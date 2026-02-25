<?php

declare(strict_types=1);

namespace App\Queries\Customer;

use App\Models\Customer;
use App\Utils\Constants;
use Illuminate\Contracts\Pagination\Paginator;

final readonly class GetCustomerStatementsQuery
{
    public function execute(): Paginator
    {
        return Customer::query()
            ->withUser()
            ->simplePaginate(Constants::NUMBER_OF_PAGINATED_CUSTOMERS)
            ->withQueryString();
    }
}
