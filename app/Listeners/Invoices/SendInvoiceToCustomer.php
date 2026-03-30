<?php

declare(strict_types=1);

namespace App\Listeners\Invoices;

use App\Events\Invoices\InvoiceSentToCustomer;
use App\Mail\InvoiceCreatedMail;
use Illuminate\Support\Facades\Mail;

final readonly class SendInvoiceToCustomer
{
    public function handle(InvoiceSentToCustomer $event): void
    {
        $invoice = $event->invoice;

        $invoice->load(['customer', 'items']);

        Mail::to($invoice->customer->email)->send(new InvoiceCreatedMail($invoice));

    }
}
