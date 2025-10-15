<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Choose Your Plan') }}
            </h2>
            <a href="{{ route('plans.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Create New Plan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Error
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
    

            @if($plans->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($plans as $index => $plan)
                        @php
                            $colors = [
                                ['bg' => 'bg-gradient-to-br from-blue-500 to-blue-600', 'text' => 'text-blue-600', 'border' => 'border-blue-200', 'button' => 'bg-blue-600 hover:bg-blue-700'],
                                ['bg' => 'bg-gradient-to-br from-purple-500 to-purple-600', 'text' => 'text-purple-600', 'border' => 'border-purple-200', 'button' => 'bg-purple-600 hover:bg-purple-700'],
                                ['bg' => 'bg-gradient-to-br from-green-500 to-green-600', 'text' => 'text-green-600', 'border' => 'border-green-200', 'button' => 'bg-green-600 hover:bg-green-700'],
                                ['bg' => 'bg-gradient-to-br from-orange-500 to-orange-600', 'text' => 'text-orange-600', 'border' => 'border-orange-200', 'button' => 'bg-orange-600 hover:bg-orange-700'],
                                ['bg' => 'bg-gradient-to-br from-pink-500 to-pink-600', 'text' => 'text-pink-600', 'border' => 'border-pink-200', 'button' => 'bg-pink-600 hover:bg-pink-700'],
                                ['bg' => 'bg-gradient-to-br from-indigo-500 to-indigo-600', 'text' => 'text-indigo-600', 'border' => 'border-indigo-200', 'button' => 'bg-indigo-600 hover:bg-indigo-700']
                            ];
                            $colorScheme = $colors[$index % count($colors)];
                            
                            // Calculate pricing display
                            $basePrice = $plan->price / 100;
                            $currencySymbol = match($plan->currency) {
                                'usd' => '$',
                                'eur' => '€',
                                'gbp' => '£',
                                'cad' => 'C$',
                                'aud' => 'A$',
                                'jpy' => '¥',
                                'inr' => '₹',
                                default => '$'
                            };
                            
                            $intervalText = match($plan->billing_method) {
                                'day' => 'day',
                                'week' => 'week',
                                'month' => 'month',
                                'year' => 'year',
                                default => 'month'
                            };
                            
                            $intervalCount = $plan->interval_count ?? 1;
                            $displayPrice = $basePrice;
                            $billingText = $intervalCount > 1 ? "every {$intervalCount} {$intervalText}s" : "per {$intervalText}";
                            
                            // Generate dummy features based on plan
                            $features = [
                                'Basic' => ['Up to 5 projects', '1GB storage', 'Email support', 'Basic analytics'],
                                'Standard' => ['Up to 15 projects', '10GB storage', 'Priority support', 'Advanced analytics', 'API access'],
                                'Premium' => ['Unlimited projects', '100GB storage', '24/7 support', 'Premium analytics', 'Full API access', 'Custom integrations'],
                                'Enterprise' => ['Unlimited everything', '1TB storage', 'Dedicated support', 'Custom analytics', 'White-label options', 'SLA guarantee']
                            ];
                            
                            $planType = match(true) {
                                $basePrice < 10 => 'Basic',
                                $basePrice < 50 => 'Standard', 
                                $basePrice < 100 => 'Premium',
                                default => 'Enterprise'
                            };
                            
                            $planFeatures = $features[$planType] ?? $features['Basic'];
                        @endphp
                        
                        <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden border-2 {{ $colorScheme['border'] }} hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col h-full">
                            <!-- Popular Badge -->
                            @if($index === 1)
                                <div class="absolute top-0 right-0 {{ $colorScheme['bg'] }} text-white px-4 py-1 text-sm font-semibold rounded-bl-lg">
                                    Most Popular
                                </div>
                            @endif
                            
                            <!-- Header -->
                            <div class="{{ $colorScheme['bg'] }} p-8 text-white text-center">
                                <h3 class="text-2xl font-bold mb-2">{{ $plan->name }}</h3>
                                <div class="text-4xl font-bold mb-2">
                                    {{ $currencySymbol }}{{ number_format($displayPrice, 2) }}
                                </div>
                                <p class="text-blue-100">{{ $billingText }}</p>
                            </div>
                            
                            <!-- Features -->
                            <div class="p-8 flex flex-col flex-1">
                                <ul class="space-y-4 mb-8">
                                    @foreach($planFeatures as $feature)
                                        <li class="flex items-center">
                                            <svg class="w-5 h-5 {{ $colorScheme['text'] }} mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-gray-700">{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                
                                <!-- Checkout Button -->
                                <a href="{{ route('plans.checkout', $plan->stripe_plan_id) }}" class="mt-auto w-full inline-flex items-center justify-center {{ $colorScheme['button'] }} text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Choose Plan
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($plans->hasPages())
                    <div class="mt-12 flex justify-center">
                        {{ $plans->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No plans available</h3>
                    <p class="text-gray-500 mb-6">Get started by creating your first subscription plan.</p>
                    <a href="{{ route('plans.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Create Your First Plan
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>