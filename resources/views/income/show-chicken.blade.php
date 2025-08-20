@extends('layouts.app')

@section('title',"درآمدهای فروش مرغ")

@section('js')
    <x-scriptExpenses/>

@endsection

@section('main')
    <section class="content">
        <div class="container-fluid">

            <div class="container my-5">
                <div class="d-flex flex-wrap align-items-center g-4">

                    <div class="col-lg-4 col-md-3">
                        <div class="card shadow-sm border-0 h-100 text-center">
                            <div class="card-body">
                                <h6 class="text-muted mb-4">{{ $summary['label'] }}</h6>
                                <h4 class="fw-bold text-primary">{{ sep($summary['value']) }}</h4>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">{{ $title }} <small></small></h4>
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#StoreChickenModal">
                                افزودن
                            </button>
                        </div>
                        <x-responsive-display>
                            <x-slot:desktop>
                                <div class="body table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>عنوان</th>
                                            <th>تعداد مرغ</th>
                                            <th>وزن<small>(کیلوگرم)</small></th>
                                            <th>قیمت واحد <small>(تومان)</small></th>
                                            <th>قیمت کل <small>(تومان)</small></th>
                                            <th>توضیحات</th>
                                            <th>عملیات</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @forelse($incomes as $income)
                                            <tr>

                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $income->name }}</td>
                                                <td>{{ sep($income->quantity) }}</td>
                                                <td>{{ $income->weight }}</td>
                                                <td>{{ sep($income->price) }}</td>
                                                <td>{{ sep($income->weight * $income->price) }}</td>
                                                <td>{{ $income->description }}</td>
                                                <td>
                                                    <button
                                                        class="btn tblActnBtn btn-edit"
                                                        data-id="{{ $income->id }}"
                                                        data-type_modal="income"
                                                        data-update_url="{{ route('income.update' ,$income->id)}}"
                                                        data-type="chicken"
                                                        data-name="{{ $income->name }}"
                                                        data-quantity="{{ $income->quantity }}"
                                                        data-weight="{{ $income->weight }}"
                                                        data-price="{{ $income->price }}"
                                                        data-description="{{ $income->description }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#UpdateChickenModal">
                                                        <i class="material-icons">mode_edit</i>
                                                    </button>

                                                    <button
                                                        class="btn tblActnBtn btn-delete"
                                                        data-id="{{ $income->id }}"
                                                        data-name="{{ $income->name}}"
                                                        data-delete_url="{{ route('income.destroy' ,$income->id)}}"
                                                        data-type_modal="income"
                                                        data-type="chicken"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#DeleteChickenModal">
                                                        <i class="material-icons">delete</i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">رکوردی وجود ندارد</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </x-slot:desktop>

                            <x-slot:mobile>
                                @forelse($incomes as $income)
                                    <div class="card mb-3" x-data="{ open: false }">

                                        <div class="card-header d-flex justify-content-between align-items-center" @click="open = !open" style="cursor: pointer;">
                                            <strong><span class="text-danger"> عنوان :</span> {{ $income->name }}</strong> - <strong>  <span class="text-danger">تعداد مرغ:</span>  {{ sep($income->quantity)}}</strong>
                                            <span x-show="!open">▼</span>
                                            <span x-show="open">▲</span>
                                        </div>


                                        <div class="card-body" x-show="open" x-transition>

                                            <div class="bold text-danger mb-5"> عنوان :
                                                <span class="text-dark">{{ $income->name }}</span>
                                            </div>

                                            <div class="bold text-danger mb-5"> تعداد مرغ:
                                                <span class="text-dark">{{ $income->quantity }}</span>
                                            </div>

                                            <div class="bold text-danger mb-5"> وزن :<small>(کیلوگرم)</small>
                                                <span class="text-dark">{{ $income->weight }}</span>
                                            </div>

                                            <div class="bold text-danger mb-5"> قیمت واحد :
                                                <span class="text-dark">{{ sep($income->price) }} <small class="text-dark">(تومان)</small></span>
                                            </div>

                                            <div class="bold text-danger mb-5">قیمت کل :
                                                <span class="text-dark">{{ sep($income->weight * $income->price) }} <small class="text-dark">(تومان)</small></span>
                                            </div>

                                            <div class="bold text-danger mb-5">توضیحات :
                                                <span class="text-dark">{{ $income->description }}</span>
                                            </div>

                                            <div class="bold text-danger mb-5">عملیات :
                                                <button
                                                    class="btn btn-sm btn-primary btn-edit"
                                                    data-id="{{ $income->id }}"
                                                    data-type_modal="income"
                                                    data-update_url="{{ route('income.update' ,$income->id)}}"
                                                    data-type="chicken"
                                                    data-name="{{ $income->name }}"
                                                    data-quantity="{{ $income->quantity }}"
                                                    data-weight="{{ $income->weight }}"
                                                    data-price="{{ $income->price }}"
                                                    data-description="{{ $income->description }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#UpdateChickenModal">ویرایش
                                                </button>

                                                <button
                                                    class="btn btn-sm btn-danger btn-delete"
                                                    data-id="{{ $income->id }}"
                                                    data-type_modal="income"
                                                    data-delete_url="{{ route('income.destroy' ,$income->id)}}"
                                                    data-name="{{ $income->name}}"
                                                    data-type="misc"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#DeleteChickenModal">حذف
                                                </button>

                                            </div>

                                        </div>
                                    </div>
                                @empty
                                    <div class="text-dark alert alert-warning text-center ">
                                        هیچ رکودی ثبت نشده است.
                                    </div>
                                @endforelse

                            </x-slot:mobile>

                        </x-responsive-display>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="StoreChickenModal" tabindex="-1" aria-labelledby="StoreChickenModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="StoreChickenModalLabel">افزودن </h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <form id="StoreChickenForm" method="post" action="{{ route('income.store') }}" >
                                @csrf
                                <input type="hidden" name="type" value="chicken">
                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                                <input type="hidden" name="breeding_cycle_id" value="{{ $category->breeding_cycle_id }}">


                                <div class="mb-3">
                                    <label class="form-label">عنوان: </label>
                                    <input name="name" class="form-control validate-required" data-error="فیلد عنوان الزامی است" placeholder="نام خریدار وارد کنید">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">تعداد</label>
                                    <input type="tel" name="quantity" class="form-control  validate-required" data-error="فیلد تعداد الزامی است" placeholder="تعداد رو وارد کنید" dir="rtl">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">وزن <small>(کیلوگرم)</small></label>
                                    <input type="number" name="weight"  class="form-control validate-required" placeholder="وزن را وارد کنید " step="0.01" inputmode="decimal" dir="ltr" style="text-align: right;">
                                </div>


                                <div class="mb-3">
                                    <label class="form-label">قیمت  <small>(تومان)</small></label>
                                    <input type="tel" name="price" class="form-control"  placeholder="قیمت رو وارد کنید" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">توضیحات</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                                    <button type="submit" class="btn btn-success" data-validate="true">ذخیره</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade  edit-modal" id="UpdateChickenModal" tabindex="-1" aria-labelledby="UpdateChickenModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="UpdateChickenModalLabel">ویرایش</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="UpdateChickenForm" method="post" >
                                @csrf
                                <input type="hidden" name="category_id" value="{{ $category->id  ?? '-' }}">
                                <input type="hidden" name="breeding_cycle_id" value="{{ $category->breeding_cycle_id  ?? '-'}}">
                                <input type="hidden" name="type" value="chicken">
                                <input type="hidden" id="edit-chicken-id" name="id">

                                <div class="mb-3">
                                    <label for="edit-chicken-name" class="form-label">عنوان :</label>
                                    <input name="name" class="form-control validate-required" data-error="عنوان فیلد الزامی است" placeholder="نام خریدار وارد کنید">
                                </div>

                                <div class="mb-3">
                                    <label for="edit-chicken-quantity" class="form-label">تعداد</label>
                                    <input type="tel" name="quantity" id="edit-chicken-quantity" class="form-control   validate-required" data-error="فید وزن الزامی است" placeholder="تعداد رو وارد کنید"  dir="rtl">
                                </div>

                                <div class="mb-3">
                                    <label for="edit-chicken-weight" class="form-label">وزن <small>(کیلوگرم)</small></label>
                                    <input type="number" name="weight" id="edit-chicken-weight" class="form-control validate-required" placeholder="وزن را وارد کنید " step="0.01" inputmode="decimal" dir="ltr" style="text-align: right;">
                                </div>

                                <div class="mb-3">
                                    <label for="edit-chicken-price" class="form-label">قیمت واحد <small>(تومان)</small></label>
                                    <input type="tel" name="price" id="edit-chicken-price" class="form-control" placeholder="قیمت رو وارد کنید" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label for="edit-chicken-description" class="form-label">توضیحات</label>
                                    <textarea name="description" id="edit-chicken-description" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                                    <button type="submit" class="btn btn-success" data-validate="true">ذخیره</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="DeleteChickenModal" tabindex="-1" aria-labelledby="DeleteChickenModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="DeleteChickenModalLabel">حذف رکورد</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>آیا مطمئن هستید که می‌خواهید
                                <span id="DeleteName" class="text-danger"></span>
                                <strong class="text-danger" id="delete-income-name"></strong> را حذف کنید؟</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                            <form id="DeleteChickenForm" method="post">
                                @csrf
                                <input type="hidden" name="type" value="chicken">
                                <input type="hidden" id="chicken-id" name="id">
                                <button type="submit" class="btn btn-danger">بله، حذف شود</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

