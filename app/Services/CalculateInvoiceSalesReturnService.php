<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Invoice;
use App\Models\SalesReturn;

final readonly class CalculateInvoiceSalesReturnService
{
    public function addReturn(Invoice $invoice, SalesReturn $salesReturn): Invoice
    {
        $currentReturnTotal = (float) ($invoice->sales_return_total ?? 0);
        $newReturnTotal = $currentReturnTotal + (float) $salesReturn->total;

        $invoice->update([
            'sales_return_total' => $newReturnTotal,
        ]);

        return $invoice->fresh();
    }

    public function removeReturn(Invoice $invoice, SalesReturn $salesReturn): Invoice
    {
        $currentReturnTotal = (float) ($invoice->sales_return_total ?? 0);
        $newReturnTotal = max(0, $currentReturnTotal - (float) $salesReturn->total);

        $invoice->update([
            'sales_return_total' => $newReturnTotal,
        ]);

        return $invoice->fresh();
    }

    public function getAdjustedTotal(Invoice $invoice): float
    {
        return max(0, (float) $invoice->total - (float) ($invoice->sales_return_total ?? 0));
    }
}
