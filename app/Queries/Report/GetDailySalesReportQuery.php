<?php

declare(strict_types=1);

namespace App\Queries\Report;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\SalesReturn;

final readonly class GetDailySalesReportQuery
{
    public function execute(string $date): array
    {
        $invoices = Invoice::query()
            ->with('customer')
            ->whereDate('invoice_date', $date)
            ->get();

        $totalSales = $invoices->sum(fn ($invoice): float => (float) $invoice->total - (float) ($invoice->sales_return_total ?? 0));

        $returns = SalesReturn::query()
            ->whereDate('return_date', $date)
            ->where('status', 'approved')
            ->get();
        $totalReturns = $returns->sum('total');

        $payments = Payment::query()
            ->whereDate('payment_date', $date)
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
