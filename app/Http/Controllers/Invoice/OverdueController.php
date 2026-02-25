<?php

declare(strict_types=1);

namespace App\Http\Controllers\Invoice;

use App\Queries\Invoice\GetOverdueInvoicesQuery;
use Illuminate\View\View;

final readonly class OverdueController
{
    public function __construct(
        private GetOverdueInvoicesQuery $getOverdueInvoicesQuery,
    ) {}

    public function __invoke(): View
    {
        $overdueInvoices = $this->getOverdueInvoicesQuery->execute();

        $agingReport = [
            'current' => $overdueInvoices->filter(fn ($invoice): bool => $invoice->days_overdue <= 0)->sum('total'),
            '1_30_days' => $overdueInvoices->filter(fn ($invoice): bool => $invoice->days_overdue >= 1 && $invoice->days_overdue <= 30)->sum('total'),
            '31_60_days' => $overdueInvoices->filter(fn ($invoice): bool => $invoice->days_overdue >= 31 && $invoice->days_overdue <= 60)->sum('total'),
            'over_60_days' => $overdueInvoices->filter(fn ($invoice): bool => $invoice->days_overdue > 60)->sum('total'),
        ];

        $totalOverdue = $overdueInvoices->sum('total');
        $countOverdue = $overdueInvoices->count();

        return view('overdue.index', [
            'overdueInvoices' => $overdueInvoices,
            'agingReport' => $agingReport,
            'totalOverdue' => $totalOverdue,
            'countOverdue' => $countOverdue,
        ]);
    }
}
