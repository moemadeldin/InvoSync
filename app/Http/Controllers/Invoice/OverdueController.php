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
        return view('overdue.index', $this->getOverdueInvoicesQuery->execute());
    }
}
