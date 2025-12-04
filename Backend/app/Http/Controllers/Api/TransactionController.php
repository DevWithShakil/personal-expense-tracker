<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;



class TransactionController extends Controller
{
    public function index(Request $request)
{
    $query = $request->user()->transactions()->with('category');

    // 1. Filter by Month
    if ($request->has('month')) {
        $query->whereMonth('transaction_date', $request->month);
    }

    // 2. Filter by Year
    if ($request->has('year')) {
        $query->whereYear('transaction_date', $request->year);
    }

    // 3. Filter by Type (Income/Expense)
    if ($request->has('type') && $request->type !== 'all') {
        $query->whereHas('category', function ($q) use ($request) {
            $q->where('type', $request->type);
        });
    }

    // 4. Search by Description
    if ($request->has('search')) {
        $searchTerm = $request->search;
        $query->where(function($q) use ($searchTerm) {
            $q->where('description', 'ILIKE', "%{$searchTerm}%")
              ->orWhereHas('category', function($catQ) use ($searchTerm) {
                  $catQ->where('name', 'ILIKE', "%{$searchTerm}%");
              });
        });
    }

    $transactions = $query->latest('transaction_date')->paginate(20);

    return TransactionResource::collection($transactions);
}
    public function store(StoreTransactionRequest $request)
    {
        $transaction = $request->user()->transactions()->create($request->validated());
        $transaction->load('category');
        return new TransactionResource($transaction);
    }

    public function update(Transaction $transaction, StoreTransactionRequest $request)
    {
        $transaction->update($request->validated());
        return new TransactionResource($transaction);
    }

    public function destroy(Transaction $transaction)
    {
        if($transaction->user_id !== auth()->user()->id)
        {
            abort(403, 'You are not authorized to delete this transaction.');
        }

        $transaction->delete();
        return response()->noContent();
    }
}
