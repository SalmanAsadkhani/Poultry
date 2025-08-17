<script>

    document.addEventListener('DOMContentLoaded', function() {

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
                const type_modal = button.dataset.type_modal;

                console.log(type_modal);
                if (type_modal === 'expense'){
                    form.action = "{{ route('expenses.update', ':id') }}".replace(':id', button.dataset.id);
                    form.querySelector('input[name="id"]')?.setAttribute('value', button.dataset.id);
                    form.querySelector('input[name="name"]')?.setAttribute('value', button.dataset.name);
                    form.querySelector('input[name="quantity"]').value = button.dataset.quantity;
                    form.querySelector('input[name="bag_count"]').value = button.dataset.bag_count ?? '-';
                    form.querySelector('input[name="unit_price"]').value = button.dataset.price;
                    form.querySelector('textarea[name="description"]').textContent = button.dataset.description;
                }

                else if (type_modal === 'income'){
                    form.action = "{{ route('income.update', ':id') }}".replace(':id', button.dataset.id);
                    form.querySelector('input[name="id"]')?.setAttribute('value', button.dataset.id);
                    form.querySelector('input[name="name"]')?.setAttribute('value', button.dataset.name);
                    form.querySelector('input[name="quantity"]').value = button.dataset.quantity;
                    form.querySelector('input[name="weight"]').value = button.dataset.weight;
                    form.querySelector('input[name="price"]').value = button.dataset.price;
                    form.querySelector('textarea[name="description"]').textContent = button.dataset.description;
                }
            });

        }


        setupEditModal('UpdateFeedModal');
        setupEditModal('UpdateDrugModal');
        setupEditModal('UpdateMiscModal');
        setupEditModal('UpdateChickenModal');
    });

    document.addEventListener('DOMContentLoaded', function() {

     function setupDeleteModal(modalId) {
         const modalEl = document.getElementById(modalId);
         if (!modalEl) return;

         const form = modalEl.querySelector('form');
         const DeleteSpanName  = modalEl.querySelector('#DeleteName');

         modalEl.addEventListener('show.bs.modal', function (event) {
             const button = event.relatedTarget;
             const type_modal =  button.dataset.type_modal;
             DeleteSpanName.innerHTML = button.dataset.name;


             if (type_modal === 'expense') {
                form.action = "{{ route('expenses.destroy', ':id') }}".replace(':id', button.dataset.id);
                form.querySelector('input[name="id"]')?.setAttribute('value', button.dataset.id);

             }

            else if (type_modal === 'income'){
                 form.action = "{{ route('income.destroy', ':id') }}".replace(':id', button.dataset.id);
                 form.querySelector('input[name="id"]')?.setAttribute('value', button.dataset.id);
            }
         });

     }

     setupDeleteModal('DeleteFeedModal', 'feed');
     setupDeleteModal('DeleteDrugModal', 'drug');
     setupDeleteModal('DeleteMiscModal', 'misc');
     setupDeleteModal('DeleteChickenModal', 'chicken');
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


