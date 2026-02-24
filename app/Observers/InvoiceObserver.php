<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Invoice;
use Illuminate\Support\Str;

final readonly class InvoiceObserver
{
    public function creating(Invoice $invoice): void
    {
        if (empty($invoice->invoice_number)) {
            $invoice->invoice_number = $this->generateInvoiceNumber();
        }
    }

    private function generateInvoiceNumber(): string
    {
        $year = now()->year;
        $prefix = 'INV-'.$year.'-';

        do {
            $random = Str::upper(Str::random(8));
            $invoiceNumber = $prefix.$random;
        } while (Invoice::query()->where('invoice_number', $invoiceNumber)->exists());

        return $invoiceNumber;
    }
}
