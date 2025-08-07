<script>

    document.addEventListener('DOMContentLoaded', function () {

        function toEnglishNumerals(str) {
            if (!str) return '';
            const persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
            const arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
            let res = str.toString();
            for (let i = 0; i < 10; i++) {
                res = res.replace(new RegExp(persian[i], 'g'), i)
                    .replace(new RegExp(arabic[i], 'g'), i);
            }
            return res;
        }


        function formatNumberInput(input) {

            let value = toEnglishNumerals(input.value).replace(/\D/g, '');

            input.value = value ? parseInt(value, 10).toLocaleString('en-US') : '';
        }

        const inputs = document.querySelectorAll('input[type="tel"]');
        inputs.forEach(input => {

            input.addEventListener('input', () => formatNumberInput(input));
            formatNumberInput(input);
        });


        const forms = document.querySelectorAll('form');
        forms.forEach(form => {

            form.addEventListener('submit', function () {
                form.querySelectorAll('input[type="tel"]').forEach(input => {
                    input.value = toEnglishNumerals(input.value).replace(/,/g, '');
                });
            });
        });


        $(document).on('click', '[data-validate="true"], button[type="submit"]', function (e) {
            let valid = true;

            const scope = $(this).closest('form').length
                ? $(this).closest('form')
                : $(this).closest('tr, .card, .box, body');

            const requiredInputs = scope.find('.validate-required');

            requiredInputs.each(function () {
                const input = $(this);
                const value = input.val() ? input.val().trim() : '';
                input.next('.validation-error').remove();
                if (value === '') {
                    const message = input.data('error-message') || 'لطفاً این فیلد را پر کنید.';
                    input.after(`<div class="validation-error text-danger mt-1" style="font-size: 12px;">${message}</div>`);
                    input.addClass('is-invalid');
                    valid = false;
                } else {
                    input.removeClass('is-invalid');
                }
            });

            if (!valid) {
                e.preventDefault();
                return false;
            }
        });


    });

</script>

<style>
    .is-invalid {
        border-color: #dc3545 !important;
    }
</style>
