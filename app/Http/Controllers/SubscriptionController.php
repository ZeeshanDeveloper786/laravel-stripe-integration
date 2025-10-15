<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;
use \Stripe\Stripe;
use App\Models\Plan;

class SubscriptionController extends Controller
{
    public function singleCharge(Request $request){        
        $amount = $request->amount;
        $amount = $amount * 100; // amount in cents
        $payment_method = $request->payment_method;

        $user = auth()->user();
        $user->createOrGetStripeCustomer();

        $payment_method = $user->addPaymentMethod($payment_method);


        $user->charge(
            $amount, $payment_method->id,[
                'payment_method_types' => ['card'],
            ]
        );

        return back()->with('success','payment has been made successfully');
    }

    public function listPlan(){
        $plans = Plan::paginate(10);
        return view('stripe.plans.index', compact('plans'));
    }


    public function createPlan() {
        return view('stripe.plans.create');
    }

    public function storePlan(Request $request) {
        $request->validate([
            'plan_name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'currency' => 'required|string|size:3',
            'interval_count' => 'nullable|integer|min:1',
            'billing_period' => 'required|in:day,week,month,year',
        ]);
        
        
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            // Create the product
            $product = \Stripe\Product::create([
                'name' => $request->plan_name,
            ]);

            // Create the plan
            $plan = \Stripe\Plan::create([
                'amount' => $request->amount * 100, // amount in cents
                'currency' => $request->currency,
                'interval' => $request->billing_period,
                'product' => $product->id,
                'interval_count' => $request->interval_count
            ]);

            // Store plan details in your database
            Plan::create([
                'stripe_plan_id' => $plan->id,
                'name' => $request->plan_name,
                'price' => $plan->amount,
                'billing_method' => $plan->interval,
                'currency' => $plan->currency,
                'interval_count' => $plan->interval_count
            ]);

        } catch (\Exception $e) {
            throw $e;
        }
        return redirect()->route('plans.index')->with('success', 'Plan created successfully.'); 

    }

    public function checkout($stripe_plan_id) {
        $plan = Plan::where('stripe_plan_id', $stripe_plan_id)->first();
        if(!$plan) {
            return back()->withErrors(['message' => 'Plan not found.']);
        }

        
        
        return view('stripe.plans.checkout', [
            'intent' => auth()->user()->createSetupIntent(),
            'plan' => $plan,
        ]);
    }

    public function processCheckout(Request $request) {             
        
        try {
            $user = Auth::user();
            $user->createOrGetStripeCustomer();
            if(isset($request->payment_method)){
                $payment_method =$user->addPaymentMethod($request->payment_method);
            }

            $user->newSubscription('default', $request->stripe_plan_id)->create($payment_method ? $payment_method->id : null);

        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Error creating subscription. ' . $e->getMessage()]);
        }

        
        return back()->with('success', 'Subscription created successfully');
    }

//      public function retrievePlans() {
//        $key = \config('services.stripe.secret');
//        $stripe = new \Stripe\StripeClient($key);
//        $plansraw = $stripe->plans->all();
//        $plans = $plansraw->data;
       
//        foreach($plans as $plan) {
//            $prod = $stripe->products->retrieve(
//                $plan->product,[]
//            );
//            $plan->product = $prod;
//        }
//        return $plans;
//    }
//    public function showSubscription() {
//        $plans = $this->retrievePlans();
//        $user = Auth::user();
       
//        return view('seller.pages.subscribe', [
//            'user'=>$user,
//            'intent' => $user->createSetupIntent(),
//            'plans' => $plans
//        ]);
//    }
//    public function processSubscription(Request $request)
//    {
//        $user = Auth::user();
//        $paymentMethod = $request->input('payment_method');
                   
//        $user->createOrGetStripeCustomer();
//        $user->addPaymentMethod($paymentMethod);
//        $plan = $request->input('plan');
//        try {
//            $user->newSubscription('default', $plan)->create($paymentMethod, [
//                'email' => $user->email
//            ]);
//        } catch (\Exception $e) {
//            return back()->withErrors(['message' => 'Error creating subscription. ' . $e->getMessage()]);
//        }
       
//        return redirect('dashboard');
//    }
}
