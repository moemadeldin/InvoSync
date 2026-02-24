<?php

declare(strict_types=1);

namespace App\DTOs\Invoices;

final readonly class InvoiceTotals
{
    public function __construct(
        public float $subtotal,
        public float $tax,
        public float $total,
    ) {}

    public function toArray(): array
    {
        return [
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'total' => $this->total,
        ];
    }
}
