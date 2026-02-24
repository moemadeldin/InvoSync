<?php

declare(strict_types=1);

namespace App\Queries\Invoice;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Utils\Constants;
use Illuminate\Contracts\Pagination\Paginator;

final readonly class GetInvoicesQuery
{
    public function execute(array $filters): Paginator
    {
        $status = isset($filters['status']) && is_string($filters['status'])
            ? InvoiceStatus::from($filters['status'])
            : null;

        return Invoice::query()
            ->withCustomerAndUsers()
            ->search(isset($filters['search']) && is_string($filters['search']) ? $filters['search'] : null)
            ->filterByStatus($status)
            ->filterByDateFrom(isset($filters['date_from']) && is_string($filters['date_from']) ? $filters['date_from'] : null)
            ->filterByDateTo(isset($filters['date_to']) && is_string($filters['date_to']) ? $filters['date_to'] : null)
            ->simplePaginate(Constants::NUMBER_OF_PAGINATED_INVOICES)
            ->withQueryString();
    }
}
