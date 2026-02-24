<?php

declare(strict_types=1);

namespace App\Actions\Invoice;

use App\Models\Invoice;

final readonly class DeleteInvoiceAction
{
    public function execute(Invoice $invoice): void
    {
        $invoice->items()->delete();
        $invoice->delete();
    }
}
