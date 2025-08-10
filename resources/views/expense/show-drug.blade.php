@extends('layouts.app')

@section('title','هزینه‌ها')

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
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#StoreDrugModal">
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
                                    <th>نام دارو</th>
                                    <th>تعداد</th>
                                    <th>قیمت واحد <small>(تومان)</small></th>
                                    <th>قیمت کل <small>(تومان)</small></th>
                                    <th>توضیحات</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody>

                                @forelse($expenses as $expense)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $expense->name }}</td>
                                        <td>{{ $expense->quantity }}</td>
                                        <td>{{ sep($expense->price) }}</td>
                                        <td>{{ sep($expense->quantity * $expense->price) }}</td>
                                        <td>{{ $expense->description }}</td>
                                        <td>
                                            <button
                                                class="btn tblActnBtn btn-edit"
                                                data-id="{{ $expense->id }}"
                                                data-type="drug"
                                                data-name="{{ $expense->name }}"
                                                data-quantity="{{ $expense->quantity }}"
                                                data-price="{{ $expense->price }}"
                                                data-description="{{ $expense->description }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#UpdateDrugModal">
                                                <i class="material-icons">mode_edit</i>
                                            </button>

                                            <button
                                                class="btn tblActnBtn btn-delete"
                                                data-id="{{ $expense->id }}"
                                                data-name="{{ $expense->name}}"
                                                data-type="misc"
                                                data-bs-toggle="modal"
                                                data-bs-target="#DeleteDrugModal">
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
                                @forelse($expenses as $expense)
                                    <div class="card mb-3" x-data="{ open: false }">

                                        <div class="card-header d-flex justify-content-between align-items-center" @click="open = !open" style="cursor: pointer;">
                                            <strong><span class="text-danger"> نام دارو :</span> {{ $expense->name }}</strong> - <strong>{{ sep($expense->price)}} <span class="text-danger">تومان     </span> </strong>
                                            <span x-show="!open">▼</span>
                                            <span x-show="open">▲</span>
                                        </div>


                                        <div class="card-body" x-show="open" x-transition>

                                            <div class="bold text-danger mb-5">نام دارو:
                                                <span class="text-dark">{{ $expense->name }}</span>
                                            </div>

                                            <div class="bold text-danger mb-5"> تعداد :
                                                <span class="text-dark">{{ $expense->quantity }}</span>
                                            </div>

                                            <div class="bold text-danger mb-5"> قیمت واحد :
                                                <span class="text-dark">{{ sep($expense->price) }} <small class="text-dark">(تومان)</small></span>
                                            </div>

                                            <div class="bold text-danger mb-5">قیمت کل :
                                                <span class="text-dark">{{ sep($expense->quantity * $expense->price) }} <small class="text-dark">(تومان)</small></span>
                                            </div>

                                            <div class="bold text-danger mb-5">توضیحات :
                                                <span class="text-dark">{{ $expense->description }}</span>
                                            </div>


                                             <div class="bold text-danger mb-5">عملیات :
                                                 <button
                                                     class="btn btn-sm btn-primary btn-edit"
                                                     data-id="{{ $expense->id }}"
                                                     data-type="drug"
                                                     data-name="{{ $expense->name }}"
                                                     data-quantity="{{ $expense->quantity }}"
                                                     data-price="{{ $expense->price }}"
                                                     data-description="{{ $expense->description }}"
                                                     data-bs-toggle="modal"
                                                     data-bs-target="#UpdateDrugModal">ویرایش
                                                 </button>

                                                 <button
                                                     class="btn btn-sm btn-danger btn-delete"
                                                     data-id="{{ $expense->id }}"
                                                     data-name="{{ $expense->name}}"
                                                     data-type="misc"
                                                     data-bs-toggle="modal"
                                                     data-bs-target="#DeleteDrugModal">حذف
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

            <div class="modal fade" id="StoreDrugModal" tabindex="-1" aria-labelledby="StoreDrugModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="StoreDrugModalLabel">افزودن </h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="StoreDrugForm" method="post" action="{{ route('expenses.store') }}">
                                @csrf
                                <input type="hidden" name="type" value="drug">
                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                                <input type="hidden" name="breeding_cycle_id" value="{{ $category->breeding_cycle_id }}">

                                <div class="mb-3">
                                    <label class="form-label">نام دارو </label>
                                    <input type="text" name="name" class="form-control  validate-required" data-error="نام دارو الزامی است"   placeholder="مثلا: انرژی پلاس" >
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">تعداد</label>
                                    <input type="tel" name="quantity" class="form-control  validate-required" data-error=" تعداد دارو الزامی است"  placeholder="مثلا: 2"  dir="rtl" >
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">قیمت واحد  <small>(تومان)</small> </label>
                                    <input type="tel" name="unit_price" class="form-control"  placeholder="مثلا: 1,000,000"  dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">توضیحات</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                                    <button type="submit" class="btn btn-success"  data-validate="true">ذخیره</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade  edit-expense-modal" id="UpdateDrugModal" tabindex="-1" aria-labelledby="UpdateDrugModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="UpdateDrugModalLabel">ویرایش </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="UpdateDrugForm" method="post">
                                @csrf

                                <input type="hidden" name="category_id" value="{{ $category->id  ?? '' }}">
                                <input type="hidden" name="breeding_cycle_id" value="{{ $category->breeding_cycle_id  ?? ''}}">
                                <input type="hidden" name="type" value="drug">
                                <input type="hidden" id="edit-drug-id" name="id">

                                <div class="mb-3">
                                    <label for="edit-drug-name" class="form-label">نام دارو</label>
                                    <input type="text" name="name" id="edit-drug-name" class="form-control validate-required" data-error="نام دارو الزامی است">
                                </div>
                                <div class="mb-3">
                                    <label for="edit-drug-quantity" class="form-label">تعداد</label>
                                    <input type="tel" name="quantity" id="edit-drug-quantity" class="form-control validate-required" data-error=" فیلد تعداد الزامی است" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label for="edit-drug-price" class="form-label">قیمت واحد<small>(تومان)</small></label>
                                    <input type="tel" name="unit_price" id="edit-drug-price" class="form-control" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label for="edit-drug-description" class="form-label">توضیحات</label>
                                    <textarea name="description" id="edit-drug-description" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                                    <button type="submit" class="btn btn-success"  data-validate="true">ذخیره</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="DeleteDrugModal" tabindex="-1" aria-labelledby="DeleteDrugModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="DeleteDrugModalLabel">حذف رکورد</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>آیا مطمئن هستید که می‌خواهید  <span class="text-danger"> {{$expense->name ?? '-'}}</span> <strong class="text-danger" id="delete-expense-name"></strong> را حذف کنید؟</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                            <form id="DeleteDrugForm" method="post">
                                @csrf
                                <input type="hidden" name="type" value="drug">
                                <input type="hidden" id="delete-drug-id" name="id">

                                <button type="submit" class="btn btn-danger">بله، حذف شود</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

