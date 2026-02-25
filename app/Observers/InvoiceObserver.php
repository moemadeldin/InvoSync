<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

final readonly class InvoiceObserver
{
    public function creating(Invoice $invoice): void
    {
        if (empty($invoice->invoice_number)) {
            $invoice->invoice_number = $this->generateInvoiceNumber($invoice);
        }
    }

    private function generateInvoiceNumber(Invoice $invoice): string
    {
        $year = now()->year;

        return DB::transaction(function () use ($invoice, $year): string {
            $count = Invoice::query()
                ->where('user_id', $invoice->user_id)
                ->whereYear('created_at', $year)
                ->withTrashed()
                ->lockForUpdate()
                ->count();

            $sequence = mb_str_pad((string) ($count + 1), 4, '0', STR_PAD_LEFT);

            return sprintf('INV-%d-%s', $year, $sequence);
        });
    }
}
