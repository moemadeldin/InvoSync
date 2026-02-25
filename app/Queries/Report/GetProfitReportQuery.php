<?php

declare(strict_types=1);

namespace App\Queries\Report;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\SalesReturn;

final readonly class GetProfitReportQuery
{
    public function execute(int $year, int $month): array
    {
        $invoices = Invoice::query()
            ->whereYear('invoice_date', $year)
            ->whereMonth('invoice_date', $month)
            ->get();

        $revenue = $invoices->sum(fn ($invoice): float => (float) $invoice->total - (float) ($invoice->sales_return_total ?? 0));

        $returns = SalesReturn::query()
            ->whereYear('return_date', $year)
            ->whereMonth('return_date', $month)
            ->where('status', 'approved')
            ->get();
        $returnsTotal = $returns->sum('total');

        $payments = Payment::query()
            ->whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month)
            ->get();
        $paymentsTotal = $payments->sum('amount');

        $netRevenue = $revenue - $returnsTotal;

        return [
            'revenue' => $revenue,
            'returns' => $returnsTotal,
            'payments' => $paymentsTotal,
            'net_revenue' => $netRevenue,
        ];
    }
}
