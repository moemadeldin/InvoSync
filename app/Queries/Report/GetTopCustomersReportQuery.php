<?php

declare(strict_types=1);

namespace App\Queries\Report;

use App\Models\Customer;
use Illuminate\Support\Collection;

final readonly class GetTopCustomersReportQuery
{
    public function execute(int $limit = 10): Collection
    {
        return Customer::query()
            ->with('invoices')
            ->get()
            ->map(function ($customer): array {
                $totalInvoiced = $customer->invoices()->sum('total');
                $totalPaid = $customer->invoices()
                    ->with('payments')
                    ->get()
                    ->sum(fn ($inv) => $inv->payments()->sum('amount'));
                $totalReturns = $customer->salesReturns()
                    ->where('status', 'approved')
                    ->sum('total');

                return [
                    'customer' => $customer,
                    'total_invoiced' => $totalInvoiced,
                    'total_paid' => $totalPaid,
                    'total_returns' => $totalReturns,
                    'balance' => $totalInvoiced - $totalPaid - $totalReturns,
                    'invoice_count' => $customer->invoices()->count(),
                ];
            })
            ->sortByDesc('total_invoiced')
            ->take($limit)
            ->values();
    }
}
