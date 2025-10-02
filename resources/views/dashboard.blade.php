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
                    <form action="{{ route('single.charge') }}" method="POST" id="subscribe-form">
                        {{-- <div class="form-group">
                            <div class="row">
                                @foreach($plans as $plan)
                                <div class="col-md-4">
                                    <div class="subscription-option">
                                        <input type="radio" id="plan-silver" name="plan" value='{{$plan->id}}'>
                                        <label for="plan-silver">
                                            <span class="plan-price">{{$plan->currency}}{{$plan->amount/100}}<small> /{{$plan->interval}}</small></span>
                                            <span class="plan-name">{{$plan->product->name}}</span>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div> --}}
                        <label for="card-holder-name">Amount</label>
                        <input type="number" name="amount" placeholder="Enter Amount" id="amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" ><br>
                        <label for="card-holder-name">Card Holder Name</label>
                        <input id="card-holder-name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Enter Card Holder Name">
                        @csrf
                        <div class="form-row">
                            <label for="card-element">Credit or debit card</label>
                            <div id="card-element" class="form-control">
                            </div>
                            <!-- Used to display form errors. -->
                            <div id="card-errors" role="alert"></div>
                        </div>
                        <div class="stripe-errors"></div>
                        @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                            @endforeach
                        </div>
                        @endif
                        
                        <div class="form-group text-center ">
                            <button  id="card-button" data-secret="{{ $intent->client_secret }}" class="btn btn-lg btn-primary mt-3">SUBMIT</button>
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
