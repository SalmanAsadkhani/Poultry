@extends('layouts.app')

@section('title','دوره ها')

@section('js')

    <script>
        $(document).ready(function() {

            $(document).on('click', '.add-feed-row', function() {
                const button = $(this);
                const reportId = button.data('report-id');
                const wrapper = button.prev('.feed-consumption-wrapper');
                const newIndex = 'new_' + Date.now();

                const newRowHtml = `
                <div class="row feed-consumption-row mb-2">
                    <div class="col">
                        <select name="feeds[${reportId}][${newIndex}][type]" class="form-select form-select-sm" style="display: block">
                            <option value="استارتر">استارتر</option>
                            <option value="پیش دان">پیش دان</option>
                            <option value="میان دان">میان دان</option>
                            <option value="پس دان">پس دان</option>
                        </select>
                    </div>
                    <div class="col">
                        <input type="number" name="feeds[${reportId}][${newIndex}][bags]" class="form-control form-control-sm" placeholder="تعداد">
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-danger remove-feed-row btn-extra-sm">حذف</button>
                    </div>
                </div>
            `;
                wrapper.append(newRowHtml);
            });

            $(document).on('click', '.remove-feed-row', function() {
                $(this).closest('.feed-consumption-row').remove();
            });


            $(document).on('click', '.save-report', function (e) {
                e.preventDefault();

                const button = $(this);
                const id = button.data('id');
                const container = button.closest('tr').length ? button.closest('tr') : button.closest('.card-body');

                const formData = new FormData();
                formData.append('mortality', container.find('input[name*="mortality"]').val());
                formData.append('actions', container.find('input[name*="actions"]').val());
                formData.append('desc', container.find('input[name*="desc"]').val());
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('daily_id', id);


                const feedConsumptions = [];
                container.find('.feed-consumption-row:visible').each(function() {
                    const row = $(this);
                    const type = row.find('select[name*="[type]"]').val();
                    const bags = row.find('input[name*="[bags]"]').val();
                    const consumptionId = row.find('input[name*="[id]"]').val();
                    if (type && bags) {
                        feedConsumptions.push({ id: consumptionId || null, type: type, bags: bags });
                    }
                });

                formData.append('feeds', JSON.stringify(feedConsumptions));

                let url = "{{ route('daily.confirm', ':id') }}";
                url = url.replace(':id', id);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: { 'Accept': 'application/json' },
                    success: function (result) {
                        if (result.res === 10) {
                            toastr.success(result.mySuccess);
                            setTimeout(() => { location.reload(); }, 1500);
                        } else {
                            toastr.error(result.myAlert);
                        }
                    },
                    error: function (xhr) {
                        if (!navigator.onLine || xhr.status === 0) {
                            toastr.warning('شما آفلاین هستید,امکان ثبت اطلاعات وجود ندارد.')
                        } else {


                            let err = 'خطایی در ارسال داده‌ها رخ داد';
                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                                err = Object.values(xhr.responseJSON.errors).join(' - ');
                            } else if (xhr.responseJSON?.myAlert) {
                                err = xhr.responseJSON.myAlert;
                            }
                            toastr.error(err);
                        }
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const scrollToLastButton = document.getElementById('scrollToTodayBtn');


            const lastReportElements = document.querySelectorAll('.last-report-row');
            let visibleLastReportRow = null;

            for (const element of lastReportElements) {
                if (element.offsetParent !== null) {
                    visibleLastReportRow = element;
                    break;
                }
            }

            if (!visibleLastReportRow) {
                scrollToLastButton.style.display = 'none';
                return;
            }

            scrollToLastButton.addEventListener('click', function () {
                visibleLastReportRow.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                visibleLastReportRow.classList.add('highlight-row');
                setTimeout(() => {
                    visibleLastReportRow.classList.remove('highlight-row');
                }, 2000);
            });
        });
    </script>

@endsection

@section('css')
    @keyframes highlight-fade {
    from { background-color: #d1ecf1; }
    to { background-color: transparent; }
    }

    .highlight-row {
    animation: highlight-fade 2s ease-out;
    }
@endsection

@section('main')

    <section class="content">
        <div class="container-fluid">

            <div class="container my-5">
                <div class="d-flex flex-wrap align-items-center g-4">
                    <div class="col-lg-4 col-md-3">
                        <div class="card shadow-sm border-0 h-100 text-center">
                            <div class="card-body">
                                <h6 class="text-muted mb-4"> سن جوجه </h6>
                                <h4 class="fw-bold text-danger">{{ $chickAge }} روزه</h4>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-4 col-md-3">
                        <div class="card shadow-sm border-0 h-100 text-center">
                            <div class="card-body">
                                <h6 class="text-muted mb-4"> جمع تلفات کل</h6>

                                <h4 Class="fw-bold text-primary">{{ sep($cycle->total_mortality) }} تلفات</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-3">
                        <div class="card shadow-sm border-0 h-100 text-center">
                            <div class="card-body">
                                <h6 class="text-muted mb-4">جمع کل دان مصرفی (کیلوگرم)</h6>
                                <h4 class="fw-bold text-success">{{ sep($grandTotalWeight) }}</h4>
                            </div>
                        </div>
                    </div>

                    @if(!empty($feedSummary))
                        <div class="col-lg-4 col-md-3">
                            <div class="card shadow-sm border-0 h-100 text-center">
                                <div class="card-body">
                                        <h6 class="text-muted mb-4"> خلاصه مصرف دان</h6>
                                    @foreach($feedSummary as $summary)
                                       <div>
                                           <h5  class="fw-bold text-primary mb-2">{{ $summary['name'] }}:</h5>
                                           <h6  class="fw-bold text-secondary mb-4">{{ sep($summary['total_weight_used']) }} کیلوگرم
                                               <small>(از {{ $summary['bags_used'] }} کیسه)</small>
                                           </h6>
                                       </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif


                </div>
            </div>


            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="body">
                            <div class="text-right mb-3">
                                <button id="scrollToTodayBtn" class="btn btn-sm btn-info mb-3">پرش به گزارش امروز</button>
                            </div>
                            <x-responsive-display>

                                <x-slot:desktop>
                                    <div class="table-responsive">
                                        <table id="tableExport" class="display table table-hover table-checkable order-column width-per-100">
                                            <thead>
                                            <tr>
                                                <th class="center">سن جوجه</th>
                                                <th class="center">تاریخ</th>
                                                <th class="center">تلفات</th>
                                                <th class="center">جمع تلفات</th>
                                                <th class="center">داروی مصرفی و واکسن</th>
                                                <th class="center">دان مصرفی <small>(نوع و تعداد کیسه)</small></th>
                                                <th class="center">مقدار دان مصرف شده </th>
                                                <th class="center">ملاحضات</th>
                                                <th class="center">جزییات</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($cycle->dailyReports as $report)
                                                <tr @if($loop->last) class="last-report-row" @endif>
                                                    <td>{{ $report->days_number }}</td>

                                                    <td>{{ $report->daily_date }}</td>

                                                    <td><input type="tel" name="mortality[{{ $report->id }}]" class="validate-required" value="{{ $report->mortality_count }}"  data-error-message="تعداد تلفات را وارد نمایید"></td>

                                                    <td>{{ $report->total_mortality }}</td>

                                                    <td><input name="actions[{{ $report->id }}]" value="{{ $report->actions_taken }}" ></td>

                                                    <td>
                                                        @include('partials.feed_consumption_form' , ['report' => $report])
                                                    </td>

                                                    <td>{{ sep($report->feed_daily_used) }} <small>(کیلوگرم)</small></td>

                                                    <td><input name="desc[{{ $report->id }}]"  value="{{ $report->description }}"></td>

                                                    <td><button class="btn btn-primary save-report ajax-form "  data-validate="true"  data-id="{{ $report->id }}">ثبت</button></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </x-slot:desktop>

                                <x-slot:mobile>

                                    @foreach($cycle->dailyReports as $report)
                                        <div @class(['card', 'mb-3', 'last-report-row' => $loop->last])
                                             x-data="{ open: {{ $loop->last ? 'true' : 'false' }} }">

                                                <div class="card-header d-flex justify-content-between align-items-center" @click="open = !open" style="cursor: pointer;">
                                                    <strong>روز {{ $report->days_number }}</strong> - <span>{{ $report->daily_date }}</span>
                                                    <span x-show="!open">▼</span>
                                                    <span x-show="open">▲</span>
                                                </div>


                                                <div class="card-body" x-show="open" x-transition>
                                                    <div class="form-group">
                                                        <label class="bold text-danger ">تلفات:</label>
                                                        <input type="tel" class="form-control" name="mortality_mobile[{{ $report->id }}]" value="{{ $report->mortality_count }}">
                                                    </div>

                                                    <div class="bold text-danger mb-5">جمع تلفات:
                                                        <span class="text-dark">{{ $report->total_mortality }}</span>
                                                    </div>


                                                    <div class="form-group">
                                                        <label class="bold text-danger">داروی مصرفی:</label>
                                                        <input class="form-control" name="actions_mobile[{{ $report->id }}]" value="{{ $report->actions_taken }}">
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="bold text-danger">دان مصرفی:</label>
                                                        <div class="mt-2">
                                                            @include('partials.feed_consumption_form', ['report' => $report])
                                                        </div>
                                                    </div>


                                                    <div class="bold text-danger mb-5"> مقدار دان مصرف شده
                                                        <span class="text-dark">{{ $report->feed_daily_used }} <small>(کیلوگرم)</small></span>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="bold text-danger">ملاحظات:</label>
                                                        <input class="form-control" name="desc_mobile[{{ $report->id }}]" value="{{ $report->description }}">
                                                    </div>
                                                    <button class="btn btn-primary mt-2 save-report" data-id="{{ $report->id }}">ثبت</button>
                                                </div>
                                            </div>
                                        @endforeach

                                </x-slot:mobile>

                            </x-responsive-display>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

