@extends('layouts.app')

@section('title', 'Customer Statement - ' . $customer->name)

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-semibold text-slate-50">Customer Statement</h1>
                <p class="text-slate-400 mt-1">{{ $customer->name }} ({{ $customer->email }})</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('customers.statements.index') }}"
                    class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg text-slate-200 transition">
                    Back
                </a>
                <a href="{{ route('customers.show', $customer) }}"
                    class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg text-slate-200 transition">
                    View Customer
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-slate-800 rounded-xl p-6">
                <p class="text-slate-400 text-sm font-medium">Total Invoiced</p>
                <p class="text-2xl font-bold text-slate-50 mt-2">${{ $summary->formattedTotalInvoiced }}</p>
            </div>
            <div class="bg-green-900/30 border border-green-700/50 rounded-xl p-6">
                <p class="text-green-400 text-sm font-medium">Total Paid</p>
                <p class="text-2xl font-bold text-green-400 mt-2">${{ $summary->formattedTotalPaid }}</p>
            </div>
            <div class="bg-red-900/30 border border-red-700/50 rounded-xl p-6">
                <p class="text-red-400 text-sm font-medium">Total Returns</p>
                <p class="text-2xl font-bold text-red-400 mt-2">${{ $summary->formattedTotalReturns }}</p>
            </div>
            <div class="bg-slate-800 rounded-xl p-6">
                <p class="text-slate-400 text-sm font-medium">Balance Due</p>
                <p class="text-2xl font-bold {{ $summary->balance > 0 ? 'text-red-400' : 'text-green-400' }} mt-2">
                    ${{ $summary->formattedBalance }}
                </p>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-slate-800 rounded-2xl overflow-hidden shadow-lg">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-700">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Date</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Type</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Reference</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-slate-200">Debit</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-slate-200">Credit</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-slate-200">Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-200">
                                {{ $transaction->date?->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($transaction->type === 'invoice')
                                    <span class="px-2 py-1 text-xs rounded bg-indigo-900 text-indigo-300">Invoice</span>
                                @elseif($transaction->type === 'payment')
                                    <span class="px-2 py-1 text-xs rounded bg-green-900 text-green-300">Payment</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-red-900 text-red-300">Return</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-200">
                                {{ $transaction->reference }}
                            </td>
                            <td class="px-6 py-4 text-sm text-right text-slate-200">
                                {{ $transaction->formattedDebit }}
                            </td>
                            <td class="px-6 py-4 text-sm text-right text-slate-200">
                                {{ $transaction->formattedCredit }}
                            </td>
                            <td
                                class="px-6 py-4 text-sm text-right font-semibold {{ $transaction->balance > 0 ? 'text-red-400' : 'text-green-400' }}">
                                ${{ $transaction->formattedBalance }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection