
@extends('layouts.app')

@section('title','درآمدهای متفرقه')

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
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#StoreMiscModal">
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
                                            <th>نوع</th>
                                            <th>تعداد/لیتر</th>
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
                                                <td>{{ $income->quantity }}</td>
                                                <td>{{ sep($income->price) }}</td>
                                                <td>{{ sep($income->quantity * $income->price) }}</td>
                                                <td>{{ $income->description }}</td>
                                                <td>
                                                    <button
                                                        class="btn tblActnBtn btn-edit"
                                                        data-id="{{ $income->id }}"
                                                        data-type_modal="income"
                                                        data-type="misc"
                                                        data-name="{{ $income->name }}"
                                                        data-quantity="{{ $income->quantity }}"
                                                        data-price="{{ $income->price }}"
                                                        data-description="{{ $income->description }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#UpdateMiscModal">
                                                        <i class="material-icons">mode_edit</i>
                                                    </button>

                                                    <button
                                                        class="btn tblActnBtn btn-delete"
                                                        data-id="{{ $income->id }}"
                                                        data-name="{{ $income->name}}"
                                                        data-type_modal="income"
                                                        data-bs-toggle="modal"
                                                        data-type="misc"
                                                        data-bs-target="#DeleteMiscModal">
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

                            <x-slot:mobile >
                                @forelse ($incomes as $income)
                                    <div class="card mb-3" x-data="{ open: false }">

                                        <div class="card-header d-flex justify-content-between align-items-center" @click="open = !open" style="cursor: pointer;">
                                            <strong><span class="text-danger"> نام  :</span> {{ $income->name }}</strong> - <strong>{{ sep($income->price)}} <span class="text-danger">تومان     </span> </strong>
                                            <span x-show="!open">▼</span>
                                            <span x-show="open">▲</span>
                                        </div>


                                        <div class="card-body" x-show="open" x-transition>

                                            <div class="bold text-danger mb-5">نام :
                                                <span class="text-dark">{{ $income->name }}</span>
                                            </div>

                                            <div class="bold text-danger mb-5"> تعداد/لیتر :
                                                <span class="text-dark">{{ $income->quantity }}</span>
                                            </div>

                                            <div class="bold text-danger mb-5"> قیمت واحد :
                                                <span class="text-dark">{{ sep($income->price) }} <small class="text-dark">(تومان)</small></span>
                                            </div>

                                            <div class="bold text-danger mb-5">قیمت کل :
                                                <span class="text-dark">{{ sep($income->quantity * $income->price) }} <small class="text-dark">(تومان)</small></span>
                                            </div>

                                            <div class="bold text-danger mb-5">توضیحات :
                                                <span class="text-dark">{{ $income->description }}</span>
                                            </div>


                                            <div class="bold text-danger mb-5">عملیات :
                                                <button
                                                    class="btn btn-sm btn-primary btn-edit"
                                                    data-id="{{ $income->id }}"
                                                    data-type_modal="income"
                                                    data-type="misc"
                                                    data-name="{{ $income->name }}"
                                                    data-quantity="{{ $income->quantity }}"
                                                    data-price="{{ $income->price }}"
                                                    data-description="{{ $income->description }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#UpdateMiscModal">ویرایش
                                                </button>

                                                <button
                                                    class="btn btn-sm btn-danger btn-delete"
                                                    data-id="{{ $income->id }}"
                                                    data-type_modal="income"
                                                    data-name="{{ $income->name}}"
                                                    data-type="miscIncome"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#DeleteMiscModal">حذف
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

            <div class="modal fade" id="StoreMiscModal" tabindex="-1" aria-labelledby="StoreMiscModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="StoreMiscModalLabel">افزودن </h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="StoreMiscForm" method="post" action="{{ route('income.store') }}">
                                @csrf
                                <input type="hidden" name="type" value="misc">
                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                                <input type="hidden" name="breeding_cycle_id" value="{{ $category->breeding_cycle_id }}">


                                <div class="mb-3">
                                    <label class="form-label">نوع جنس</label>
                                    <input type="text" name="name" class="form-control   validate-required" data-error=" فیلد نوع جنس الزامی است" placeholder="نوع جنس رو وارد کنید" >
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">تعداد</label>
                                    <input type="tel" name="quantity" class="form-control   validate-required" data-error="فیلد تعداد الزامی است" placeholder="تعداد رو وارد کنید" dir="rtl" >
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">قیمت واحد</label>
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

            <div class="modal fade  edit-income-modal" id="UpdateMiscModal" tabindex="-1" aria-labelledby="UpdateMiscModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="UpdateMiscModalLabel">ویرایش </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="UpdateMiscForm" method="post" >
                                @csrf

                                <input type="hidden" name="category_id" value="{{ $category->id  ?? '' }}">
                                <input type="hidden" name="breeding_cycle_id" value="{{ $category->breeding_cycle_id  ?? ''}}">
                                <input type="hidden" name="type" value="misc">
                                <input type="hidden" id="edit-misc-id" name="id">

                                <div class="mb-3">
                                    <label for="edit-misc-name" class="form-label">نام</label>
                                    <input type="text" name="name" id="edit-misc-name" class="form-control validate-required" data-error="فیلد نام الزامی است" value="{{$income->name ?? ''}}">
                                </div>
                                <div class="mb-3">
                                    <label for="edit-misc-quantity" class="form-label">تعداد</label>
                                    <input type="tel" name="quantity" id="edit-misc-quantity" class="form-control   validate-required" data-error="فیلد تعداد الزامی است" value="{{$income->quantity ?? ''}}" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label for="edit-misc-unit_price" class="form-label">قیمت واحد <small>(تومان)</small></label>
                                    <input type="tel" name="price" id="edit-misc-unit_price" class="form-control" value="{{$income->price ?? ''}}" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label for="edit-misc-description" class="form-label">توضیحات</label>
                                    <textarea name="description" id="edit-misc-description" class="form-control" rows="3">{{$income->descriprion ?? ''}}</textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                                    <button type="submit" class="btn btn-success" data-validate="true" >ذخیره</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="DeleteMiscModal" tabindex="-1" aria-labelledby="DeleteMiscModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="DeleteMiscModalLabel">حذف رکورد</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>آیا مطمئن هستید که می‌خواهید  <span id="DeleteName" class="text-danger"></span> <strong class="text-danger" id="delete-income-name"></strong> را حذف کنید؟</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                            <form id="DeleteMiscForm" method="post">
                                @csrf
                                <input type="hidden" name="type" value="misc">
                                <input type="hidden" id="delete-misc-id" name="id">
                                <button type="submit" class="btn btn-danger">بله، حذف شود</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

