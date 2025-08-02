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

    });
</script>
