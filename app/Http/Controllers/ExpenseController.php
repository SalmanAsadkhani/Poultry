<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Requests\StoreExpenseRequest;
use App\Models\BreedingCycle;
use App\Models\Drug;
use App\Models\DrugCategory;
use App\Models\Feed;
use App\Models\FeedCategory;
use App\Models\Miscellaneous;
use App\Models\MiscellaneousCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index() : View
    {

        $cycles = BreedingCycle::with([
            'feedCategories',
            'drugCategories',
            'miscellaneousCategories'
        ])->where('user_id', auth()->id())->get();

        return view('expense.index' , compact('cycles'));
    }

    public function Invoice(StoreInvoiceRequest $request ): JsonResponse
    {

        $model = match ($request['expense_category']) {
            'feed' => FeedCategory::class,
            'drug' => DrugCategory::class,
            'misc' => MiscellaneousCategory::class,
        };


        $model::create([
            'breeding_cycle_id' => $request->breeding_cycle_id ,
            'name' => $request->NameInvoice,
        ]);


        return response()->json(['res' => 10 , 'mySuccess' => "{$request->NameInvoice} با موفقیت اضافه گردید"]);

    }

    public function showCategory(string $type, int $categoryId) : View
    {

        [$categoryModel, $relation, $viewName, $titlePrefix] = match ($type) {
            'feed' => [FeedCategory::class, 'feeds', 'expense.show-feed', 'صورتحساب دان'],
            'drug' => [DrugCategory::class, 'drugs', 'expense.show-drug', 'صورتحساب  داروخانه'],
            'misc' => [MiscellaneousCategory::class, 'miscellaneous', 'expense.show-misc', 'صورتحساب  متفرقه'],

        };

        $category = $categoryModel::with($relation)->findOrFail($categoryId);


        $Value = 0;
        $Label = '';

        switch ($type) {
            case 'feed':

                $Value = $category->$relation()->sum('quantity');
                $Label = 'مجموع دان مصرفی (کیلوگرم)';
                break;
            case 'drug':
            case 'misc':

                $Value = $category->$relation()->sum(DB::raw('quantity * price'));
                $Label = 'جمع کل هزینه‌ها (تومان)';
                break;
        }


        return view($viewName, [
            'category' => $category,
            'expenses' => $category->$relation,
            'title'    => $titlePrefix,
            'summary'  => [
                'value' => $Value,
                'label' => $Label
            ]
        ]);
    }

    public function store(StoreExpenseRequest $request) : JsonResponse
    {

        [$model, $categoryIdKey] = match ($request->type) {
            'feed' => [Feed::class, 'feed_category_id'],
            'drug' => [Drug::class, 'drug_category_id'],
            'misc' => [Miscellaneous::class, 'miscellaneous_category_id'],
        };


        $data = [
            'breeding_cycle_id' => $request->breeding_cycle_id,
            $categoryIdKey      => $request->category_id,
            'name'              => $request->name,
            'quantity'          => $request->quantity,
            'price'             => $request->unit_price,
            'description'       => $request->description,
        ];

        if ($request->type == 'feed') {
            $data['bag_count'] = $request->bag_count;
        }


        $model::create($data);

        return response()->json([
            'res' => 10,
            'mySuccess' => "  {$request->name} با موفقیت اضافه گردید"
        ]);
    }

    public function update(UpdateExpenseRequest $request, $id) : JsonResponse
    {


       [ $model , $categoryIdKey ] = match ($request->type) {
            'feed' => [Feed::class, 'feed_category_id'],
            'drug' => [Drug::class, 'drug_category_id'],
            'misc' => [Miscellaneous::class, 'miscellaneous_category_id'],
        };

        $expense = $model::findOrFail($request->id);

        $data = [
            'breeding_cycle_id' => $request->breeding_cycle_id,
            $categoryIdKey      => $request->category_id,
            'name'=> $request->name,
            'quantity' => $request->quantity,
            'price' => $request->unit_price,
            'description' => $request->description,
        ];

        if ($request->type == 'feed') {
            $data['bag_count'] = $request->bag_count;
        }

        $expense->update($data);

        return response()->json([
            'res' => 10,
            'mySuccess' => "  {$request->name} با موفقیت ویرایش گردید"
        ]);
    }

    public function destroy(Request $request, $id) : JsonResponse
    {

        $request->validate(['type' => 'required|string|in:feed,drug,misc']);

        $model = match ($request->type) {
            'feed' => Feed::class,
            'drug' => Drug::class,
            'misc' => Miscellaneous::class,
        };


        $expense = $model::findOrFail($request->id);
        $expense->delete();

        return response()->json([
            'res' => 10,
            'mySuccess' => "  {$expense->name} با موفقیت حذف گردید"
        ]);
    }

}
