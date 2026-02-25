@extends('layouts.app')

@section('title', 'Overdue Invoices')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-semibold text-slate-50">Overdue Invoices</h1>
            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg text-slate-200 transition">
                Back
            </a>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-red-900/30 border border-red-700/50 rounded-xl p-6">
                <p class="text-red-400 text-sm font-medium">Total Overdue</p>
                <p class="text-3xl font-bold text-red-400 mt-2">${{ number_format($totalOverdue, 2) }}</p>
            </div>
            <div class="bg-slate-800 rounded-xl p-6">
                <p class="text-slate-400 text-sm font-medium">Current (Due Today)</p>
                <p class="text-2xl font-bold text-slate-50 mt-2">${{ number_format($agingReport['current'], 2) }}</p>
            </div>
            <div class="bg-slate-800 rounded-xl p-6">
                <p class="text-yellow-400 text-sm font-medium">1-30 Days</p>
                <p class="text-2xl font-bold text-yellow-400 mt-2">${{ number_format($agingReport['1_30_days'], 2) }}</p>
            </div>
            <div class="bg-slate-800 rounded-xl p-6">
                <p class="text-orange-400 text-sm font-medium">31-60 Days</p>
                <p class="text-2xl font-bold text-orange-400 mt-2">${{ number_format($agingReport['31_60_days'], 2) }}</p>
            </div>
            <div class="bg-slate-800 rounded-xl p-6">
                <p class="text-red-400 text-sm font-medium">60+ Days</p>
                <p class="text-2xl font-bold text-red-400 mt-2">${{ number_format($agingReport['over_60_days'], 2) }}</p>
            </div>
            <div class="bg-slate-800 rounded-xl p-6">
                <p class="text-slate-400 text-sm font-medium">Total Invoices</p>
                <p class="text-2xl font-bold text-slate-50 mt-2">{{ $countOverdue }}</p>
            </div>
        </div>

        <!-- Overdue Invoices Table -->
        <div class="bg-slate-800 rounded-2xl overflow-hidden shadow-lg">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-700">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Invoice #</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Customer</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Due Date</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Days Overdue</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Amount</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($overdueInvoices as $invoice)
                        <tr class="hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-200">
                                {{ $invoice->invoice_number }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-200">
                                {{ $invoice->customer->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-200">
                                {{ $invoice->due_date?->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($invoice->days_overdue > 60)
                                    <span class="px-2 py-1 text-xs rounded bg-red-900 text-red-300">{{ $invoice->days_overdue }} days</span>
                                @elseif($invoice->days_overdue > 30)
                                    <span class="px-2 py-1 text-xs rounded bg-orange-900 text-orange-300">{{ $invoice->days_overdue }} days</span>
                                @elseif($invoice->days_overdue > 0)
                                    <span class="px-2 py-1 text-xs rounded bg-yellow-900 text-yellow-300">{{ $invoice->days_overdue }} days</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-slate-700 text-slate-300">Due today</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-red-400 font-semibold">
                                ${{ number_format((float) $invoice->total, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('invoices.show', $invoice) }}"
                                        class="px-4 py-2 rounded-lg text-white font-semibold text-xs gradient-primary hover:shadow-lg transition-all">
                                        View
                                    </a>
                                    @if($invoice->status !== App\Enums\InvoiceStatus::Paid)
                                        <a href="{{ route('payments.create', $invoice->id) }}"
                                            class="px-4 py-2 rounded-lg text-white font-semibold text-xs bg-blue-600 hover:bg-blue-500 transition-all">
                                            Pay
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">No overdue invoices. Great job!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-container">
            {{ $overdueInvoices->links() }}
        </div>
    </div>
@endsection
