<?php

declare(strict_types=1);

namespace App\Actions\Payment;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;

final readonly class CreatePaymentAction
{
    public function execute(array $data, User $user): Payment
    {
        $invoice = Invoice::query()->findOrFail($data['invoice_id']);

        $payment = Payment::query()->create([
            'user_id' => $user->id,
            'invoice_id' => $invoice->id,
            'customer_id' => $invoice->customer_id,
            'amount' => $data['amount'],
            'payment_date' => $data['payment_date'],
            'payment_method' => $data['payment_method'],
            'reference_number' => $data['reference_number'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        $this->updateInvoiceStatus($invoice);

        return $payment;
    }

    private function updateInvoiceStatus(Invoice $invoice): void
    {
        $totalPaid = (float) $invoice->payments()->sum('amount');
        $invoiceTotal = (float) $invoice->total;

        if ($totalPaid >= $invoiceTotal && $invoice->status !== InvoiceStatus::Paid) {
            $invoice->update(['status' => InvoiceStatus::Paid]);
        }
    }
}
