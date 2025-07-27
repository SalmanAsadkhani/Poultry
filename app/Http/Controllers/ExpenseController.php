<?php

namespace App\Http\Controllers;

use App\Http\Requests\editExpense;
use App\Http\Requests\StoreExpense;
use App\Models\BreedingCycle;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index() : View
    {
        $cycles = BreedingCycle::where('user_id', auth()->id())->get();
        return view('expense.index' , compact('cycles'));
    }

    public function Invoice(Request $request ): JsonResponse
    {

        $request->validate([
            'breeding_cycle_id' => 'required|exists:breeding_cycles,id',
            'NameInvoice' => ['required', 'string', 'max:255' , 'min:3'],
        ]);

        $expense = ExpenseCategory::create([
            'breeding_cycle_id' => $request->breeding_cycle_id,
            'name' => $request->NameInvoice,
            'status' => 1
        ]);

        return response()->json(['res' => 10]);

    }

    public function show($id): View
    {
        $expenseCate = ExpenseCategory::with(  'expenses')->where('id', $id)->firstOrFail();

        $total_feed = $expenseCate->expenses->sum('quantity');
        $total_price = $expenseCate->expenses->sum('total_price');
        $average_price = $expenseCate->expenses->avg('unit_price');

        return view('expense.show' , compact('expenseCate' , 'total_feed' , 'total_price' , 'average_price'));
    }

    public function store(StoreExpense $request ): JsonResponse
    {
        $expense = Expense::create([
            'breeding_cycle_id' => $request->breeding_cycle_id,
            'expense_category_id' => $request->expense_category_id,
            'name' => $request->name,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'description' => $request->description,
            'status' => 1
        ]);

        return response()->json([
            'res' => 10,
            'mySuccess' => 'با موفقیت ثبت گردید'
        ]);
    }

    public function update(editExpense $request , $id ): JsonResponse
    {
        $expense = Expense::where('id', $id)->first();

        $expense->update([
            'breeding_cycle_id' => $request->breeding_cycle_id,
            'expense_category_id' => $request->expense_category_id,
            'name' => $request->name,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'description' => $request->description,
            'status' => 1,
        ]);

        return response()->json([
            'res' => 10,
            'mySuccess' => 'با موفقیت ,ویرایش گردید'
        ]);
    }

    public function delete($id)
    {
        $expense = Expense::where('id', $id)->first();

        $expense->delete();

        return response()->json([
            'res' => 10,
            'mySuccess' => 'رکورد با موفقیت حذف گردید'
        ]);

    }

}
