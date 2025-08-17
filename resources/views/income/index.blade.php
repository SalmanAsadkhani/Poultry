@extends('layouts.app')

@section('title','درآمدها')

@section('js')
    <x-invoices/>
@endsection

@section('main')

    <section class="content">
        <div class="container-fluid">


            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <div>

                                <button type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#StoreInvoiceModal">
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
                                            <th>نام دسته‌بندی</th>
                                            <th>دسته‌بندی اصلی</th>
                                            <th>جزییات</th>
                                            <th>عملیات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($cycle->chickenIncomeCategories as $category)
                                            <tr>
                                                <td>{{ $category->name }}</td>
                                                <td>فروش مرغ</td>
                                                <td>
                                                    <a href="{{ route('income.category.show', ['type' => 'chicken', 'category' => $category->id]) }}" class="btn btn-sm btn-outline-primary btn-border-radius">مشاهده</a>
                                                </td>
                                                <td>

                                                    <button
                                                        class="btn tblActnBtn btn-edit"
                                                        data-bs-toggle="modal"
                                                        data-type_modal='income'
                                                        data-bs-target="#UpdateInvoiceModal"
                                                        data-update_url="{{ route('Invoice.income.update', $category->id) }}"
                                                        data-name="{{ $category->name }}"
                                                        data-breeding_cycle_id="{{ $cycle->id }}"
                                                        data-category_type="chicken">
                                                        <i class="material-icons">mode_edit</i>
                                                    </button>

                                                    @if ($category->chickens->count() == 0)
                                                        <button
                                                            class="btn tblActnBtn btn-delete"
                                                            data-bs-toggle="modal"
                                                            data-type_modal='income'
                                                            data-category_type="chicken"
                                                            data-bs-target="#DeleteInvoiceModal"
                                                            data-delete_url="{{ route('Invoice.income.destroy', $category->id) }}"
                                                            data-name="{{ $category->name }}">
                                                            <i class="material-icons">delete</i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                        @foreach($cycle->miscIncomeCategories as $category)
                                            <tr>
                                                <td>{{ $category->name }}</td>
                                                <td>متفرقه</td>
                                                <td>
                                                    <a href="{{ route('income.category.show', ['type' => 'misc', 'category' => $category->id]) }}" class="btn btn-sm btn-outline-primary btn-border-radius">مشاهده</a>
                                                </td>
                                                <td>
                                                    <button
                                                        class="btn tblActnBtn btn-edit"
                                                        data-bs-toggle="modal"
                                                        data-type_modal='income'
                                                        data-bs-target="#UpdateInvoiceModal"
                                                        data-update_url="{{ route('Invoice.income.update', $category->id) }}"
                                                        data-name="{{ $category->name }}"
                                                        data-breeding_cycle_id="{{ $cycle->id }}"
                                                        data-category_type="misc">
                                                        <i class="material-icons">mode_edit</i>
                                                    </button>

                                                    @if ($category->miscellaneous_incomes->count() == 0)
                                                        <button
                                                            class="btn tblActnBtn btn-delete"
                                                            data-bs-toggle="modal"
                                                            data-category_type="misc"
                                                            data-type_modal='income'
                                                            data-bs-target="#DeleteInvoiceModal"
                                                            data-delete_url="{{ route('Invoice.income.destroy', $category->id) }}"
                                                            data-name="{{ $category->name }}">
                                                            <i class="material-icons">delete</i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                        @if($cycle->chickenIncomeCategories->isEmpty() && $cycle->miscIncomeCategories->isEmpty())
                                            <tr>
                                                <td colspan="4" class="text-center">هیچ صورتحسابی ثبت نشده است.</td>
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

    <div class="modal fade" id="StoreInvoiceModal" tabindex="-1" aria-labelledby="StoreInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="StoreInvoiceModalLabel">فرم افزودن دسته‌بندی</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="Invoice" action="{{ route('Invoice.income.store') }}" method="post" novalidate="novalidate">
                    <div class="modal-body">
                        @csrf
                        <x-validation-error/>

                        <div class="mb-3">
                            <label for="create-cycle" class="form-label">انتخاب دوره:</label>
                            <select name="breeding_cycle_id" id="create-cycle" style="display: block">
                                @forelse($cycles as $cycle)
                                    <option value="{{ $cycle->id }}">{{ $cycle->name }}</option>
                                @empty
                                    <option disabled selected>هیچ دوره‌ای موجود نیست</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="create-income_category" class="form-label"> دسته‌بندی:</label>
                            <select name="income_category" id="create-income_category" style="display: block">
                                <option value="chicken">فروش مرغ</option>
                                <option value="misc">متفرقه</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="create-name" class="form-label">نام دسته‌بندی:</label>
                            <input name="NameInvoice" type="text" class="form-control validate-required" id="create-name" placeholder="مثلاً: صورتحساب فروش مرغ"  data-error-message="نام صورتحساب الزامی است " data-numeric="true">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-success" data-validate="true">ذخیره </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="UpdateInvoiceModal" tabindex="-1" aria-labelledby="UpdateInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="UpdateInvoiceModalLabel">فرم ویرایش دسته‌بندی</h5>
                    <button type="button" class="close" data-bs-dismiss-modal aria-label="Close"></button>
                </div>

                <form id="UpdateInvoiceForm" method="post" novalidate="novalidate">
                    <div class="modal-body">
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
                            <select name="income_category" id="Income_category" style="display: block">
                                <option value='chicken'>فروش مرغ</option>
                                <option value='misc' >متفرقه</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">نام دسته‌بندی:</label>
                            <input name="name" type="text" class="form-control validate-required" id="name" placeholder="مثلاً: صورتحساب فروش مرغ"  data-error-message="نام صورتحساب الزامی است " data-numeric="true">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-success" data-validate="true">ذخیره تغییرات</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="DeleteInvoiceModal" tabindex="-1" aria-labelledby="DeleteInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="DeleteInvoiceModalLabel">تایید حذف</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>آیا از حذف دسته‌بندی <strong id="categoryNameToDelete"></strong> مطمئن هستید؟</p>
                </div>
                <div class="modal-footer">
                    <form id="delete-invoice-form" method="POST">
                        @csrf
                        <input type="hidden" name="category_type" value="category_type">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                        <button type="submit" class="btn btn-danger">بله، حذف کن</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

