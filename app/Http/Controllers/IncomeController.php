<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIncomeRequest;
use App\Http\Requests\StoreInvoiceIncomeRequest;
use App\Http\Requests\UpdateIncomeRequest;
use App\Models\BreedingCycle;
use App\Models\ChickenSales;
use App\Models\ChickenSalesCategory;
use App\Models\MiscellaneousIncome;
use App\Models\MiscellaneousIncomeCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class IncomeController extends Controller
{
    public function index() : View
    {

        $cycles = BreedingCycle::with([
            'chickenIncomeCategories',
            'chickenIncomeCategories.chickens',
            'miscIncomeCategories',
            'miscIncomeCategories.miscellaneous_incomes',
        ])->where('user_id', auth()->id())->get();

        return view('income.index' , [
            'cycles' => $cycles,
        ]);
    }

    public function invoice(StoreInvoiceIncomeRequest $request ): JsonResponse
    {
        $model = match ($request['income_category']) {
            'chicken' => ChickenSalesCategory::class,
            'misc' => MiscellaneousIncomeCategory::class,
        };


        $model::create([
            'breeding_cycle_id' => $request->breeding_cycle_id ,
            'name' => $request->NameInvoice,
        ]);


        return response()->json(['res' => 10 , 'mySuccess' => "{$request->NameInvoice} با موفقیت اضافه گردید"]);

    }

    public function invoice_update(Request $request , $id ): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'breeding_cycle_id' => 'required|exists:breeding_cycles,id',
            'income_category' => 'required|in:chicken,misc',
        ]);

        $model = match ($request['income_category']) {
            'chicken' => ChickenSalesCategory::class,
            'misc' => MiscellaneousIncomeCategory::class,
        };

        $expense = $model::findOrFail($id);

        $expense->update([
            'breeding_cycle_id' => $request->breeding_cycle_id ,
            'name' => $request->name,
        ]);


        return response()->json([
                'res' => 10,
                'mySuccess' => 'دسته‌بندی با موفقیت ویرایش شد'
        ]);
    }

    public function invoice_destroy(Request $request , $id): JsonResponse
    {
        $request->validate([
            'category_type' => 'required|in:chicken,misc',
        ]);

        $model = match ($request['income_category']) {
            'chicken' => ChickenSalesCategory::class,
            'misc' => MiscellaneousIncomeCategory::class,
        };


        $expense = $model::findOrFail($id);

        $expense->delete();

        return response()->json([
            'res' => 10,
            'mySuccess' =>  'دسته‌بندی با موفقیت حذف شد.'
        ]);
    }

    public function show(string $type, int $categoryId) : View
    {
        [$categoryModel, $relation, $viewName, $titlePrefix] = match ($type) {
            'chicken' => [ChickenSalesCategory::class,'chickens', 'income.show-chicken', 'صورتحساب درآمدهای فروش مرغ'],
            'misc' => [MiscellaneousIncomeCategory::class, 'miscellaneous_income', 'income.show-misc', 'صورتحساب درآمدهای متفرقه'],

        };


        $category = $categoryModel::with($relation)->findOrFail($categoryId);

        $Value = 0;
        $Label = '';

        switch ($type) {
            case 'chicken':

                $Value = $category->$relation()->sum('quantity');
                $Label = 'مجموع فروش کل مرغ';
                break;
            case 'misc':

                $Value = $category->$relation()->sum(DB::raw('quantity * price'));
                $Label = 'جمع کل  (تومان)';
                break;
        }


        return view($viewName, [
            'category' => $category,
            'incomes' => $category->$relation,
            'title'    => $titlePrefix,
            'summary'  => [
                'value' => $Value,
                'label' => $Label
            ]
        ]);
    }

    public function store(StoreIncomeRequest $request) : JsonResponse
    {
        [$model, $categoryIdKey] = match ($request->type) {
            'chicken' => [ChickenSales::class , 'chicken_categories_id'],
            'misc' => [MiscellaneousIncome::class ,'misc_categories_id' ],
        };


        $data = [
            'breeding_cycle_id' => $request->breeding_cycle_id,
            $categoryIdKey      => $request->category_id,
            'name'              => $request->name,
            'quantity'          => $request->quantity,
            'price'             => $request->price,
            'description'       => $request->description,
        ];

        if ($request->type == 'chicken') {
            $data['weight'] = $request->weight;
        }


        $model::create($data);

        return response()->json([
            'res' => 10,
            'mySuccess' => "  {$request->name} با موفقیت اضافه گردید"
        ]);
    }

    public function update(UpdateIncomeRequest $request, $id) : JsonResponse
    {
        [ $model , $categoryIdKey ] = match ($request->type) {
            'chicken' => [ChickenSales::class , 'chicken_categories_id'],
            'misc' => [MiscellaneousIncome::class ,'misc_categories_id' ],
        };

        $income = $model::findOrFail($request->id);

        $data = [
            'breeding_cycle_id' => $request->breeding_cycle_id,
            $categoryIdKey      => $request->category_id,
            'name'=> $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'description' => $request->description,
        ];

        if ($request->type == 'chicken') {
            $data['weight'] = $request->weight;
        }

        $income->update($data);

        return response()->json([
            'res' => 10,
            'mySuccess' => "  {$request->name} با موفقیت ویرایش گردید"
        ]);
    }

    public function destroy(Request $request, $id) : JsonResponse
    {

        $request->validate(['type' => 'required|string|in:chicken,misc']);

        $model = match ($request->type) {
            'chicken' => ChickenSales::class,
            'misc' => MiscellaneousIncome::class,
        };


        $income = $model::findOrFail($id);

        $income->delete();

        return response()->json([
            'res' => 10,
            'mySuccess' => "  {$income->name} با موفقیت حذف گردید"
        ]);
    }
}
