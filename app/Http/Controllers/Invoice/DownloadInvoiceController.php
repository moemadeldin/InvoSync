<?php

declare(strict_types=1);

namespace App\Http\Controllers\Invoice;

use App\Actions\Invoice\GenerateInvoicePdfAction;
use App\Models\Invoice;
use Illuminate\Http\Response;

final readonly class DownloadInvoiceController
{
    public function __invoke(Invoice $invoice, GenerateInvoicePdfAction $action): Response
    {
        return $action->execute($invoice);
    }
}
