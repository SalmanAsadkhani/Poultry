@extends('layouts.app')

@section('title','دوره ها')

@section('css')

@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

{{--    <script>--}}
{{--        $(document).ready(function() {--}}

{{--                const form = $('#courseForm');--}}
{{--                const modalEl = document.getElementById(modalId);--}}


{{--                if (form.length === 0) {--}}
{{--                    return;--}}
{{--                }--}}
{{--                const errorBox = $('<div class="alert alert-danger mt-2" style="display: none;"></div>');--}}
{{--                form.prepend(errorBox);--}}

{{--                form.on('submit', function(e) {--}}
{{--                    e.preventDefault();--}}
{{--                    errorBox.hide().html('');--}}

{{--                    const formData = new FormData(this);--}}

{{--                    $.ajax({--}}
{{--                        url: form.attr('action'),--}}
{{--                        method: form.attr('method'),--}}
{{--                        data: formData,--}}
{{--                        processData: false,--}}
{{--                        contentType: false,--}}
{{--                        headers: {--}}
{{--                            'X-CSRF-TOKEN': formData.get('_token'),--}}
{{--                            'Accept': 'application/json'--}}
{{--                        },--}}
{{--                        success: function(result) {--}}
{{--                            if (result.res === 10) {--}}
{{--                                toastr.success(result.mySuccess);--}}
{{--                                if (modalEl) bootstrap.Modal.getInstance(modalEl).hide();--}}
{{--                                form[0].reset();--}}
{{--                                setTimeout(() => location.reload(), 1500);--}}
{{--                            } else {--}}
{{--                                errorBox.html(`<ul><li>${result.myAlert || 'خطایی رخ داد.'}</li></ul>`).show();--}}
{{--                            }--}}
{{--                        },--}}
{{--                        error: function(xhr) {--}}
{{--                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {--}}
{{--                                let list = '<ul>';--}}
{{--                                $.each(xhr.responseJSON.errors, function(key, errors) {--}}
{{--                                    errors.forEach(err => list += `<li>${err}</li>`);--}}
{{--                                });--}}
{{--                                list += '</ul>';--}}
{{--                                errorBox.html(list).show();--}}
{{--                            }--}}
{{--                            else {--}}
{{--                                const errorMessage = xhr.responseJSON?.myAlert || 'خطایی در ارتباط با سرور رخ داد.';--}}
{{--                                errorBox.html(`<ul><li>${errorMessage}</li></ul>`).show();--}}
{{--                            }--}}
{{--                        }--}}
{{--                    });--}}
{{--                });--}}
{{--        });--}}
{{--    </script>--}}

@endsection

@section('main')

    <section class="content">
        <div class="container-fluid">


            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <div>

                                <button type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#addCycleModal">
                                    افزودن دوره
                                </button>

                            </div>
                        </div>

                        <div class="body">
                            <x-responsive-display>
                                <x-slot:desktop>
                                    <div class="body table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>نام دوره</th>
                                        <th>تاریخ جوجه ریزی</th>
                                        <th>تعداد جوجه ریزی</th>
                                        <th>وضعیت</th>
                                        <th>جزییات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($breedingCycles as $k=>$breeding)
                                            <tr>
                                                <th scope="row">{{$k+1}}</th>
                                                <td>{{$breeding->name}}</td>
                                                <td>{{$breeding->start_date}}</td>
                                                <td>{{$breeding->chicken_count}}</td>
                                                <td class="{{
                                                        $breeding->status === 1 ?
                                                        "text-info" :
                                                        "text-success"
                                                    }}">

                                                    {{
                                                        $breeding->status === 1 ?
                                                        "فعال" :
                                                        "پایان یافته"
                                                    }}
                                                </td>
                                                <td>
                                                    <a href="{{route('breeding.show' , $breeding->id)}}" class="btn btn-outline-primary btn-border-radius">مشاهده</a>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                                </x-slot:desktop>

                                <x-slot:mobile>

                                    @foreach($breedingCycles as $k=>$breeding)
                                        <div class="card mb-3" x-data="{ open: false }">
                                            <div class="card-header d-flex justify-content-between align-items-center" @click="open = !open" style="cursor: pointer;">
                                                <strong> <span class="text-danger"> نام دوره :</span> {{  $breeding->name  }}</strong>
                                                -  <a href="{{route('breeding.show' , $breeding->id)}}" class="btn btn-outline-primary btn-border-radius">مشاهده</a>

                                                <span x-show="!open">▼</span>
                                                <span x-show="open">▲</span>
                                            </div>


                                            <div class="card-body" x-show="open" x-transition>

                                                <div class="bold text-danger mb-3">تاریخ جوجه ریزی:
                                                    <span class="text-dark" dir="ltr">{{$breeding->start_date }}</span>
                                                </div>

                                                <div class="bold text-danger mb-3">:تعداد جوجه ریزی
                                                    <span class="text-dark">{{ sep($breeding->chicken_count)}} هزار </span>
                                                </div>

                                                <div class="text-danger mb-3 "> وضعیت :
                                                    <span
                                                        class="{{
                                                        $breeding->status === 1 ?
                                                        "text-info" :
                                                        "text-success"
                                                    }}">

                                                    {{
                                                        $breeding->status === 1 ?
                                                        "فعال" :
                                                        "پایان یافته"
                                                    }}
                                                    </span>
                                                </div>

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

    <div class="modal fade" id="addCycleModal" tabindex="-1" aria-labelledby="addCycleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCycleModalLabel">فرم افزودن دوره جدید</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <x-validation-error/>


                    <form id="courseForm" method="post" action="{{route('breeding.add')}}">
                       @csrf

                        <div class="mb-3">
                            <label for="Name" class="form-label">نام پرورش:</label>
                            <input name="Name" type="text" class="form-control validate-required" id="Name" placeholder="مثلا: فروردین 1404" value="{{old('Name')}}"  data-error-message=" نام پرورش الزامی می باشد"  data-numeric="true">
                        </div>
                        <div class="mb-3">
                            <label for="Date" class="form-label">تاریخ جوجه‌ریزی:</label>
                            <input  name="Date" type="text" class="form-control validate-required" id="Date"  placeholder="مثلا:1404/01/01"  value="{{old('Date')}}"  data-error-message="تاریخ جوجه‌ریزی الزامی می باشد" data-numeric="true">
                        </div>
                        <div class="mb-3">
                            <label for="Count" class="form-label">تعداد جوجه:</label>
                            <input  name="Count" type="tel" class="form-control validate-required " id="Count" min="1" placeholder="مثلا: 150000" value="{{old('Count')}}"  data-error-message="تعداد جوجه الزامی می باشد" dir="rtl" data-numeric="true">
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                    <button type="submit" form="courseForm" class="btn btn-success"  data-validate="true">ذخیره دوره</button>
                </div>
            </div>
        </div>
    </div>


@endsection

