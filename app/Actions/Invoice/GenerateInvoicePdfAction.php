<?php

declare(strict_types=1);

namespace App\Actions\Invoice;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

final readonly class GenerateInvoicePdfAction
{
    public function execute(Invoice $invoice): Response
    {
        $invoice->load(['customer', 'user', 'items']);

        return Pdf::loadView('invoices.pdf', ['invoice' => $invoice])
            ->setPaper('a4')
            ->setOption('isHtml5ParserEnabled', true)
            ->download(sprintf('invoice-%s.pdf', $invoice->invoice_number));
    }
}
