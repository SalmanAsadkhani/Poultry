<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register({{ asset("service-worker.js") }})
                .then(registration => {
                    console.log('Service Worker registered successfully: ', registration);
                })
                .catch(err => {
                    console.log('Service Worker registration failed: ', err);
                });
        });
    }

    let deferredPrompt;

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;


        const installButton = document.getElementById('installButton');
        if (installButton) {
            installButton.style.display = 'block';
        }
    });

    const installButton = document.getElementById('installButton');
    if (installButton) {
        installButton.addEventListener('click', async () => {
            if (deferredPrompt) {

                deferredPrompt.prompt();


                const { outcome } = await deferredPrompt.userChoice;
                console.log(`User response to the install prompt: ${outcome}`);

                deferredPrompt = null;

                installButton.style.display = 'none';
            }
        });
    }



    let refreshing;

    navigator.serviceWorker.addEventListener('controllerchange', () => {
        if (refreshing) return;
        refreshing = true;

        toastr.info('نسخه جدیدی از برنامه در دسترس است، در حال بارگذاری مجدد...');
        setTimeout(() => {
            window.location.reload();
        }, 1500);
    });

</script>




