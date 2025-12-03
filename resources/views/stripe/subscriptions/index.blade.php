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
            <!-- Dynamic Flash Messages Container -->
            <div id="flash-messages"></div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

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

                                        <tr class="hover:bg-gray-50" data-subscription-id="{{ $subscription->id }}">
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
                                                <span class="status-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 next-billing-date">
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
                                                <div class="flex space-x-2 subscription-actions">
                                                    
                                                        
                                                    @if($subscription->canceled())
                                                        <button class="text-indigo-600 hover:text-indigo-900" onclick="resumeSubscription(event, '{{ $subscription->id }}')">
                                                            Resume
                                                        </button>
                                                    @else
                                                        <button class="text-red-600 hover:text-red-900" onclick="cancelSubscription(event, '{{ $subscription->id }}')">
                                                            Cancel
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
        const statusColors = {
            'active': 'bg-green-100 text-green-800',
            'trialing': 'bg-blue-100 text-blue-800',
            'past_due': 'bg-yellow-100 text-yellow-800',
            'canceled': 'bg-red-100 text-red-800',
            'unpaid': 'bg-red-100 text-red-800',
            'incomplete': 'bg-gray-100 text-gray-800',
            'incomplete_expired': 'bg-gray-100 text-gray-800',
            'paused': 'bg-orange-100 text-orange-800'
        };

        function showMessage(msg, type = 'success') {
            const el = document.getElementById('flash-messages');
            el.innerHTML = `<div class="mb-6 ${type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'} border rounded-md p-4">
                <p class="text-sm font-medium">${msg}</p>
            </div>`;
            setTimeout(() => el.innerHTML = '', 5000);
        }

        function updateRow(id, data) {
            const row = document.querySelector(`tr[data-subscription-id="${id}"]`);
            if (!row || !data) return;
            
            const status = data.stripe_status;
            const badge = row.querySelector('.status-badge');
            badge.className = `status-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusColors[status] || 'bg-gray-100 text-gray-800'}`;
            badge.textContent = status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            
            const dateCell = row.querySelector('.next-billing-date');
            if (dateCell) dateCell.textContent = data.ends_at || 'N/A';
            
            const actions = row.querySelector('.subscription-actions');
            if (actions) {
                actions.innerHTML = data.is_active 
                    ? `<button class="text-red-600 hover:text-red-900" onclick="cancelSubscription(event, '${id}')">Cancel</button>`
                    : `<button class="text-indigo-600 hover:text-indigo-900" onclick="resumeSubscription(event, '${id}')">Resume</button>`;
            }
        }

        function cancelSubscription(event, id) {
            if (!confirm('Are you sure you want to cancel this subscription? It will remain active until the end of the billing period.')) return;
            
            const btn = event.target;
            btn.disabled = true;
            btn.textContent = 'Canceling...';
            
            fetch(`/subscriptions/${id}/cancel`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                    updateRow(id, data.subscription);
                } else {
                    showMessage(data.message || 'Failed to cancel subscription', 'error');
                    btn.disabled = false;
                    btn.textContent = 'Cancel';
                }
            })
            .catch(() => {
                showMessage('An error occurred. Please try again.', 'error');
                btn.disabled = false;
                btn.textContent = 'Cancel';
            });
        }

        function resumeSubscription(event, id) {
            if (!confirm('Are you sure you want to resume this subscription?')) return;
            
            const btn = event.target;
            btn.disabled = true;
            btn.textContent = 'Resuming...';
            
            fetch(`/subscriptions/${id}/resume`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                    updateRow(id, data.subscription);
                } else {
                    showMessage(data.message || 'Failed to resume subscription', 'error');
                    btn.disabled = false;
                    btn.textContent = 'Resume';
                }
            })
            .catch(() => {
                showMessage('An error occurred. Please try again.', 'error');
                btn.disabled = false;
                btn.textContent = 'Resume';
            });
        }
    </script>
    @endpush
</x-app-layout>
