<?php

declare(strict_types=1);

namespace App\Actions\Invoice;

use App\DTOs\Invoices\InvoiceItemData;
use App\DTOs\Invoices\UpdateInvoiceData;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Services\InvoiceItemSyncService;
use Illuminate\Support\Facades\DB;

final readonly class UpdateInvoiceAction
{
    public function __construct(
        private InvoiceItemSyncService $invoiceItemSync,
    ) {}

    public function execute(Invoice $invoice, UpdateInvoiceData $data): Invoice
    {
        return DB::transaction(function () use ($invoice, $data): Invoice {
            $invoice->update([
                'customer_id' => $data->customerId,
                'status' => $data->status,
                'invoice_date' => $data->invoiceDate,
                'due_date' => $data->dueDate,
                'notes' => $data->notes,
            ]);

            if ($data->items !== null) {
                $this->invoiceItemSync->sync($invoice, $data->items);
            }

            $taxRate = $data->taxRate ?? $invoice->tax_rate;
            $this->invoiceItemSync->applyTotals($invoice, $this->resolveItems($invoice, $data), $taxRate);

            return $invoice;
        });
    }

    private function resolveItems(Invoice $invoice, UpdateInvoiceData $data): array
    {
        if ($data->items !== null) {
            return $data->items;
        }

        return $invoice->items()
            ->get()
            ->map(static fn (InvoiceItem $item): InvoiceItemData => new InvoiceItemData(
                description: $item->description,
                qty: $item->qty,
                unitPrice: (float) $item->unit_price,
            ))
            ->all();
    }
}
