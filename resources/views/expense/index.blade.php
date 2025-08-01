@extends('layouts.app')

@section('title','دوره ها')

@section('css')

@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('Invoice');
            const modalEl = document.getElementById('addInvoiceModal');

            const errorBox = document.createElement('div');
            errorBox.className = 'alert alert-danger';
            errorBox.style.display = 'none';
            form.prepend(errorBox);

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(form);
                errorBox.innerHTML = '';
                errorBox.style.display = 'none';

                fetch("{{route('Invoice.store')}}", {
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

                                <button type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#addInvoiceModal">
                                    افزودن صورتحساب
                                </button>

                            </div>
                        </div>

                        <div class="body">
                            <div class="body table-responsive">
                                @forelse($cycles as $cycle)

                                        <h5 class="mt-4 mb-3 text-primary">دوره: {{ $cycle->name }}</h5>
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th> نام دسته‌بندی </th>
                                                <th>دسته‌بندی </th>
                                                <th>جزییات</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($cycle->feedCategories as $category)
                                                <tr>
                                                    <td>{{ $category->name }}</td>
                                                    <td>دان</td>
                                                    <td>
                                                        <a href="{{ route('expense.category.show', ['type' => 'feed', 'category' => $category->id]) }}" class="btn btn-sm btn-outline-primary btn-border-radius">مشاهده</a>
                                                    </td>
                                                </tr>
                                            @endforeach


                                            @foreach($cycle->drugCategories as $category)
                                                <tr>
                                                    <td>{{ $category->name }}</td>
                                                    <td>داروخانه</td>
                                                    <td>
                                                        <a href="{{ route('expense.category.show', ['type' => 'drug', 'category' => $category->id]) }}" class="btn btn-sm btn-outline-primary btn-border-radius">مشاهده</a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            @foreach($cycle->miscellaneousCategories as $category)
                                                <tr>
                                                    <td>{{ $category->name }}</td>
                                                    <td>متفرقه</td>
                                                    <td>
                                                        <a href="{{ route('expense.category.show', ['type' => 'misc', 'category' => $category->id]) }}" class="btn btn-sm btn-outline-primary btn-border-radius">مشاهده</a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            @if($cycle->feedCategories->isEmpty() && $cycle->drugCategories->isEmpty() && $cycle->miscellaneousCategories->isEmpty())
                                                <tr>
                                                    <td colspan="3" class="text-center">هیچ صورتحساب ثبت نشده است.</td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>

                                @empty
                                    <p class="text-center">هیچ دوره‌ای یافت نشد.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="addInvoiceModal" tabindex="-1" aria-labelledby="addInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addInvoiceModalLabel">فرم افزودن دسته‌بندی</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <x-validation-error/>


                    <form id="Invoice" action="{{ route('Invoice.store') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="cycle" class="form-label">انتخاب دوره:</label>
                            <select name="breeding_cycle_id" id="cycle" style="display: block">
                                @forelse($cycles as $cycle)
                                    <option value="{{ $cycle->id }}">{{ $cycle->name }}</option>
                                @empty
                                    <option disabled selected>هیچ دوره‌ای موجود نیست</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label"> دسته‌بندی:</label>
                            <select name="expense_category" id="Expense_category" style="display: block">
                                <option value="feed">دان</option>
                                <option value="drug">داروخانه</option>
                                <option value="misc">متفرقه</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">نام دسته‌بندی:</label>
                            <input name="NameInvoice" type="text" class="form-control" id="name" placeholder="مثلاً: صورتحساب دان">
                        </div>
                    </form>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                    <button type="submit" form="Invoice" class="btn btn-success">ذخیره </button>
                </div>
            </div>
        </div>
    </div>


@endsection

