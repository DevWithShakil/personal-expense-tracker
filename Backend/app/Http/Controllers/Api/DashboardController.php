<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // 1. Balances
        $totalIncome = $user->transactions()->whereHas('category', fn($q) => $q->where('type', 'income'))->sum('amount');
        $totalExpense = $user->transactions()->whereHas('category', fn($q) => $q->where('type', 'expense'))->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // 2. This Month
        $thisMonthIncome = $user->transactions()->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])->whereHas('category', fn($q) => $q->where('type', 'income'))->sum('amount');
        $thisMonthExpense = $user->transactions()->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])->whereHas('category', fn($q) => $q->where('type', 'expense'))->sum('amount');

        // 3. Recent Transactions
        $recentTransactions = $user->transactions()->with('category')->latest('transaction_date')->limit(5)->get();

        // 4. Expense Chart Data
        $expenseChart = $this->getChartData($user, 'expense', $startOfMonth, $endOfMonth);

        // 5. Income Chart Data
        $incomeChart = $this->getChartData($user, 'income', $startOfMonth, $endOfMonth);

        return response()->json([
            'balance' => [
                'total' => number_format($balance, 2, '.', ''),
                'formatted' => '৳' . number_format($balance, 2),
                'status' => $balance >= 0 ? 'positive' : 'negative'
            ],
            'this_month' => [
                'income' => number_format($thisMonthIncome, 2, '.', ''),
                'income_formatted' => '৳' . number_format($thisMonthIncome, 2),
                'expense' => number_format($thisMonthExpense, 2, '.', ''),
                'expense_formatted' => '৳' . number_format($thisMonthExpense, 2),
            ],
            'recent_transactions' => TransactionResource::collection($recentTransactions),
            'expense_chart_data' => $expenseChart,
            'income_chart_data' => $incomeChart,
        ]);
    }

    // Helper function to avoid duplicate code
    private function getChartData($user, $type, $start, $end)
    {
        return $user->transactions()
            ->whereBetween('transaction_date', [$start, $end])
            ->whereHas('category', fn($q) => $q->where('type', $type))
            ->select('category_id', DB::raw('sum(amount) as total'))
            ->with('category:id,name,color_code')
            ->groupBy('category_id')
            ->get()
            ->map(function ($item) {
                return [
                    'category_name' => $item->category->name,
                    'color' => $item->category->color_code,
                    'amount' => (float) $item->total,
                    'formatted_amount' => '৳' . number_format($item->total, 2)
                ];
            });
    }
}
