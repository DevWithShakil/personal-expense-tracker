<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Financial Report</title>
    <style>
        /* Page Setup */
        @page { margin: 0px; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #1e293b;
            font-size: 12px;
            line-height: 1.5;
        }

        /* Header Section */
        .header-bg {
            background-color: #1e293b; /* Dark Slate Blue */
            height: 120px;
            padding: 40px 50px 0 50px;
            color: white;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }
        .report-subtitle {
            font-size: 14px;
            opacity: 0.8;
            font-weight: 300;
        }

        /* Meta Data (Right Aligned in Header) */
        .header-meta {
            position: absolute;
            top: 40px;
            right: 50px;
            text-align: right;
            color: white;
        }
        .meta-label {
            font-size: 9px;
            text-transform: uppercase;
            opacity: 0.6;
            letter-spacing: 1px;
        }
        .meta-value {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        /* Main Content Container */
        .container {
            padding: 40px 50px;
        }

        /* Summary Cards Layout */
        .summary-table {
            width: 100%;
            margin-bottom: 40px;
            border-spacing: 0 15px; /* Adds space between rows if needed, mainly for layout */
            border-collapse: separate;
            border-spacing: 10px 0; /* Gap between columns */
            margin-left: -10px; /* Offset for the first gap */
            margin-right: -10px;
        }
        .summary-card {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #cbd5e1;
            width: 30%; /* 3 columns */
        }

        /* Card Variants */
        .card-income { border-left-color: #22c55e; background-color: #f0fdf4; }
        .card-expense { border-left-color: #ef4444; background-color: #fef2f2; }
        .card-balance { border-left-color: #3b82f6; background-color: #eff6ff; }

        .card-title {
            font-size: 10px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .card-amount {
            font-size: 18px;
            font-weight: bold;
            margin-top: 8px;
            color: #0f172a;
        }

        /* Transaction Table */
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #334155;
            margin-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.data-table th {
            text-align: left;
            padding: 12px 15px;
            background-color: #f1f5f9;
            color: #475569;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: bold;
            border-bottom: 1px solid #e2e8f0;
        }
        table.data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        table.data-table tr:last-child td {
            border-bottom: none;
        }
        table.data-table tr:nth-child(even) {
            background-color: #fcfcfc;
        }

        /* Helpers */
        .text-right { text-align: right; }
        .text-green { color: #16a34a; }
        .text-red { color: #dc2626; }
        .font-bold { font-weight: bold; }

        .category-badge {
            font-size: 11px;
            font-weight: 600;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #f8fafc;
            padding: 20px 50px;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header-bg">
        <div class="company-name">Expense Tracker</div>
        <div class="report-subtitle">Personal Financial Statement</div>

        <div class="header-meta">
            <div>
                <div class="meta-label">Report Period</div>
                <div class="meta-value">{{ $month_name }}</div>
            </div>
            <div style="margin-top: 10px;">
                <div class="meta-label">Generated For</div>
                <div class="meta-value">{{ $user_name }}</div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Summary Cards (Using Table for Layout Stability in DomPDF) -->
        <table class="summary-table">
            <tr>
                <td class="summary-card card-income">
                    <div class="card-title">Total Income</div>
                    <div class="card-amount text-green">BDT {{ $total_income }}</div>
                </td>
                <td class="summary-card card-expense">
                    <div class="card-title">Total Expense</div>
                    <div class="card-amount text-red">BDT {{ $total_expense }}</div>
                </td>
                <td class="summary-card card-balance">
                    <div class="card-title">Net Balance</div>
                    <div class="card-amount">BDT {{ $balance }}</div>
                </td>
            </tr>
        </table>

        <!-- Transactions List -->
        <div class="section-title">Transaction History</div>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 15%">Date</th>
                    <th style="width: 25%">Category</th>
                    <th style="width: 40%">Description</th>
                    <th style="width: 20%" class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                <tr>
                    <td>{{ $t->transaction_date->format('d M, Y') }}</td>
                    <td>
                        <span class="category-badge" style="color: {{ $t->category->color_code ?? '#475569' }}">
                            {{ $t->category->name }}
                        </span>
                    </td>
                    <td style="color: #64748b;">{{ $t->description ?? '-' }}</td>
                    <td class="text-right {{ $t->category->type == 'income' ? 'text-green' : 'text-red' }} font-bold">
                        {{ $t->category->type == 'income' ? '+' : '-' }} {{ number_format($t->amount, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 30px; color: #94a3b8;">
                        No transactions found for this period.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <table width="100%">
            <tr>
                <td style="padding:0; border:none; width:50%;">
                    Generated automatically on {{ date('d M, Y h:i A') }}
                </td>
                <td style="padding:0; border:none; width:50%;" class="text-right">
                    Page <span class="page-number"></span>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
