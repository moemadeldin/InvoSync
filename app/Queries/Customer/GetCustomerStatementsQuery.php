<?php

declare(strict_types=1);

namespace App\Queries\Customer;

use App\Enums\SalesReturnStatus;
use App\Models\Customer;
use App\Utils\Constants;
use Illuminate\Pagination\Paginator;

final readonly class GetCustomerStatementsQuery
{
    public function execute(): Paginator
    {
        return Customer::query()
            ->withUser()
            ->withSum('invoices as total_invoiced', 'total')
            ->withSum(['salesReturns as total_returns' => function ($query): void {
                $query->where('status', SalesReturnStatus::Approved->value);
            }], 'total')
            ->withSum('payments as total_paid', 'amount')
            ->simplePaginate(Constants::NUMBER_OF_PAGINATED_CUSTOMERS)
            ->withQueryString();
    }
}
