@extends('layouts.app')

@section('title','دوره ها')

@section('js')
    <script>
        $(document).ready(function() {
            $('.save-report').on('click', function () {

                const button = $(this);
                const id = button.data('id');
                const row = button.closest('tr');


                const formData = new FormData();
                formData.append('mortality', row.find(`input[name="mortality[${id}]"]`).val());
                formData.append('actions', row.find(`input[name="actions[${id}]"]`).val());
                formData.append('desc', row.find(`input[name="desc[${id}]"]`).val());
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content); // ارسال توکن CSRF

                button.prop('disabled', true).text('در حال ثبت...');


                let url = "{{ route('daily.confirm', ':id') }}";
                url = url.replace(':id', id);


                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('خطا در پاسخ سرور');
                        }
                        return response.json();
                    })
                    .then(data => {

                        button.removeClass('btn-primary').addClass('btn-success').text('ثبت شد');
                        row.addClass('table-success');
                    })
                    .catch(error => {

                        console.error('خطا در ارسال اطلاعات:', error);
                        alert('مشکلی در ثبت اطلاعات پیش آمد. لطفا دوباره تلاش کنید.');
                        button.prop('disabled', false).text('ثبت');
                    });
            });
        });
    </script>
@endsection

@section('main')

    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="body">

                            {{--                            <div class="row">--}}
                            {{--                                <div class="col-lg-3 col-sm-6">--}}
                            {{--                                    <div class="support-box text-center l-bg-red">--}}
                            {{--                                        <div class="icon m-b-10">--}}
                            {{--                                            <div class="chart chart-bar">6,4,8,6,8,10,5,6,7,9,5,6,4,8,6,8,10,5,6,7,9,5</div>--}}
                            {{--                                        </div>--}}
                            {{--                                        <div class="text m-b-10">مجموع بلیط</div>--}}
                            {{--                                        <h3 class="m-b-0">1512--}}
                            {{--                                            <i class="material-icons">trending_up</i>--}}
                            {{--                                        </h3>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="col-lg-3 col-sm-6">--}}
                            {{--                                    <div class="support-box text-center l-bg-cyan">--}}
                            {{--                                        <div class="icon m-b-10">--}}
                            {{--                                            <span class="chart chart-line">9,4,6,5,6,4,7,3</span>--}}
                            {{--                                        </div>--}}
                            {{--                                        <div class="text m-b-10">پاسخ</div>--}}
                            {{--                                        <h3 class="m-b-0">1236--}}
                            {{--                                            <i class="material-icons">trending_up</i>--}}
                            {{--                                        </h3>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="col-lg-3 col-sm-6">--}}
                            {{--                                    <div class="support-box text-center l-bg-orange">--}}
                            {{--                                        <div class="icon m-b-10">--}}
                            {{--                                            <div class="chart chart-pie">30, 35, 25, 8</div>--}}
                            {{--                                        </div>--}}
                            {{--                                        <div class="text m-b-10">برطرف کردن</div>--}}
                            {{--                                        <h3 class="m-b-0">1107--}}
                            {{--                                            <i class="material-icons">trending_down</i>--}}
                            {{--                                        </h3>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="col-lg-3 col-sm-6">--}}
                            {{--                                    <div class="support-box text-center green">--}}
                            {{--                                        <div class="icon m-b-10">--}}
                            {{--                                            <div class="chart chart-bar">4,6,-3,-1,2,-2,4,3,6,7,-2,3,4,6,-3,-1,2,-2,4,3,6,7,-2,3</div>--}}
                            {{--                                        </div>--}}
                            {{--                                        <div class="text m-b-10">در انتظار</div>--}}
                            {{--                                        <h3 class="m-b-0">167--}}
                            {{--                                            <i class="material-icons">trending_down</i>--}}
                            {{--                                        </h3>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}

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
                                        <th class="center">ملاحضات</th>
                                        <th class="center">جزییات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($breedingCycle->dailyReports as $report)
                                        <tr>
                                            <td>{{ $report->days_number }}</td>
                                            <td>{{ $report->date }}</td>
                                            <td><input name="mortality[{{ $report->id }}]" value="{{ $report->mortality_count }}"></td>
                                            <td>{{ $report->cycle->dailyReports()->where('days_number', '<=', $report->days_number)->sum('mortality_count') }}</td>
                                            <td><input name="actions[{{ $report->id }}]" value="{{ $report->actions_taken }}"></td>
                                            <td>{{ $report->feed_type }}</td>
                                            <td><input name="desc[{{ $report->id }}]" value="{{ $report->description }}"></td>
                                            <td><button class="btn btn-primary save-report" data-id="{{ $report->id }}">ثبت</button></td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

