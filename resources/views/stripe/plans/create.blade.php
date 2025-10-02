
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
                    <form action="/plans" method="POST" class="space-y-6">
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
                                required>
                        </div>

                        <!-- Amount Currency -->
                        <div class="space-y-2">
                            <label for="amount_currency" class="block text-sm font-medium text-gray-700">
                                Amount Currency
                            </label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-gray-500 sm:text-sm">
                                    $
                                </span>
                                <input type="number" 
                                    class="block w-full rounded-none rounded-r-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                    id="amount_currency" 
                                    name="amount_currency" 
                                    placeholder="Enter amount" 
                                    required>
                            </div>
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
                                required>
                        </div>

                        <!-- Billing Period -->
                        <div class="space-y-2">
                            <label for="billing_period" class="block text-sm font-medium text-gray-700">
                                Billing Period
                            </label>
                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                id="billing_period" 
                                name="billing_period" 
                                required>
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