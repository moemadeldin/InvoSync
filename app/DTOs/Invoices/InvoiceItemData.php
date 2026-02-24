<?php

declare(strict_types=1);

namespace App\DTOs\Invoices;

final readonly class InvoiceItemData
{
    public function __construct(
        public string $description,
        public int $qty,
        public float $unitPrice,
    ) {}

    public static function fromArray(array $data): self
    {

        $description = $data['description'];

        $qty = $data['qty'];

        $unitPrice = $data['unit_price'];

        return new self(
            description: (string) $description,
            qty: (int) $qty,
            unitPrice: (float) $unitPrice,
        );
    }

    public function toArray(): array
    {
        return [
            'description' => $this->description,
            'qty' => $this->qty,
            'unitPrice' => $this->unitPrice,
        ];
    }

    public function total(): float
    {
        return $this->qty * $this->unitPrice;
    }
}
