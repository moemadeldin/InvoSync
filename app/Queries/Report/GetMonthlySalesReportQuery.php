<?php

declare(strict_types=1);

namespace App\Queries\Report;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\SalesReturn;

final readonly class GetMonthlySalesReportQuery
{
    public function execute(int $year, int $month): array
    {
        $invoices = Invoice::query()
            ->with('customer')
            ->whereYear('invoice_date', $year)
            ->whereMonth('invoice_date', $month)
            ->get();

        $totalSales = $invoices->sum(fn ($invoice): float => (float) $invoice->total - (float) ($invoice->sales_return_total ?? 0));

        $returns = SalesReturn::query()
            ->whereYear('return_date', $year)
            ->whereMonth('return_date', $month)
            ->where('status', 'approved')
            ->get();
        $totalReturns = $returns->sum('total');

        $payments = Payment::query()
            ->whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month)
            ->get();
        $totalPayments = $payments->sum('amount');

        $netSales = $totalSales - $totalReturns;

        return [
            'invoices' => $invoices,
            'total_sales' => $totalSales,
            'total_returns' => $totalReturns,
            'total_payments' => $totalPayments,
            'net_sales' => $netSales,
        ];
    }
}
