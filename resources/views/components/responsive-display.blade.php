{{-- این کامپوننت دو اسلات به نام دسکتاپ و موبایل دارد --}}
<div class="responsive-container">

    {{-- این بخش فقط در دسکتاپ نمایش داده می‌شود --}}
    <div class="desktop-view">
        {{ $desktop }}
    </div>

    {{-- این بخش فقط در موبایل نمایش داده می‌شود --}}
    <div class="mobile-view">
        {{ $mobile }}
    </div>

</div>

{{-- استایل‌های لازم برای تعویض ظاهر --}}
<style>
    .mobile-view {
        display: none;
    }
    .card.mobile-card { /* استایل برای کارت‌های موبایل */
        margin-bottom: 1rem;
    }
    @media (max-width: 768px) {
        .desktop-view { display: none; }
        .mobile-view { display: block; }
    }
</style>
