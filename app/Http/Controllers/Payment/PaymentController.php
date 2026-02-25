<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payment;

use App\Actions\Payment\CreatePaymentAction;
use App\Actions\Payment\DeletePaymentAction;
use App\Http\Requests\Payment\FilterPaymentRequest;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use App\Queries\Payment\GetPaymentsQuery;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final readonly class PaymentController
{
    public function __construct(
        private GetPaymentsQuery $getPaymentsQuery,
    ) {}

    public function index(FilterPaymentRequest $request): View
    {
        $payments = $this->getPaymentsQuery->execute($request->validated());

        return view('payments.index', [
            'payments' => $payments,
            'filters' => $request->validated(),
        ]);
    }

    public function create(?string $invoice_id = null): View
    {
        $invoices = Invoice::query()
            ->withCustomerAndUsers()
            ->get();

        $selectedInvoice = null;
        if ($invoice_id) {
            $selectedInvoice = Invoice::with(['items', 'customer', 'payments'])->find($invoice_id);
        }

        return view('payments.create', [
            'invoices' => $invoices,
            'selectedInvoice' => $selectedInvoice,
            'invoice_id' => $invoice_id,
        ]);
    }

    public function store(#[CurrentUser()] User $user, StorePaymentRequest $request, CreatePaymentAction $action): RedirectResponse
    {
        $action->execute($request->validated(), $user);

        return to_route('payments.index')->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment): View
    {
        $payment->load(['invoice', 'customer', 'invoice.payments']);

        return view('payments.show', ['payment' => $payment]);
    }

    public function destroy(Payment $payment, DeletePaymentAction $action): RedirectResponse
    {
        $action->execute($payment);

        return to_route('payments.index')->with('success', 'Payment deleted successfully.');
    }
}
