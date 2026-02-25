<?php

declare(strict_types=1);

namespace App\Actions\Payment;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Payment;

final readonly class DeletePaymentAction
{
    public function execute(Payment $payment): void
    {
        $invoice = $payment->invoice;
        $payment->delete();

        $this->revertInvoiceStatus($invoice);
    }

    private function revertInvoiceStatus(Invoice $invoice): void
    {
        $totalPaid = (float) $invoice->payments()->sum('amount');
        $invoiceTotal = (float) $invoice->total;

        if ($totalPaid < $invoiceTotal && $invoice->status === InvoiceStatus::Paid) {
            $invoice->update(['status' => InvoiceStatus::Sent]);
        }
    }
}
