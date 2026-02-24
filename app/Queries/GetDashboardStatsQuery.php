<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\InvoiceStatus;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Collection;

final readonly class GetDashboardStatsQuery
{
    public function execute(): array
    {
        $invoiceCounts = Invoice::query()
            ->get(['status', 'total'])
            ->groupBy('status');

        return [
            'total_customers' => Customer::query()->count(),

            'total_revenue' => $invoiceCounts
                ->get(InvoiceStatus::Paid->value)
                ?->sum('total') ?? 0,

            'invoices_by_status' => $invoiceCounts->mapWithKeys(
                fn (Collection $group, string $status): array => [$status => $group->count()]
            ),
        ];
    }
}
