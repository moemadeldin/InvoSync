@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-slate-50">Customer Details</h1>
            <a href="{{ route('customers.index') }}"
                class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg text-slate-200 transition">
                Back
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg px-4 py-3 mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-slate-800 rounded-2xl p-8 shadow-lg">
            <div class="space-y-4">
                <div>
                    <label class="block text-slate-400 text-xs font-medium uppercase tracking-wide mb-1">Name</label>
                    <p class="text-slate-50 text-lg">{{ $customer->name }}</p>
                </div>
                <div>
                    <label class="block text-slate-400 text-xs font-medium uppercase tracking-wide mb-1">Email</label>
                    <p class="text-slate-50 text-lg">{{ $customer->email }}</p>
                </div>
                <div>
                    <label class="block text-slate-400 text-xs font-medium uppercase tracking-wide mb-1">Phone</label>
                    <p class="text-slate-50 text-lg">{{ $customer->phone ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-slate-400 text-xs font-medium uppercase tracking-wide mb-1">Address</label>
                    <p class="text-slate-50 text-lg">{{ $customer->address ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-slate-400 text-xs font-medium uppercase tracking-wide mb-1">Created At</label>
                    <p class="text-slate-50 text-lg">{{ $customer->created_at->format('Y-m-d H:i:s') }}</p>
                </div>
            </div>

            <div class="mt-8 flex gap-4">
                <a href="{{ route('customers.edit', $customer) }}"
                    class="px-6 py-3 rounded-lg text-white font-semibold text-sm bg-yellow-600 hover:bg-yellow-500 transition-all">
                    Edit
                </a>
                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="cursor-pointer px-6 py-3 rounded-lg text-white font-semibold text-sm bg-red-600 hover:bg-red-500 transition-all"
                        onclick="return confirm('Are you sure?')">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
