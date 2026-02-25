<?php

declare(strict_types=1);

namespace App\DTOs\Invoices;

final readonly class UpdateInvoiceData
{
    public function __construct(
        public string $customerId,
        public string $status,
        public float $taxRate = 0.0,
        public ?array $items = null,
        public ?string $invoiceDate = null,
        public ?string $dueDate = null,
        public ?string $notes = null,
    ) {}

    public static function fromArray(array $data): self
    {

        $itemsData = $data['items'] ?? null;

        $customerId = $data['customer_id'];

        $status = $data['status'];

        $taxRate = (float) $data['tax_rate'] ?? 0;

        $invoiceDate = $data['invoice_date'] ?? null;
        $dueDate = $data['due_date'] ?? null;

        $notes = $data['notes'] ?? null;

        return new self(
            customerId: (string) $customerId,
            status: (string) $status,
            taxRate: $taxRate,
            items: $itemsData !== null
                ? array_map(InvoiceItemData::fromArray(...), $itemsData)
                : null,
            invoiceDate: $invoiceDate !== null ? (string) $invoiceDate : null,
            dueDate: $dueDate !== null ? (string) $dueDate : null,
            notes: $notes !== null ? (string) $notes : null,
        );
    }
}
