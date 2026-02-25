<?php

declare(strict_types=1);

namespace App\Queries\Payment;

use App\Models\Payment;
use App\Utils\Constants;
use Illuminate\Contracts\Pagination\Paginator;

final readonly class GetPaymentsQuery
{
    public function execute(array $filters): Paginator
    {
        return Payment::query()
            ->withInvoiceAndCustomer()
            ->filterByInvoice($filters['invoice_id'] ?? null)
            ->filterByCustomer($filters['customer_id'] ?? null)
            ->filterByDateFrom($filters['date_from'] ?? null)
            ->filterByDateTo($filters['date_to'] ?? null)
            ->latest()
            ->simplePaginate(Constants::NUMBER_OF_PAGINATED_PAYMENTS)
            ->withQueryString();
    }
}
