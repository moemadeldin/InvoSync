<?php

declare(strict_types=1);

namespace App\Events\Invoices;

use App\Models\Invoice;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final readonly class InvoiceSentToCustomer
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Invoice $invoice,
    ) {}
}
