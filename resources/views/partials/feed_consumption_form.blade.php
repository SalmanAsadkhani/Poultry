<div class="feed-consumption-wrapper">
    @forelse($report->feedConsumptions as $consumption)
        <div class="row feed-consumption-row mb-2">
            <input type="hidden" name="feeds[{{ $report->id }}][{{ $loop->index }}][id]" value="{{ $consumption->id }}">
            <div class="col">
                <select name="feeds[{{ $report->id }}][{{ $loop->index }}][type]" class="form-select form-select-sm" style="display: block">
                    @foreach(['استارتر', 'پیش دان', 'میان دان', 'پس دان'] as $type)
                        <option value="{{ $type }}" @if($consumption->feed_type == $type) selected @endif>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <input type="tel" dir="rtl" name="feeds[{{ $report->id }}][{{ $loop->index }}][bags]" class="form-control form-control-sm validate-required" placeholder="تعداد" value="{{ $consumption->bag_count }}" min="1"  data-error-message="تعداد کیسه باید حداقل ۱ باشد.">
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-danger remove-feed-row btn-extra-sm">حذف</button>
            </div>
        </div>
    @empty
        <div class="row feed-consumption-row mb-2">
            <div class="col">
                <select name="feeds[{{ $report->id }}][0][type]" class="form-select form-select-sm" style="display: block">
                    @foreach(['استارتر', 'پیش دان', 'میان دان', 'پس دان'] as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <input type="tel" dir="rtl" name="feeds[{{ $report->id }}][0][bags]" class="form-control form-control-sm validate-required" placeholder="تعداد"  data-error-message="تعداد کیسه باید حداقل ۱ باشد.">
            </div>
            <div class="col-auto"></div>
        </div>
    @endforelse
</div>
<button type="button" class="btn btn-outline-success mt-2 add-feed-row btn-extra-sm" data-report-id="{{ $report->id }}">+ افزودن</button>


<style>
    .btn-extra-sm {
        padding: 0.15rem 0.4rem;
        font-size: 0.75rem;
        border-radius: 0.2rem;
    }
</style>
