<?php

declare(strict_types=1);

namespace App\Queries\Invoice;

use App\Models\Invoice;

final readonly class GetOverdueInvoicesQuery
{
    public function execute(): array
    {
        $overdueInvoices = Invoice::query()
            ->withCustomerAndUsers()
            ->overdue()
            ->overdueDays()
            ->simplePaginate()
            ->withQueryString();

        $allOverdue = Invoice::query()
            ->overdue()
            ->overdueDays()
            ->get(['total', 'days_overdue']);

        $agingReport = [
            'current' => 0.0,
            '1_30_days' => 0.0,
            '31_60_days' => 0.0,
            'over_60_days' => 0.0,
        ];

        foreach ($allOverdue as $invoice) {
            $days = (int) $invoice->days_overdue;
            $agingReport[match (true) {
                $days <= 0 => 'current',
                $days <= 30 => '1_30_days',
                $days <= 60 => '31_60_days',
                default => 'over_60_days',
            }] += (float) $invoice->total;
        }

        return [
            'overdueInvoices' => $overdueInvoices,
            'agingReport' => $agingReport,
            'totalOverdue' => $allOverdue->sum('total'),
            'countOverdue' => $allOverdue->count(),
        ];
    }
}
