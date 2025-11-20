<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Subscriptions') }}
            </h2>
            <a href="{{ route('plans.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Browse Plans
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($subscriptions->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Plan Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Subscription Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Billing Period
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Next Billing
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($subscriptions as $subscription)

                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $subscription->plan->name ?? $subscription->name ?? 'Unknown Plan' }}
                                                </div>                                           
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $subscription->name ?? 'Default Subscription' }}
                                                </div>                                               
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'active' => 'bg-green-100 text-green-800',
                                                        'trialing' => 'bg-blue-100 text-blue-800',
                                                        'past_due' => 'bg-yellow-100 text-yellow-800',
                                                        'canceled' => 'bg-red-100 text-red-800',
                                                        'unpaid' => 'bg-red-100 text-red-800',
                                                        'incomplete' => 'bg-gray-100 text-gray-800',
                                                        'incomplete_expired' => 'bg-gray-100 text-gray-800',
                                                        'paused' => 'bg-orange-100 text-orange-800'
                                                    ];
                                                    $statusColor = $statusColors[$subscription->stripe_status] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                                    {{ ucfirst(str_replace('_', ' ', $subscription->stripe_status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    ${{ number_format($subscription->plan->price, 2) }}
                                                </div>                                          
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ ucfirst($subscription->plan->billing_method) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @php
                                                    if($subscription->canceled() && $subscription->ends_at) {
                                                        // If canceled, show when it ends
                                                        $nextBilling = $subscription->ends_at;
                                                    } elseif($subscription->currentPeriodEnd()) {
                                                        // If active, show next billing date
                                                        $nextBilling = $subscription->currentPeriodEnd();
                                                    } elseif($subscription->trial_ends_at) {
                                                        // If on trial, show trial end date
                                                        $nextBilling = $subscription->trial_ends_at;
                                                    } else {
                                                        $nextBilling = null;
                                                    }
                                                @endphp
                                                @if($nextBilling)
                                                    {{ $nextBilling->format('M d, Y') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    @if($subscription->active())
                                                        <button class="text-red-600 hover:text-red-900" onclick="cancelSubscription('{{ $subscription->id }}')">
                                                            Cancel
                                                        </button>
                                                    @elseif($subscription->canceled())
                                                        <button class="text-indigo-600 hover:text-indigo-900" onclick="resumeSubscription('{{ $subscription->id }}')">
                                                            Resume
                                                        </button>
                                                    @endif
                                                   
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No subscriptions found</h3>
                    <p class="text-gray-500 mb-6">You don't have any active subscriptions yet.</p>
                    <a href="{{ route('plans.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Browse Available Plans
                    </a>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function cancelSubscription(subscriptionId) {
            if (confirm('Are you sure you want to cancel this subscription?')) {
                // Add your cancellation logic here
                console.log('Canceling subscription:', subscriptionId);
            }
        }

        function resumeSubscription(subscriptionId) {
            if (confirm('Are you sure you want to resume this subscription?')) {
                // Add your resume logic here
                console.log('Resuming subscription:', subscriptionId);
            }
        }
    </script>
    @endpush
</x-app-layout>
