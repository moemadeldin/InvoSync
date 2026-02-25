<?php

declare(strict_types=1);

namespace App\Queries\Invoice;

use App\Models\Invoice;
use Illuminate\Contracts\Pagination\Paginator;

final readonly class GetOverdueInvoicesQuery
{
    public function execute(): Paginator
    {
        return Invoice::query()
            ->withCustomerAndUsers()
            ->overdue()
            ->overdueDays()
            ->simplePaginate()
            ->withQueryString();
    }
}
