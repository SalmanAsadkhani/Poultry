<script>
    document.addEventListener('DOMContentLoaded', function() {

        function setupEditModal(modalId) {
            const modalEl = document.getElementById(modalId);
            if (!modalEl) return;

            console.log(modalId)
            const form = modalEl.querySelector('form');
            const errorBox = document.createElement('div');
            errorBox.className = 'alert alert-danger mt-2';
            errorBox.style.display = 'none';
            form.prepend(errorBox);

            modalEl.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const type_modal = button.dataset.type_modal;

                if (type_modal === 'expense'){
                    form.action = button.dataset.update_url;
                    form.querySelector('input[name="id"]').value = button.dataset.category_id;
                    form.querySelector('input[name="name"]').value = button.dataset.name;
                    form.querySelector('select[name="breeding_cycle_id"]').value = button.dataset.breeding_cycle_id;
                    form.querySelector('select[name="expense_category"]').value = button.dataset.category_type;
                }

                else if (type_modal === 'income'){
                    form.action = button.dataset.update_url;
                    form.querySelector('input[name="name"]').value = button.dataset.name;
                    form.querySelector('select[name="breeding_cycle_id"]').value = button.dataset.breeding_cycle_id;
                    form.querySelector('select[name="income_category"]').value = button.dataset.category_type;

                }
            });

        }

        setupEditModal('UpdateInvoiceModal');
    })


    document.addEventListener('DOMContentLoaded', function() {

        function setupDeleteModal(modalId) {
            const modalEl = document.getElementById(modalId);
            if (!modalEl) return;

            const form = modalEl.querySelector('form');

            modalEl.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const type_modal =  button.dataset.type_modal;

                if (type_modal === 'expense') {
                    form.action = button.dataset.delete_url;
                    form.querySelector('input[name="id"]').value = button.dataset.category_id;
                    form.querySelector('input[name="category_type"]').value = button.dataset.category_type;

                }

                else if (type_modal === 'income'){
                    form.action = button.dataset.delete_url;
                    form.querySelector('input[name="id"]').value = button.dataset.id;
                    form.querySelector('input[name="category_type"]').value = button.dataset.category_type;
                }
            });

        }

        setupDeleteModal('DeleteInvoiceModal');

    });


    document.addEventListener('DOMContentLoaded', function () {
        const allEditModals = document.querySelectorAll('.edit-modal');

        allEditModals.forEach(modal => {
            modal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const form = this.querySelector('form');

                for (const key in button.dataset) {

                    let fieldName = key;

                    if (key === 'price') {
                        fieldName = 'unit_price';
                    }

                    const input = form.querySelector(`[name="${fieldName}"]`);

                    if (input) {
                        input.value = button.dataset[key];
                    }
                }
            });

        });
    });
</script>
