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
        $query = $request->user()
                    ->transactions()
                    ->with('category');

        if($request->has('month')){
            $query->whereMonth('transaction_date', $request->month);
        }

        if($request->has('year')){
            $query->whereMonth('transaction_date', $request->year);
        }

        if($request->has('type')){
            $query->where('category', function($q) use ($request){
                $q->where('type', $request->type);
            });
        }

        $transactions = $query->latest('transaction_date')->paginate(10);
        return TransactionResource::collection($transactions);
    }

    public function store(StoreTransactionRequest $request)
    {
        $transaction = $request->user()->transactions()->create($request->validated());
        $transaction->load('category');
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
