<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Models\Customer;
use App\Queries\Customer\GetCustomerStatementQuery;
use App\Queries\Customer\GetCustomerStatementsQuery;
use Illuminate\View\View;

final readonly class CustomerStatementController
{
    public function __construct(
        private GetCustomerStatementsQuery $getCustomerStatementsQuery,
        private GetCustomerStatementQuery $getCustomerStatementQuery,
    ) {}

    public function index(): View
    {
        $customers = $this->getCustomerStatementsQuery->execute();

        return view('customers.statements.index', [
            'customers' => $customers,
        ]);
    }

    public function show(Customer $customer): View
    {
        $result = $this->getCustomerStatementQuery->execute($customer);

        return view('customers.statements.show', [
            'customer' => $customer,
            'transactions' => $result->transactions,
            'summary' => $result->summary,
        ]);
    }
}
