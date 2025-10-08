
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Plan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('plans.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <!-- Plan Name -->
                        <div class="space-y-2">
                            <label for="plan_name" class="block text-sm font-medium text-gray-700">
                                Plan Name
                            </label>
                            <input type="text" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                id="plan_name" 
                                name="plan_name" 
                                placeholder="Enter plan name" 
                                >
                        </div>

                        <!-- Amount -->
                        <div class="space-y-2">
                            <label for="amount" class="block text-sm font-medium text-gray-700">
                                Amount
                            </label>
                            <input type="number" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                id="amount" 
                                name="amount" 
                                placeholder="Enter amount" 
                                >
                        </div>

                        <!-- Currency -->
                        <div class="space-y-2">
                            <label for="currency" class="block text-sm font-medium text-gray-700">
                                Currency
                            </label>
                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                id="currency" 
                                name="currency" 
                                >
                                <option value="" disabled selected>Select currency</option>
                                <option value="usd">USD ($)</option>
                                <option value="eur">EUR (€)</option>
                                <option value="gbp">GBP (£)</option>
                                <option value="cad">CAD (C$)</option>
                                <option value="aud">AUD (A$)</option>
                                <option value="jpy">JPY (¥)</option>
                                <option value="inr">INR (₹)</option>
                            </select>
                        </div>

                        <!-- Interval Count -->
                        <div class="space-y-2">
                            <label for="interval_count" class="block text-sm font-medium text-gray-700">
                                Interval Count
                            </label>
                            <input type="number" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                id="interval_count" 
                                name="interval_count" 
                                placeholder="Enter interval count" 
                                >
                        </div>

                        <!-- Billing Period -->
                        <div class="space-y-2">
                            <label for="billing_period" class="block text-sm font-medium text-gray-700">
                                Billing Period
                            </label>
                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                id="billing_period" 
                                name="billing_period" 
                                >
                                <option value="" disabled selected>Select billing period</option>
                                <option value="week">Week</option>
                                <option value="month">Month</option>
                                <option value="year">Year</option>
                            </select>
                        </div>

                        @if (count($errors) > 0)
                        <div class="rounded-md bg-red-50 p-4">
                            <div class="text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" 
                                class="btn btn-primary">
                                submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>