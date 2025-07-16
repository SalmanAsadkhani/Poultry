@extends('layouts.app')

@section('title','دوره ها')

@section('css')

@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('courseForm');
            const modalEl = document.getElementById('addCycleModal');

            const errorBox = document.createElement('div');
            errorBox.className = 'alert alert-danger';
            errorBox.style.display = 'none';
            form.prepend(errorBox);

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(form);
                errorBox.innerHTML = '';
                errorBox.style.display = 'none';

                fetch("{{ route('breeding.add') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name=\"_token\"]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                }).then(response => {
                    if (response.status === 422) {
                        return response.json().then(data => {
                            let list = '<ul>';
                            Object.values(data.errors).forEach(errs =>
                                errs.forEach(err => list += `<li>${err}</li>`)
                            );
                            list += '</ul>';
                            errorBox.innerHTML = list;
                            errorBox.style.display = 'block';

                            return;
                        });
                    }

                    return response.json().then(result => {
                        if (result.res === 10) {

                            bootstrap.Modal.getInstance(modalEl).hide();
                            form.reset();
                            location.reload();
                        } else {
                            errorBox.innerHTML = `<ul><li>${result.myAlert}</li></ul>`;
                            errorBox.style.display = 'block';
                        }
                    });
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
                        <div class="header">
                            <div>

                                <button type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#addCycleModal">
                                    افزودن دوره
                                </button>

                            </div>
                        </div>

                        <div class="body">
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
                            <input name="Name" type="text" class="form-control" id="Name" placeholder="مثلا: فروردین 1404" value="{{old('Name')}}" >
                        </div>
                        <div class="mb-3">
                            <label for="Date" class="form-label">تاریخ جوجه‌ریزی:</label>
                            <input  name="Date" type="text" class="form-control" id="Date"  placeholder="مثلا:1404/01/01"  value="{{old('Date')}}">
                        </div>
                        <div class="mb-3">
                            <label for="Count" class="form-label">تعداد جوجه:</label>
                            <input  name="Count" type="number" class="form-control " id="Count" min="1" placeholder="مثلا: 150000" value="{{old('Count')}}">
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                    <button type="submit" form="courseForm" class="btn btn-success">ذخیره دوره</button>
                </div>
            </div>
        </div>
    </div>


@endsection

