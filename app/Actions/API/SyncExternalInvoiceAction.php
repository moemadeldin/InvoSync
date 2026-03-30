<?php

declare(strict_types=1);

namespace App\Actions\API;

use App\Enums\InvoiceStatus;
use App\Events\Invoices\InvoiceSentToCustomer;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

final readonly class SyncExternalInvoiceAction
{
    /**
     * @param  array{customer_email: string, customer_name: string, customer_phone: string, customer_company: string, user_id: string}  $data
     */
    public function execute(array $data): Invoice
    {
        return DB::transaction(function () use ($data): Invoice {
            $customer = $this->findOrCreateCustomer($data);

            $invoice = $this->createInvoice($customer, $data);

            $this->createInvoiceItem($invoice, $data);
            event(new InvoiceSentToCustomer($invoice));

            return $invoice;
        });
    }

    /**
     * @param  array{customer_email: string, customer_name: string, customer_phone: string, customer_company: string, user_id: string}  $data
     */
    private function findOrCreateCustomer(array $data): Customer
    {
        return Customer::query()
            ->firstOrCreate([
                'name' => $data['customer_name'],
                'email' => $data['customer_email'],
                'phone' => $data['customer_phone'] ?? null,
                'company' => $data['customer_company'] ?? null,
                'user_id' => $data['user_id']
            ]);
    }

    /**
     * @param  array{user_id: string, amount: float}  $data
     */
    private function createInvoice(Customer $customer, array $data): Invoice
    {
        return Invoice::query()->create([
            'user_id' => $data['user_id'],
            'customer_id' => $customer->id,
            'total' => $data['amount'],
            'subtotal' => $data['amount'],
            'status' => InvoiceStatus::Paid->value,
            'due_date' => now(),
            'invoice_date' => now(),
        ]);
    }

    /**
     * @param  array{description: string, amount: float}  $data
     */
    private function createInvoiceItem(Invoice $invoice, array $data): void
    {
        $invoice->items()->create([
            'description' => $data['description'],
            'qty' => 1,
            'unit_price' => $data['amount'],
            'total' => $data['amount'],
        ]);
    }
}
