<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\Invoices\InvoiceItemData;
use App\Models\Invoice;

final readonly class InvoiceItemSyncService
{
    public function __construct(
        private CalculateInvoiceTotalService $calculateTotals,
    ) {}

    public function sync(Invoice $invoice, array $items): void
    {
        $invoice->items()->delete();

        $invoice->items()->createMany(
            array_map(
                static fn (InvoiceItemData $item): array => [
                    'description' => $item->description,
                    'qty' => $item->qty,
                    'unit_price' => $item->unitPrice,
                    'total' => $item->total(),
                ],
                $items,
            ),
        );
    }

    public function applyTotals(Invoice $invoice, array $items, float $taxAmount): void
    {
        $totals = $this->calculateTotals->calculate($items, $taxAmount);
        $invoice->update([
            'subtotal' => $totals->subtotal,
            'tax' => $totals->tax,
            'total' => $totals->total,
        ]);
    }
}
