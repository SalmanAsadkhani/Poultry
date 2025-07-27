@extends('layouts.app')

@section('title','دوره ها')

@section('js')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                formData.append('daily_id' , id);



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
                            toastr.success( result.mySuccess);
                            setTimeout(()=>{
                                location.reload();
                            } , 1500)
                            form.reset();
                            errorBox.style.display = 'none';

                        } else {
                            errorBox.innerHTML = `<ul><li>${result.myAlert}</li></ul>`;
                            errorBox.style.display = 'block';

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
@endsection

@section('main')

    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="body">
                            <x-responsive-display>
                                {{-- این اسلات، ظاهر دسکتاپ را مشخص می‌کند --}}
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
                                                    <td>{{ $report->total_mortality}}</td>
                                                    <td><input name="actions[{{ $report->id }}]" value="{{ $report->actions_taken }}"></td>
                                                    <td>{{ $report->feed_type }}</td>
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
                                            <div class="card mb-3" x-data="{ open: false }">
                                                <div class="card-header d-flex justify-content-between align-items-center" @click="open = !open" style="cursor: pointer;">
                                                    <strong>روز {{ $report->days_number }}</strong> - <span>{{ $report->date }}</span>
                                                    <span x-show="!open">▼</span>
                                                    <span x-show="open">▲</span>
                                                </div>


                                                <div class="card-body" x-show="open" x-transition>
                                                    <div class="form-group">
                                                        <label>تلفات:</label>
                                                        <input class="form-control" name="mortality_mobile[{{ $report->id }}]" value="{{ $report->mortality_count }}">
                                                    </div>
                                                    <p>جمع تلفات: {{ $report->total_mortality }}</p>
                                                    <div class="form-group">
                                                        <label>داروی مصرفی:</label>
                                                        <input class="form-control" name="actions_mobile[{{ $report->id }}]" value="{{ $report->actions_taken }}">
                                                    </div>
                                                    <p>دان: {{ $report->feed_type }}</p>
                                                    <div class="form-group">
                                                        <label>ملاحظات:</label>
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

