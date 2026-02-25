@extends('layouts.app')

@section('title', 'Payments')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-semibold text-slate-50">Payments</h1>
            <a href="{{ route('payments.create') }}"
                class="px-6 py-3 rounded-lg text-white font-semibold text-sm gradient-primary hover:shadow-lg hover:-translate-y-0.5 transition-all">
                Record Payment
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg px-4 py-3 mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-slate-800 rounded-2xl overflow-hidden shadow-lg">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-700">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Date</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Invoice #</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Customer</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Method</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Amount</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-200">
                                {{ $payment->payment_date->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-200">
                                {{ $payment->invoice->invoice_number }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-200">
                                {{ $payment->customer->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-200">
                                {{ $payment->payment_method->label() }}
                            </td>
                            <td class="px-6 py-4 text-sm text-green-400 font-semibold">
                                ${{ $payment->formatted_amount }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('payments.show', $payment) }}"
                                        class="px-4 py-2 rounded-lg text-white font-semibold text-xs gradient-primary hover:shadow-lg transition-all">
                                        View
                                    </a>
                                    <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="cursor-pointer px-4 py-2 rounded-lg text-white font-semibold text-xs bg-red-600 hover:bg-red-500 transition-all"
                                            onclick="return confirm('Are you sure?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">No payments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container mt-4">
            {{ $payments->links() }}
        </div>
    </div>
@endsection