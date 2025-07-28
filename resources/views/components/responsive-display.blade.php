
<div class="responsive-container">

    <div class="desktop-view">
        {{ $desktop }}
    </div>

    <div class="mobile-view">
        {{ $mobile }}
    </div>

</div>


<style>
    .mobile-view {
        display: none;
    }
    .card.mobile-card {
        margin-bottom: 1rem;
    }
    @media (max-width: 768px) {
        .desktop-view { display: none; }
        .mobile-view { display: block; }
    }
</style>
