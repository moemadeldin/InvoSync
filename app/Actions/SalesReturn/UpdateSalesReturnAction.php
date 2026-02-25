<?php

declare(strict_types=1);

namespace App\Actions\SalesReturn;

use App\Enums\InvoiceStatus;
use App\Enums\SalesReturnStatus;
use App\Models\Invoice;
use App\Models\SalesReturn;
use App\Services\CalculateInvoiceSalesReturnService;
use Illuminate\Support\Facades\DB;

final readonly class UpdateSalesReturnAction
{
    public function __construct(
        private CalculateInvoiceSalesReturnService $salesReturnService,
    ) {}

    public function execute(SalesReturn $salesReturn, array $data): SalesReturn
    {
        return DB::transaction(function () use ($salesReturn, $data): SalesReturn {
            $oldStatus = $salesReturn->status;
            $newStatus = SalesReturnStatus::from($data['status']);

            if (! empty($data['invoice_id'])) {
                $invoice = Invoice::query()
                    ->with('items')
                    ->where('id', $data['invoice_id'])
                    ->lockForUpdate()
                    ->findOrFail($data['invoice_id']);

                [$itemsToSave, $subtotal] = $this->calculateItemsFromInvoice($data, $invoice);

                $this->updateSalesReturn($salesReturn, $data, $subtotal);
                $this->syncItems($salesReturn, $itemsToSave);

                $salesReturn = $salesReturn->fresh();
                $this->handleStatusChange($salesReturn, $invoice, $oldStatus, $newStatus);
                $this->syncInvoiceStatus($invoice);
            } else {
                $this->updateSalesReturn($salesReturn, $data, 0);
                $salesReturn->items()->delete();

                if ($salesReturn->invoice) {
                    $this->handleStatusChange($salesReturn, $salesReturn->invoice, $oldStatus, $newStatus);
                    $this->syncInvoiceStatus($salesReturn->invoice->fresh());
                }
            }

            return $salesReturn->fresh();
        });
    }

    private function calculateItemsFromInvoice(array $data, Invoice $invoice): array
    {
        $itemsToSave = [];
        $subtotal = 0;

        foreach ($invoice->items as $item) {
            $returnQty = (int) ($data['items'][$item->id]['qty'] ?? 0);
            $returnQty = min($returnQty, $item->qty);

            if ($returnQty > 0) {
                $itemTotal = $returnQty * (float) $item->unit_price;
                $subtotal += $itemTotal;
                $itemsToSave[] = [
                    'invoice_item_id' => $item->id,
                    'description' => $item->description,
                    'qty' => $returnQty,
                    'unit_price' => $item->unit_price,
                    'total' => $itemTotal,
                ];
            }
        }

        return [$itemsToSave, $subtotal];
    }

    private function updateSalesReturn(SalesReturn $salesReturn, array $data, float $subtotal): void
    {
        $salesReturn->update([
            'customer_id' => $data['customer_id'],
            'invoice_id' => $data['invoice_id'] ?? null,
            'return_date' => $data['return_date'],
            'reason' => $data['reason'],
            'notes' => $data['notes'] ?? null,
            'status' => $data['status'],
            'subtotal' => $subtotal,
            'tax' => 0,
            'total' => $subtotal,
        ]);
    }

    private function syncItems(SalesReturn $salesReturn, array $items): void
    {
        $salesReturn->items()->delete();
        $salesReturn->items()->createMany($items);
    }

    private function handleStatusChange(SalesReturn $salesReturn, Invoice $invoice, SalesReturnStatus $oldStatus, SalesReturnStatus $newStatus): void
    {
        if ($oldStatus !== SalesReturnStatus::Approved && $newStatus === SalesReturnStatus::Approved) {
            $this->salesReturnService->addReturn($invoice, $salesReturn);
        } elseif ($oldStatus === SalesReturnStatus::Approved && $newStatus !== SalesReturnStatus::Approved) {
            $this->salesReturnService->removeReturn($invoice, $salesReturn);
        }
    }

    private function syncInvoiceStatus(Invoice $invoice): void
    {
        $invoice->loadSum('approvedReturns', 'total');
        $approvedTotal = (float) $invoice->approved_returns_sum_total;

        if ($approvedTotal >= (float) $invoice->total) {
            $invoice->update([
                'pre_return_status' => $invoice->status,
                'status' => InvoiceStatus::Returned->value,
            ]);
        } elseif ($invoice->status === InvoiceStatus::Returned) {
            $restoreStatus = $invoice->pre_return_status ?? InvoiceStatus::Sent->value;
            $invoice->update([
                'status' => $restoreStatus,
                'pre_return_status' => null,
            ]);
        }
    }
}
