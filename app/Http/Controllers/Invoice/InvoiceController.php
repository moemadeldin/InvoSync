<?php

declare(strict_types=1);

namespace App\Http\Controllers\Invoice;

use App\Actions\Invoice\CreateInvoiceAction;
use App\Actions\Invoice\DeleteInvoiceAction;
use App\Actions\Invoice\UpdateInvoiceAction;
use App\DTOs\Invoices\StoreInvoiceData;
use App\DTOs\Invoices\UpdateInvoiceData;
use App\Http\Requests\Invoice\FilterInvoicesRequest;
use App\Http\Requests\Invoice\StoreInvoiceRequest;
use App\Http\Requests\Invoice\UpdateInvoiceRequest;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use App\Queries\Invoice\GetInvoicesQuery;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final readonly class InvoiceController
{
    public function __construct(
        private GetInvoicesQuery $getInvoicesQuery,
    ) {}

    public function index(FilterInvoicesRequest $request): View
    {
        $invoices = $this->getInvoicesQuery->execute($request->validated());

        return view('invoices.index', [
            'invoices' => $invoices,
            'filters' => $request->validated(),
        ]);
    }

    public function create(): View
    {
        $customers = Customer::query()
            ->withUser()
            ->get();

        return view('invoices.create', ['customers' => $customers]);
    }

    public function store(#[CurrentUser()] User $user, StoreInvoiceRequest $request, CreateInvoiceAction $action): RedirectResponse
    {
        $action->execute(StoreInvoiceData::fromArray($request->validated()), $user);

        return to_route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load('items');

        return view('invoices.show', ['invoice' => $invoice]);
    }

    public function edit(Invoice $invoice): View
    {
        $invoice->load(['customer', 'items']);
        $customers = Customer::query()
            ->withUser()
            ->get();

        return view('invoices.edit', ['invoice' => $invoice, 'customers' => $customers]);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice, UpdateInvoiceAction $action): RedirectResponse
    {
        $action->execute($invoice, UpdateInvoiceData::fromArray($request->validated()));

        return to_route('invoices.show', $invoice)->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice, DeleteInvoiceAction $action): RedirectResponse
    {
        $action->execute($invoice);

        return to_route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}
