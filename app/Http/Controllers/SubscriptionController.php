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
