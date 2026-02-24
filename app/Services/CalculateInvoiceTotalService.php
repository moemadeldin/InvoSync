<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\Invoices\InvoiceItemData;
use App\DTOs\Invoices\InvoiceTotals;

final readonly class CalculateInvoiceTotalService
{
    public function calculate(array $items, float $taxAmount = 0.0): InvoiceTotals
    {
        $subtotal = array_reduce(
            $items,
            static fn (float $carry, InvoiceItemData $item): float => $carry + $item->total(),
            0.0,
        );

        $total = $subtotal + $taxAmount;

        return new InvoiceTotals(
            subtotal: round($subtotal, 2),
            tax: round($taxAmount, 2),
            total: round($total, 2),
        );
    }
}
