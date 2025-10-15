<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Single Charges') }}
        </h2>
    </x-slot>

    {{-- stripe style start--}}
    @push('styles')
    <style>
        .StripeElement {
            background-color: white;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid transparent;
            box-shadow: 0 1px 3px 0 #e6ebf1;
            transition: box-shadow 150ms ease;
        }
        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }
        .StripeElement--invalid {
            border-color: #fa755a;
        }      
    </style>
    {{-- stripe style end--}}
    @endpush
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- stripe form start --}}
                    <form action="{{ route('plans.process-checkout') }}" method="POST" id="subscribe-form" class="space-y-6">
                        @csrf
                       
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-lg font-semibold text-blue-800">
                                Your subscription is {{ strtoupper($plan->name) }} <span style="float: right;">{{strtoupper($plan->currency)}} {{$plan->price/100}}</span>
                            </p>
                        </div>
                        <!-- here we only include plan_id and dont need to have amount field because stripe will handle the amount -->
                        <input type="hidden" name="stripe_plan_id" value="{{$plan->stripe_plan_id}}">
                     
                        <!-- Card Holder Name Field -->
                        <div class="space-y-2">
                            <label for="card-holder-name" class="block text-sm font-medium text-gray-700">Card Holder Name</label>
                            <input id="card-holder-name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Enter Card Holder Name"required>
                        </div>


                        <!-- Card Element -->
                        <div class="space-y-2">
                            <label for="card-element" class="block text-sm font-medium text-gray-700">Credit or debit card</label>
                            <div id="card-element" class="form-control p-3 border border-gray-300 rounded-md">
                            </div>
                            <!-- Used to display form errors. -->
                            <div id="card-errors" role="alert" class="text-red-600 text-sm mt-1"></div>
                        </div>

                        <div class="stripe-errors"></div>

                        <!-- Error Messages -->
                        @if (count($errors) > 0)
                        <div class="bg-red-50 border border-red-200 rounded-md p-4">
                            <div class="text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Submit Button -->
                        <div class="text-center">
                            <button id="card-button" 
                                data-secret="{{ $intent->client_secret }}" 
                                class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                SUBMIT PAYMENT
                            </button>
                        </div>
                    </form>
                    {{-- stripe form end --}}
                    
                    {{-- stripe script start --}}
                    @push('scripts')
                            <script src="https://js.stripe.com/v3/"></script>
                            <script>
                                var stripe = Stripe('{{ env('STRIPE_KEY') }}');
                                var elements = stripe.elements();
                                var style = {
                                    base: {
                                        color: '#32325d',
                                        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                                        fontSmoothing: 'antialiased',
                                        fontSize: '16px',
                                        '::placeholder': {
                                            color: '#aab7c4'
                                        }
                                    },
                                    invalid: {
                                        color: '#fa755a',
                                        iconColor: '#fa755a'
                                    }
                                };
                                var card = elements.create('card', {hidePostalCode: true,
                                    style: style});
                                card.mount('#card-element');
                                card.addEventListener('change', function(event) {
                                    var displayError = document.getElementById('card-errors');
                                    if (event.error) {
                                        displayError.textContent = event.error.message;
                                    } else {
                                        displayError.textContent = '';
                                    }
                                });
                                const cardHolderName = document.getElementById('card-holder-name');
                                const cardButton = document.getElementById('card-button');
                                const clientSecret = cardButton.dataset.secret;
                                cardButton.addEventListener('click', async (e) => {
                                    e.preventDefault();
                                    console.log("attempting");
                                    const { setupIntent, error } = await stripe.confirmCardSetup(
                                        clientSecret, {
                                            payment_method: {
                                                card: card,
                                                billing_details: { name: cardHolderName.value }
                                            }
                                        }
                                        );
                                    if (error) {
                                        var errorElement = document.getElementById('card-errors');
                                        errorElement.textContent = error.message;
                                    } else {
                                        paymentMethodHandler(setupIntent.payment_method);
                                    }
                                });
                                function paymentMethodHandler(payment_method) {
                                    var form = document.getElementById('subscribe-form');
                                    var hiddenInput = document.createElement('input');
                                    hiddenInput.setAttribute('type', 'hidden');
                                    hiddenInput.setAttribute('name', 'payment_method');
                                    hiddenInput.setAttribute('value', payment_method);
                                    form.appendChild(hiddenInput);
                                    form.submit();
                                }
                            </script>
                    @endpush
                    {{-- stripe script end --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
