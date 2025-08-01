@extends('layouts.app')

@section('title','هزینه‌ها')

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('#StoreExpenseForm');
            const modalEl = document.getElementById('StoreExpenseModal');

            const errorBox = document.createElement('div');
            errorBox.className = 'alert alert-danger';
            errorBox.style.display = 'none';
            form.prepend(errorBox);

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(form);
                errorBox.innerHTML = '';
                errorBox.style.display = 'none';

                fetch("{{ route('expenses.store') }}", {
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
                            toastr.success( result.mySuccess);
                            setTimeout(()=>{
                                location.reload();
                            } , 1500)

                            bootstrap.Modal.getInstance(modalEl).hide();
                            form.reset();
                        } else {
                            errorBox.innerHTML = `<ul><li>${result.myAlert}</li></ul>`;
                            errorBox.style.display = 'block';
                        }
                    });
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.getElementById('edit-expense-id').value = this.dataset.id;
                    document.getElementById('edit-name').value = this.dataset.name;
                    document.getElementById('edit-breeding_cycle_id').value = this.dataset.breeding_cycle_id;
                    document.getElementById('edit-expense_category_id').value = this.dataset.expense_category_id;
                    document.getElementById('edit-quantity').value = this.dataset.quantity;
                    document.getElementById('edit-unit_price').value = this.dataset.unit_price;
                    document.getElementById('edit-description').value = this.dataset.description;
                });
            });

            const form = document.getElementById('UpdateExpenseForm');
            const modalEl = document.getElementById('UpdateExpenseModal');
            const errorBox = document.createElement('div');
            errorBox.className = 'alert alert-danger';
            errorBox.style.display = 'none';
            form.prepend(errorBox);

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const id = document.getElementById('edit-expense-id').value;
                const formData = new FormData(form);
                errorBox.innerHTML = '';
                errorBox.style.display = 'none';

                fetch(`{{ route('expenses.update', ':id') }}`.replace(':id', id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
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
                            toastr.success(result.mySuccess);
                            setTimeout(() => location.reload(), 1500);
                            bootstrap.Modal.getInstance(modalEl).hide();
                            form.reset();
                        } else {
                            errorBox.innerHTML = `<ul><li>${result.myAlert}</li></ul>`;
                            errorBox.style.display = 'block';
                        }
                    });
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const deleteForm = document.getElementById('DeleteExpenseForm');
            const modalEl = document.getElementById('DeleteExpenseModal');
            const errorBox = document.createElement('div');
            errorBox.className = 'alert alert-danger';
            errorBox.style.display = 'none';
            deleteForm.prepend(errorBox);

            // دکمه‌های حذف جدول
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const name = this.dataset.name;

                    document.getElementById('delete-expense-id').value = id;
                    document.getElementById('delete-expense-name').innerText = name;
                });
            });

            deleteForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const id = document.getElementById('delete-expense-id').value;
                const formData = new FormData(deleteForm);
                errorBox.innerHTML = '';
                errorBox.style.display = 'none';

                fetch(`{{ route('expenses.delete', ':id') }}`.replace(':id', id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
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
                            toastr.success(result.mySuccess);
                            setTimeout(() => location.reload(), 1500);
                            bootstrap.Modal.getInstance(modalEl).hide();
                            deleteForm.reset();
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
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">هزینه‌های دسته: {{ $expenseCate->name }}</h4>
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#StoreExpenseModal">
                                افزودن
                            </button>
                        </div>
                        <div class="body table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>عنوان</th>
                                    <th>تعداد</th>
                                    <th>قیمت واحد <small>(تومان)</small></th>
                                    <th> قیمت کل <small>(تومان)</small></th>
                                    <th>توضیحات</th>
                                    <th>جزییات</th>

                                </tr>
                                </thead>
                                <tbody>

                                @foreach($expenseCate->expenses as $expense)
                                    <tr>

                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $expense->name }}</td>
                                        <td>{{ $expense->quantity }}</td>
                                        <td>{{ sep($expense->unit_price) }}</td>
                                        <td>{{ sep($expense->total_price) }}</td>
                                        <td>{{ $expense->description }}</td>
                                        <td>

                                            <button
                                                class="btn tblActnBtn btn-edit"
                                                data-id="{{ $expense->id }}"
                                                data-name="{{ $expense->name }}"
                                                data-breeding_cycle_id="{{ $expense->breeding_cycle_id }}"
                                                data-expense_category_id="{{ $expense->expense_category_id }}"
                                                data-quantity="{{ $expense->quantity }}"
                                                data-unit_price="{{ $expense->unit_price }}"
                                                data-description="{{ $expense->description }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#UpdateExpenseModal">
                                                <i class="material-icons">mode_edit</i>
                                            </button>



                                            <button
                                                class="btn tblActnBtn btn-delete"
                                                data-id="{{ $expense->id }}"
                                                data-name="{{ $expense->name }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#DeleteExpenseModal">
                                                <i class="material-icons">delete</i>
                                            </button>

                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Modal -->
            <div class="modal fade" id="StoreExpenseModal" tabindex="-1" aria-labelledby="StoreExpenseModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="StoreExpenseModalLabel">افزودن </h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="StoreExpenseForm" method="post">
                                @csrf
                                <input type="hidden" name="breeding_cycle_id" value="{{ $expenseCate->breeding_cycle_id }}">
                                <input type="hidden" name="expense_category_id" value="{{ $expenseCate->id }}">


                                <div class="mb-3">
                                    <label class="form-label">نام </label>
                                    <input type="text" name="name" class="form-control" >
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">تعداد</label>
                                    <input type="number" name="quantity" class="form-control" >
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">قیمت واحد</label>
                                    <input type="number" name="unit_price" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">توضیحات</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                                    <button type="submit" class="btn btn-success">ذخیره</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="UpdateExpenseModal" tabindex="-1" aria-labelledby="UpdateExpenseModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="UpdateExpenseModalLabel">ویرایش هزینه</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="UpdateExpenseForm" method="post">
                                @csrf
                                <input type="hidden" name="breeding_cycle_id" id="edit-breeding_cycle_id">
                                <input type="hidden" name="expense_category_id" id="edit-expense_category_id">
                                <input type="hidden" name="expense_id" id="edit-expense-id">

                                <div class="mb-3">
                                    <label for="edit-name" class="form-label">نام</label>
                                    <input type="text" name="name" id="edit-name" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="edit-quantity" class="form-label">تعداد</label>
                                    <input type="number" name="quantity" id="edit-quantity" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="edit-unit_price" class="form-label">قیمت واحد</label>
                                    <input type="number" name="unit_price" id="edit-unit_price" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="edit-description" class="form-label">توضیحات</label>
                                    <textarea name="description" id="edit-description" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                                    <button type="submit" class="btn btn-success">ذخیره</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="DeleteExpenseModal" tabindex="-1" aria-labelledby="DeleteExpenseModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="DeleteExpenseModalLabel">حذف رکورد</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>آیا مطمئن هستید که می‌خواهید رکورد <strong class="text-danger" id="delete-expense-name"></strong> را حذف کنید؟</p>
                            <div class="text-danger myAlert"></div>
                            <div class="text-success mySuccess"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                            <form id="DeleteExpenseForm" method="post">
                                @csrf
                                <input type="hidden" name="expense_id" id="delete-expense-id">
                                <button type="submit" class="btn btn-danger">بله، حذف شود</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>
@endsection
