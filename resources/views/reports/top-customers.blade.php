@extends('layouts.app')

@section('title', 'Top Customers Report')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-semibold text-slate-50">Top Customers Report</h1>
            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg text-slate-200 transition">
                Back
            </a>
        </div>

        <form method="GET" class="mb-6 flex items-center gap-4">
            <label class="text-slate-50">Show top</label>
            <select name="limit" class="px-4 py-2 bg-slate-800 border border-slate-700 rounded-lg text-slate-50">
                @foreach([5, 10, 15, 20, 25] as $l)
                    <option value="{{ $l }}" {{ $l == $limit ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
            <label class="text-slate-50">customers</label>
            <button type="submit"
                class="px-4 py-2 rounded-lg text-white font-semibold text-sm gradient-primary">
                Filter
            </button>
        </form>

        <div class="bg-slate-800 rounded-2xl overflow-hidden shadow-lg">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-700">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">#</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-200">Customer</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-slate-200">Invoices</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-slate-200">Total Invoiced</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-slate-200">Total Paid</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-slate-200">Returns</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-slate-200">Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($customers as $index => $item)
                        <tr class="hover:bg-slate-700/50">
                            <td class="px-6 py-4 text-sm text-slate-200">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm text-slate-200">{{ $item['customer']->name }}</td>
                            <td class="px-6 py-4 text-sm text-center text-slate-200">{{ $item['invoice_count'] }}</td>
                            <td class="px-6 py-4 text-sm text-right text-slate-200">${{ number_format($item['total_invoiced'], 2) }}</td>
                            <td class="px-6 py-4 text-sm text-right text-green-400">${{ number_format($item['total_paid'], 2) }}</td>
                            <td class="px-6 py-4 text-sm text-right text-red-400">${{ number_format($item['total_returns'], 2) }}</td>
                            <td class="px-6 py-4 text-sm text-right font-semibold {{ $item['balance'] > 0 ? 'text-red-400' : 'text-green-400' }}">
                                ${{ number_format($item['balance'], 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
