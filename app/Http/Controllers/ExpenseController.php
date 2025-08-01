<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Requests\StoreExpenseRequest;
use App\Models\BreedingCycle;
use App\Models\Drug;
use App\Models\DrugCategory;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Feed;
use App\Models\FeedCategory;
use App\Models\Miscellaneous;
use App\Models\MiscellaneousCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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


        return response()->json(['res' => 10]);

    }

    public function showCategory(string $type, int $categoryId) : View
    {

        [$categoryModel, $relation, $viewName, $titlePrefix] = match ($type) {
            'feed' => [FeedCategory::class, 'feeds', 'expense.show-feed', 'صورتحساب دان'],
            'drug' => [DrugCategory::class, 'drugs', 'expense.show-drug', 'صورتحساب  داروخانه'],
            'misc' => [MiscellaneousCategory::class, 'miscellaneous', 'expense.show-misc', 'صورتحساب  متفرقه'],

        };

        $category = $categoryModel::with($relation)->findOrFail($categoryId);

        return view($viewName, [
            'category' => $category,
            'expenses' => $category->$relation,
            'title'    => $titlePrefix,
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


        $model::create($data);

        return response()->json([
            'res' => 10,
            'mySuccess' => 'رکورد با موفقیت اضافه گردید'
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


        $expense->update($data);

        return response()->json([
            'res' => 10,
            'mySuccess' => 'رکورد با موفقیت ویرایش گردید',
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
            'mySuccess' => 'رکورد با موفقیت حذف گردید'
        ]);
    }

}
