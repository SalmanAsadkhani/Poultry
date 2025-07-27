@props([
    'data' => [],
    'searchableKeys' => ''
])

<div
    x-data="{
        isMobile: window.innerWidth < 768,
        search: '',
        // تابع برای فیلتر کردن
        filter(item) {
            if (this.search === '') return true;
            // کلیدهای قابل جستجو را با کاما جدا کرده و جستجو می‌کند
            return '{{ $searchableKeys }}'.split(',').some(key =>
                String(item[key]).toLowerCase().includes(this.search.toLowerCase())
            );
        }
    }"
    @resize.window.debounce.200ms="isMobile = window.innerWidth < 768"
    class="w-100">

    <div class="form-group mb-3">
        <input type="text" x-model="search" class="form-control form-control-sm" placeholder="جستجو...">
    </div>

    <div class="table-responsive" x-show="!isMobile" x-transition.opacity>
        <table {{ $attributes->merge(['class' => 'table table-hover']) }}>
            <thead>
            {{ $thead }}
            </thead>
            <tbody>
            <template x-for="item in {{ $data }}.filter(filter)" :key="item.id">
                {{ $tbody }}
            </template>
            </tbody>
        </table>
    </div>

    <div x-show="isMobile" x-transition.opacity>
        <template x-for="item in {{ $data }}.filter(filter)" :key="item.id">
            <div class="card mobile-card">
                {{ $tbody }}
            </div>
        </template>
    </div>
</div>

<style>
    .mobile-card {
        margin-bottom: 1rem;
        border: 1px solid #dee2e6;
        border-radius: .375rem;
        box-shadow: 0 1px 3px rgba(0,0,0,.05);
        padding: .5rem;
    }
    .mobile-card > div {
        display: flex;
        justify-content: space-between;
        padding: .6rem .5rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .mobile-card > div:last-child {
        border-bottom: none;
    }
    .mobile-card .label {
        font-weight: bold;
        color: #555;
    }
    .mobile-card .value {
        text-align: left;
    }
    .mobile-card .value input,
    .mobile-card .value button {
        width: 100%;
        box-sizing: border-box;
    }
</style>
