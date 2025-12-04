<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();


        $totalIncome = $user->transactions()->whereHas('category', fn($q) => $q->where('type', 'income'))->sum('amount');
        $totalExpense = $user->transactions()->whereHas('category', fn($q) => $q->where('type', 'expense'))->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // 4. Selected Month Summary (This Month / Filtered Month)
        $monthIncome = $user->transactions()
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->whereHas('category', fn($q) => $q->where('type', 'income'))
            ->sum('amount');

        $monthExpense = $user->transactions()
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->whereHas('category', fn($q) => $q->where('type', 'expense'))
            ->sum('amount');

        // 5. Recent Transactions
        $recentTransactions = $user->transactions()->with('category')->latest('transaction_date')->limit(5)->get();

        // 6. Chart Data (Filtered by Date)
        $expenseChart = $this->getChartData($user, 'expense', $startDate, $endDate);
        $incomeChart = $this->getChartData($user, 'income', $startDate, $endDate);

        return response()->json([
            'balance' => [
                'total' => number_format($balance, 2, '.', ''),
                'formatted' => '৳' . number_format($balance, 2),
                'status' => $balance >= 0 ? 'positive' : 'negative'
            ],
            'selected_month' => [
                'name' => $startDate->format('F Y'),
                'income' => number_format($monthIncome, 2, '.', ''),
                'income_formatted' => '৳' . number_format($monthIncome, 2),
                'expense' => number_format($monthExpense, 2, '.', ''),
                'expense_formatted' => '৳' . number_format($monthExpense, 2),
            ],
            'recent_transactions' => TransactionResource::collection($recentTransactions),
            'expense_chart_data' => $expenseChart,
            'income_chart_data' => $incomeChart,
        ]);
    }

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
