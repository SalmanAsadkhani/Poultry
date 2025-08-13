document.addEventListener('DOMContentLoaded', function () {

    const dbPromise = new Promise((resolve, reject) => {
        const request = indexedDB.open('offline-submissions-db', 1);
        request.onupgradeneeded = event => {
            const db = event.target.result;
            if (!db.objectStoreNames.contains('submissions')) {
                db.createObjectStore('submissions', { keyPath: 'id' });
            }
        };
        request.onsuccess = event => resolve(event.target.result);
        request.onerror = event => reject(event.target.error);
    });

    const idbStore = {
        async set(storeName, value) {
            const db = await dbPromise;
            return new Promise((resolve, reject) => {
                const tx = db.transaction(storeName, 'readwrite');
                tx.objectStore(storeName).put(value);
                tx.oncomplete = () => resolve();
                tx.onerror = event => reject(event.target.error);
            });
        },
        async getAll(storeName) {
            const db = await dbPromise;
            return new Promise((resolve, reject) => {
                const tx = db.transaction(storeName, 'readonly');
                const request = tx.objectStore(storeName).getAll();
                request.onsuccess = () => resolve(request.result);
                request.onerror = event => reject(event.target.error);
            });
        },
        async delete(storeName, key) {
            const db = await dbPromise;
            return new Promise((resolve, reject) => {
                const tx = db.transaction(storeName, 'readwrite');
                tx.objectStore(storeName).delete(key);
                tx.oncomplete = () => resolve();
                tx.onerror = event => reject(event.target.error);
            });
        }
    };


    const NumberFormatterService = {
        toEnglishNumerals(str) {
            if (str === null || typeof str === 'undefined') return '';
            const persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
            const arabic  = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
            let res = String(str);
            for (let i=0;i<10;i++) {
                res = res.replace(new RegExp(persian[i],'g'), i).replace(new RegExp(arabic[i],'g'), i);
            }
            return res;
        },
        cleanInput(input){ input.value = this.toEnglishNumerals(input.value).replace(/,/g,''); },
        formatInput(input){ const v = this.toEnglishNumerals(input.value).replace(/\D/g,''); input.value = v ? parseInt(v,10).toLocaleString('en-US') : ''; }
    };


    function validateForm(form){
        let ok = true;
        form.querySelectorAll('.validation-error').forEach(e => e.remove());
        const required = form.querySelectorAll('.validate-required');
        required.forEach(inp => inp.classList.remove('is-invalid'));
        required.forEach(inp => {
            if (!inp.value || inp.value.toString().trim() === ''){
                const msg = inp.dataset.errorMessage || 'لطفاً این فیلد را پر کنید.';
                const el = document.createElement('div');
                el.className = 'validation-error text-danger mt-1';
                el.style.fontSize = '12px';
                el.textContent = msg;
                inp.parentNode.insertBefore(el, inp.nextSibling);
                inp.classList.add('is-invalid');
                if (ok) inp.focus();
                ok = false;
            }
        });
        return ok;
    }

    function genSubmissionId(){
        return 'sub-' + Date.now().toString(36) + '-' + Math.random().toString(36).slice(2,10);
    }

    function detectFieldTypes(form){
        const map = {};
        form.querySelectorAll('input,select,textarea').forEach(el => {
            const name = el.name; if (!name) return;
            if (el.dataset && el.dataset.number){
                const v = el.dataset.number.trim();
                if (v==='int'||v==='float') { map[name]=v; return; }
            }
            if (el.tagName.toLowerCase()==='input' && el.type==='number'){
                const step = el.getAttribute('step');
                map[name] = (step && step.indexOf('.')>=0)?'float':'int';
                return;
            }
            if (el.tagName.toLowerCase()==='input' && el.type==='tel'){ map[name]='int'; return; }
            if (el.classList.contains('numeric')){ map[name]='int'; return; }
        });
        return map;
    }

    function castValueIfNeeded(value, type){
        if (typeof value !== 'string') return value;
        const clean = NumberFormatterService.toEnglishNumerals(value).replace(/,/g,'').trim();
        if (clean === '') return '';
        if (type === 'int') return String(parseInt(clean,10));
        if (type === 'float') return String(parseFloat(clean));
        return value;
    }

    function autoCastValue(value){
        if (typeof value !== 'string') return value;
        const clean = NumberFormatterService.toEnglishNumerals(value).replace(/,/g,'').trim();
        if (/^-?\d+$/.test(clean)) return String(parseInt(clean,10));
        if (/^-?\d*\.\d+$/.test(clean)) return String(parseFloat(clean));
        return value;
    }

    async function saveForLater(form, formData){
        const id = genSubmissionId();
        formData.set('_submissionId', id);

        const typeInput = form.querySelector('input[name="type"]');
        if (typeInput) {
            formData.set('type', typeInput.value);
        }
        const entries = Array.from(formData.entries());


        const fieldTypes = detectFieldTypes(form);
        const payload    = { id, url: form.action, method: (form.method||'POST').toUpperCase(), body: entries, fieldTypes, timestamp: Date.now() };
        try{
            await idbStore.set('submissions', payload);
            toastr.info('شما آفلاین هستید. اطلاعات ذخیره شد و بعداً ارسال خواهد شد.');

            setTimeout(()=> {location.reload();} , 2000);
        }catch(err){
            console.error('IDB save failed', err);
            setTimeout(()=> {location.reload();} , 2000);
            toastr.error('ذخیره محلی موفق نبود.');
        }
    }

    async function syncOfflineSubmissions(){
        if (!navigator.onLine) return;
        const submissions = await idbStore.getAll('submissions');
        if (!submissions || submissions.length===0) return;

        try{
            const tokenRes = await fetch(window.csrfTokenUrl);
            if (!tokenRes.ok) throw new Error('token failed');
            const { token: newToken } = await tokenRes.json();
            let allSent = true;
            for (const sub of submissions){
                try{
                    const fd = new FormData();
                    for (const [k,v] of sub.body){
                        let val = v;
                        if (sub.fieldTypes && sub.fieldTypes[k]) val = castValueIfNeeded(v, sub.fieldTypes[k]);
                        else val = autoCastValue(v);
                        fd.append(k, val);
                    }
                    fd.set('_token', newToken);
                    const r = await fetch(sub.url, { method: sub.method, body: fd, headers: {'X-CSRF-TOKEN': newToken, 'Accept': 'application/json'} });
                    if (r.ok){
                        await idbStore.delete('submissions', sub.id);
                    } else {
                        allSent = false;
                        const b = await r.json().catch(()=>({}));
                        console.warn('server rejected offline submission', b);
                    }
                }catch(err){
                    console.error('send error', err);
                    allSent = false;
                    if (!navigator.onLine) break;
                }
            }
            if (allSent) {
                toastr.success('همه داده‌های آفلاین ارسال شدند.');
                setTimeout(()=>location.reload(),1200);
            }
        }
        catch(err){
            console.error('sync failed', err);
            toastr.warning('همگام‌سازی آفلاین شکست خورد.');
        }
    }

    document.querySelectorAll('form').forEach(form => {
        const errorBox = document.createElement('div');
        errorBox.className = 'alert alert-danger';
        errorBox.style.display = 'none';
        form.prepend(errorBox);

        form.addEventListener('submit', async function(e){
            e.preventDefault();
            if (!validateForm(this)) return;
            this.querySelectorAll('input[type="tel"]').forEach(i=>NumberFormatterService.cleanInput(i));

            const fd = new FormData(this);

            if (!navigator.onLine){
                await saveForLater(this, fd);
                return;
            }

            try {
                const response = await fetch(this.action, {
                    method: this.method,
                    body: fd,
                    headers: {
                        'X-CSRF-TOKEN': fd.get('_token'),
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json().catch(() => ({}));

                if (response.ok) {
                    toastr.success(result.mySuccess || 'عملیات با موفقیت انجام شد.');
                    setTimeout(() => location.reload(), 1500);
                }
                else if (response.status === 422) {
                    if (result.errors) {
                        let list = '<ul>';
                        Object.values(result.errors).forEach(errs =>
                            errs.forEach(err => list += `<li>${err}</li>`)
                        );
                        list += '</ul>';
                        errorBox.innerHTML = list;
                        errorBox.style.display = 'block';
                    }
                }
                else {

                    toastr.error(result.myAlert || 'خطایی در ارتباط با سرور رخ داد.');
                }
            } catch (error) {
                if (!navigator.onLine || error.name === 'TypeError') {
                    await saveForLater(this, fd);
                }
            }
        });
    });

    window.addEventListener('online', syncOfflineSubmissions);
    if (navigator.onLine) syncOfflineSubmissions();
});
