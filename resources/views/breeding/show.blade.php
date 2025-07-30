@extends('layouts.app')

@section('title','دوره ها')

@section('js')


    <script>
        $(document).ready(function() {
            $('.save-report').on('click', function (e) {
                e.preventDefault();

                const button = $(this);
                const id = button.data('id');

                let mortality, actions, desc , feed;


                if (button.closest('tr').length) {
                    const row = button.closest('tr');
                    mortality = row.find(`input[name="mortality[${id}]"]`).val();
                    feed = row.find(`input[name="feed[${id}]"]`).val();
                    actions = row.find(`input[name="actions[${id}]"]`).val();
                    desc = row.find(`input[name="desc[${id}]"]`).val();
                } else {
                    const card = button.closest('.card-body');
                    mortality = card.find(`input[name="mortality_mobile[${id}]"]`).val();
                    feed = card.find(`input[name="feed_mobile[${id}]"]`).val();
                    actions = card.find(`input[name="actions_mobile[${id}]"]`).val();
                    desc = card.find(`input[name="desc_mobile[${id}]"]`).val();
                }


                const formData = new FormData();
                formData.append('mortality', mortality);
                formData.append('feed', feed);
                formData.append('actions', actions);
                formData.append('desc', desc);
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('daily_id', id);

                let url = "{{ route('daily.confirm', ':id') }}";
                url = url.replace(':id', id);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'Accept': 'application/json'
                    },
                    success: function (result) {
                        if (result.res === 10) {
                            toastr.success(result.mySuccess);
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            toastr.error(result.myAlert);
                        }
                    },
                    error: function (xhr) {

                        let err = 'خطایی در ارسال داده‌ها رخ داد';

                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {

                            err = Object.values(xhr.responseJSON.errors).join(' - ');

                        } else if (xhr.responseJSON?.myAlert) {

                            err = xhr.responseJSON.myAlert;

                        }
                        toastr.error(err);
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const scrollToLastButton = document.getElementById('scrollToTodayBtn');

            // تمام عناصری که این کلاس را دارند، پیدا کن
            const lastReportElements = document.querySelectorAll('.last-report-row');
            let visibleLastReportRow = null;

            // در میان آنها بگرد و عنصری که قابل مشاهده است را پیدا کن
            for (const element of lastReportElements) {
                // el.offsetParent !== null یک راه خوب برای چک کردن قابل مشاهده بودن است
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
                                <h4 class="fw-bold text-danger">{{$chickAge}} روزه</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-3">
                        <div class="card shadow-sm border-0 h-100 text-center">
                            <div class="card-body">
                                <h6 class="text-muted mb-4"> جمع تلفات کل</h6>
                                <h4 class="fw-bold text-primary">{{$total_mortality}} تلفات</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-3">
                        <div class="card shadow-sm border-0 h-100 text-center">
                            <div class="card-body">
                                <h6 class="text-muted mb-4">تعداد کل دان مصرفی</h6>
                                <h4 class="fw-bold text-price">  {{$total_feed}}کیسه</h4>
                            </div>
                        </div>
                    </div>

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
                                                <th class="center">دان</th>
                                                <th class="center">تعداد دان مصرفی <small>(کیسه)</small></th>
                                                <th class="center">ملاحضات</th>
                                                <th class="center">جزییات</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($breedingCycle->dailyReports as $report)
                                                <tr  @if($loop->last)  class="last-report-row" @endif>


                                                    <td>{{ $report->days_number }}</td>

                                                    <td>{{ $report->daily_date}}</td>

                                                    <td><input name="mortality[{{ $report->id }}]" value="{{ $report->mortality_count }}"></td>

                                                    <td>{{ $report->total_mortality}}</td>

                                                    <td><input name="actions[{{ $report->id }}]" value="{{ $report->actions_taken }}"></td>

                                                    <td>{{ $report->feed_type }}</td>

                                                    <td><input name="feed[{{ $report->id }}]" value="{{ $report->feed_count }}"></td>

                                                    <td><input name="desc[{{ $report->id }}]" value="{{ $report->description }}"></td>

                                                    <td><button class="btn btn-primary save-report" data-id="{{ $report->id }}">ثبت</button></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </x-slot:desktop>


                                <x-slot:mobile>

                                        @foreach($breedingCycle->dailyReports as $report)
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
                                                        <input class="form-control" name="mortality_mobile[{{ $report->id }}]" value="{{ $report->mortality_count }}">
                                                    </div>

                                                    <div class="bold text-danger mb-5">جمع تلفات:
                                                        <span class="text-dark">{{ $report->total_mortality }}</span>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="bold text-danger">داروی مصرفی:</label>
                                                        <input class="form-control" name="actions_mobile[{{ $report->id }}]" value="{{ $report->actions_taken }}">
                                                    </div>

                                                    <div class="bold text-danger mb-5">دان:
                                                        <span class="text-dark">{{ $report->feed_type }}</span>
                                                    </div>

                                                    <div class="text-danger mt-2 mb-3 ">  تعداد دان مصرفی  <small class="text-dark">(کیسه)</small>:
                                                        <input class="form-control" name="feed_mobile[{{ $report->id }}]" value="{{ $report->feed_count }}">
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

