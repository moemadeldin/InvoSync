<?php

declare(strict_types=1);

namespace App\Actions\SalesReturn;

use App\Enums\InvoiceStatus;
use App\Enums\SalesReturnStatus;
use App\Models\SalesReturn;
use App\Services\CalculateInvoiceSalesReturnService;
use Illuminate\Support\Facades\DB;

final readonly class DeleteSalesReturnAction
{
    public function __construct(
        private CalculateInvoiceSalesReturnService $salesReturnService,
    ) {}

    public function execute(SalesReturn $salesReturn): void
    {
        DB::transaction(function () use ($salesReturn): void {
            if ($salesReturn->invoice_id && $salesReturn->status === SalesReturnStatus::Approved) {
                $invoice = $salesReturn->invoice->fresh();
                $this->salesReturnService->removeReturn($invoice, $salesReturn);

                $invoice = $invoice->fresh();
                if ($invoice->status === InvoiceStatus::Returned) {
                    $restoreStatus = $invoice->pre_return_status ?? InvoiceStatus::Sent->value;
                    $invoice->update([
                        'status' => $restoreStatus,
                        'pre_return_status' => null,
                    ]);
                }
            }

            $salesReturn->delete();
        });
    }
}
