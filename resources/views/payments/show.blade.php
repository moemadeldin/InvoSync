@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-slate-50">Payment Details</h1>
            <div class="flex gap-2">
                <a href="{{ route('payments.index') }}"
                    class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg text-slate-200 transition">
                    Back
                </a>
            </div>
        </div>

        <div class="bg-slate-800 rounded-2xl p-8 shadow-lg mb-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-slate-400 text-xs font-medium uppercase tracking-wide mb-1">Payment
                        Date</label>
                    <p class="text-slate-50">{{ $payment->payment_date->format('Y-m-d') }}</p>
                </div>
                <div>
                    <label class="block text-slate-400 text-xs font-medium uppercase tracking-wide mb-1">Amount</label>
                    <p class="text-green-400 text-xl font-bold">${{ $payment->formatted_amount }}</p>
                </div>
                <div>
                    <label class="block text-slate-400 text-xs font-medium uppercase tracking-wide mb-1">Payment
                        Method</label>
                    <p class="text-slate-50">{{ $payment->payment_method->label() }}</p>
                </div>
                <div>
                    <label class="block text-slate-400 text-xs font-medium uppercase tracking-wide mb-1">Reference
                        Number</label>
                    <p class="text-slate-50">{{ $payment->reference_number ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-slate-400 text-xs font-medium uppercase tracking-wide mb-1">Customer</label>
                    <p class="text-slate-50">{{ $payment->customer->name }}</p>
                </div>
                <div>
                    <label class="block text-slate-400 text-xs font-medium uppercase tracking-wide mb-1">Invoice</label>
                    <a href="{{ route('invoices.show', $payment->invoice) }}" class="text-indigo-400 hover:text-indigo-300">
                        {{ $payment->invoice->invoice_number }}
                    </a>
                </div>
            </div>

            @if($payment->notes)
                <div class="mt-6">
                    <label class="block text-slate-400 text-xs font-medium uppercase tracking-wide mb-1">Notes</label>
                    <p class="text-slate-50">{{ $payment->notes }}</p>
                </div>
            @endif
        </div>

        <div class="bg-slate-800 rounded-2xl p-8 shadow-lg">
            <h3 class="text-lg font-semibold text-slate-50 mb-4">Invoice Summary</h3>
            <div class="grid grid-cols-3 gap-4 text-sm">
                <div class="p-3 bg-slate-900 rounded-lg">
                    <p class="text-slate-400">Invoice Total</p>
                    <p class="text-slate-50 font-semibold">${{ number_format((float) $payment->invoice->total, 2) }}</p>
                </div>
                <div class="p-3 bg-slate-900 rounded-lg">
                    <p class="text-slate-400">Total Paid</p>
                    <p class="text-green-400 font-semibold">${{ number_format($payment->invoice->paid_amount, 2) }}</p>
                </div>
                <div class="p-3 bg-slate-900 rounded-lg">
                    <p class="text-slate-400">Remaining</p>
                    <p
                        class="font-semibold {{ $payment->invoice->remaining_amount > 0 ? 'text-red-400' : 'text-green-400' }}">
                        ${{ number_format($payment->invoice->remaining_amount, 2) }}
                    </p>
                </div>
            </div>

            @if($payment->invoice->payments->count() > 1)
                <h4 class="text-md font-medium text-slate-50 mt-6 mb-3">All Payments for this Invoice</h4>
                <table class="w-full text-sm">
                    <thead class="bg-slate-900">
                        <tr>
                            <th class="px-4 py-2 text-left text-slate-400">Date</th>
                            <th class="px-4 py-2 text-left text-slate-400">Amount</th>
                            <th class="px-4 py-2 text-left text-slate-400">Method</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @foreach($payment->invoice->payments as $pay)
                            <tr>
                                <td class="px-4 py-2 text-slate-50">{{ $pay->payment_date->format('Y-m-d') }}</td>
                                <td class="px-4 py-2 text-slate-50">${{ $pay->formatted_amount }}</td>
                                <td class="px-4 py-2 text-slate-50">{{ $pay->payment_method->label() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="mt-6 flex justify-end">
            <form action="{{ route('payments.destroy', $payment) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="cursor-pointer px-6 py-3 rounded-lg text-white font-semibold text-sm bg-red-600 hover:bg-red-500 transition-all"
                    onclick="return confirm('Are you sure?')">
                    Delete Payment
                </button>
            </form>
        </div>
    </div>
@endsection