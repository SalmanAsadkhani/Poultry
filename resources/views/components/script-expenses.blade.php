<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function handleExpenseFormSubmit(formId) {
            const form = document.getElementById(formId);
            if (!form) {
                return;
            }

            const modalEl = form.closest('.modal');
            const errorBox = document.createElement('div');
            errorBox.className = 'alert alert-danger mt-2';
            errorBox.style.display = 'none';
            form.prepend(errorBox);

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                errorBox.style.display = 'none';
                errorBox.innerHTML = '';

                const formData = new FormData(form);

                fetch("{{ route('expenses.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.res === 10) {
                            toastr.success(result.mySuccess);
                            setTimeout(() => {
                                location.reload();
                            }, 1500);


                            bootstrap.Modal.getInstance(modalEl).hide();
                            form.reset();
                        } else if (result.errors) {

                            let errorList = '<ul>';
                            for (const key in result.errors) {
                                result.errors[key].forEach(err => {
                                    errorList += `<li>${err}</li>`;
                                });
                            }
                            errorList += '</ul>';
                            errorBox.innerHTML = errorList;
                            errorBox.style.display = 'block';
                        } else {

                            errorBox.innerHTML = `<ul><li>${result.myAlert || 'خطایی رخ داد.'}</li></ul>`;
                            errorBox.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        errorBox.innerHTML = '<ul><li>خطایی در ارتباط با سرور رخ داد.</li></ul>';
                        errorBox.style.display = 'block';
                    });
            });
        }


        handleExpenseFormSubmit('StoreFeedForm');
        handleExpenseFormSubmit('StoreDrugForm');
        handleExpenseFormSubmit('StoreMiscForm');


    });



    document.addEventListener('DOMContentLoaded', function() {

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;


        function setupEditModal(modalId) {
            const modalEl = document.getElementById(modalId);
            if (!modalEl) return;

            const form = modalEl.querySelector('form');
            const errorBox = document.createElement('div');
            errorBox.className = 'alert alert-danger mt-2';
            errorBox.style.display = 'none';
            form.prepend(errorBox);


            modalEl.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;


                let actionUrl = "{{ route('expenses.update', ':id') }}".replace(':id', button.dataset.id);
                form.setAttribute('action', actionUrl);


                form.querySelector('input[name="id"]')?.setAttribute('value', button.dataset.id);
                form.querySelector('input[name="name"]')?.setAttribute('value', button.dataset.name);
                form.querySelector('input[name="quantity"]').value = button.dataset.quantity;
                form.querySelector('input[name="bag_count"]').value = button.dataset.bag_count;
                form.querySelector('input[name="unit_price"]').value = button.dataset.price;
                form.querySelector('textarea[name="description"]').textContent = button.dataset.description;
            });


            form.addEventListener('submit', function (e) {
                e.preventDefault();
                errorBox.style.display = 'none';
                errorBox.innerHTML = '';

                const formData = new FormData(form);

                fetch(form.getAttribute('action'), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.res === 10) {
                            toastr.success(result.mySuccess);
                            setTimeout(() => { location.reload(); }, 1500);
                            bootstrap.Modal.getInstance(modalEl).hide();
                        } else if (result.errors) {

                            let errorList = '<ul>';
                            for (const key in result.errors) {
                                result.errors[key].forEach(err => errorList += `<li>${err}</li>`);
                            }
                            errorList += '</ul>';
                            errorBox.innerHTML = errorList;
                            errorBox.style.display = 'block';
                        } else {

                            errorBox.innerHTML = `<ul><li>${result.myAlert || 'خطایی رخ داد.'}</li></ul>`;
                            errorBox.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        errorBox.innerHTML = '<ul><li>خطایی در ارتباط با سرور رخ داد.</li></ul>';
                        errorBox.style.display = 'block';
                    });
            });
        }


        setupEditModal('UpdateFeedModal');
        setupEditModal('UpdateDrugModal');
        setupEditModal('UpdateMiscModal');
    });



 document.addEventListener('DOMContentLoaded', function() {

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

     function setupDeleteModal(modalId, type) {
         const modalEl = document.getElementById(modalId);
         if (!modalEl) return;

         const form = modalEl.querySelector('form');
         const nameElement = modalEl.querySelector(`#delete-${type}-name`);
         const idInput = modalEl.querySelector(`#delete-${type}-id`);


         modalEl.addEventListener('show.bs.modal', function (event) {
             const button = event.relatedTarget;
             form.querySelector('input[name="id"]')?.setAttribute('value', button.dataset.id);

             form.action = "{{ route('expenses.destroy', ':id') }}".replace(':id', button.dataset.id);
             nameElement.textContent = button.dataset.name;
             idInput.value = button.dataset.id;
         });


         form.addEventListener('submit', function(e) {
             e.preventDefault();
             const formData = new FormData(form);

             fetch(form.action, {
                 method: 'POST',
                 headers: {
                     'X-CSRF-TOKEN': csrfToken,
                     'Accept': 'application/json'
                 },
                 body: formData
             })
                 .then(response => response.json())
                 .then(result => {
                     if (result.res === 10) {
                         toastr.success(result.mySuccess);
                         setTimeout(() => { location.reload(); }, 1500);
                     } else {
                         toastr.error(result.myAlert || 'خطایی در حذف رخ داد.');
                     }
                 });
         });
     }

     setupDeleteModal('DeleteFeedModal', 'feed');
     setupDeleteModal('DeleteDrugModal', 'drug');
     setupDeleteModal('DeleteMiscModal', 'misc');
    });






</script>


