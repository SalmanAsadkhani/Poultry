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
                if (type_modal === 'expense'){
                    form.action = button.dataset.update_url;
                    form.querySelector('input[name="id"]')?.setAttribute('value', button.dataset.id);
                    form.querySelector('input[name="name"]')?.setAttribute('value', button.dataset.name);
                    form.querySelector('input[name="quantity"]').value = button.dataset.quantity;
                    form.querySelector('input[name="bag_count"]').value = button.dataset.bag_count ?? '-';
                    form.querySelector('input[name="unit_price"]').value = button.dataset.price;
                    form.querySelector('textarea[name="description"]').textContent = button.dataset.description;
                }

                else if (type_modal === 'income'){
                    form.action = button.dataset.update_url;
                    form.querySelector('input[name="id"]')?.setAttribute('value', button.dataset.id);
                    form.querySelector('input[name="name"]')?.setAttribute('value', button.dataset.name);
                    form.querySelector('input[name="quantity"]').value = button.dataset.quantity;
                    form.querySelector('input[name="weight"]').value = button.dataset.weight;
                    form.querySelector('input[name="price"]').value = button.dataset.price;
                    form.querySelector('textarea[name="description"]').textContent = button.dataset.description;
                }
            });

        }

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
                    form.action = button.dataset.delete_url
                    form.querySelector('input[name="id"]')?.setAttribute('value', button.dataset.id);

                }

                else if (type_modal === 'income'){
                    form.action = button.dataset.delete_url
                    form.action = "{{ route('income.destroy', ':id') }}".replace(':id', button.dataset.id);
                    form.querySelector('input[name="id"]')?.setAttribute('value', button.dataset.id);
                }
            });

        }

        setupEditModal('UpdateFeedModal');
        setupEditModal('UpdateDrugModal');
        setupEditModal('UpdateMiscModal');
        setupEditModal('UpdateChickenModal');
        setupEditModal('UpdateMiscIncomeModal');
         // delete

        setupDeleteModal('DeleteFeedModal', 'feed');
        setupDeleteModal('DeleteDrugModal', 'drug');
        setupDeleteModal('DeleteMiscModal', 'misc');
        setupDeleteModal('DeleteMiscIncomeModal', 'misc');
        setupDeleteModal('DeleteChickenModal', 'chicken');

    });

</script>


