@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-slate-50">Edit Customer</h1>
            <a href="{{ route('customers.index') }}"
                class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg text-slate-200 transition">
                Back
            </a>
        </div>

        <div class="bg-slate-800 rounded-2xl p-8 shadow-lg">
            <form method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-slate-300 text-sm font-medium mb-2">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}" required
                        class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-50 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    @error('name')
                        <div class="mt-1 text-sm text-red-400">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-slate-300 text-sm font-medium mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}" required
                        class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-50 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    @error('email')
                        <div class="mt-1 text-sm text-red-400">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-slate-300 text-sm font-medium mb-2">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}"
                        class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-50 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>

                <div>
                    <label for="address" class="block text-slate-300 text-sm font-medium mb-2">Address</label>
                    <textarea name="address" id="address" rows="3"
                        class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-50 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">{{ old('address', $customer->address) }}</textarea>
                </div>

                <button type="submit"
                    class="cursor-pointer w-full py-3 rounded-lg text-white font-semibold text-sm gradient-primary hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    Update Customer
                </button>
            </form>
        </div>
    </div>
@endsection
