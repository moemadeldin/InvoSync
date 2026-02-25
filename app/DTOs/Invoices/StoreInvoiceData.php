<?php

declare(strict_types=1);

namespace App\DTOs\Invoices;

final readonly class StoreInvoiceData
{
    public function __construct(
        public string $customerId,
        public array $items,
        public string $status,
        public float $taxRate,
        public string $invoiceDate,
        public string $dueDate,
        public ?string $notes = null,
    ) {}

    public static function fromArray(array $data): self
    {
        $itemsData = $data['items'];
        $customerId = $data['customer_id'];
        $status = $data['status'];
        $taxRate = (float) ($data['tax_rate'] ?? 0);
        $invoiceDate = $data['invoice_date'];
        $dueDate = $data['due_date'];
        $notes = $data['notes'] ?? null;

        return new self(
            customerId: (string) $customerId,
            items: array_map(
                InvoiceItemData::fromArray(...),
                $itemsData,
            ),
            status: (string) $status,
            taxRate: $taxRate,
            invoiceDate: $invoiceDate,
            dueDate: $dueDate,
            notes: $notes !== null ? (string) $notes : null,
        );
    }
}
