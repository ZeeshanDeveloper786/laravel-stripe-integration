<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
      $intent = request()->user()->createSetupIntent();
    return view('dashboard', compact('intent'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/single-charge', [SubscriptionController::class,'singleCharge'])->name('single.charge');
    Route::get('/plans', [SubscriptionController::class,'listPlan'])->name('plans.index');
    Route::get('/plans/create-plan', [SubscriptionController::class,'createPlan'])->name('plans.create');
    
    // Route::get('/subscribe', 'SubscriptionController@showSubscription');
    //   Route::post('/subscribe', 'SubscriptionController@processSubscription');
    //   // welcome page only for subscribed users
    //   Route::get('/welcome', 'SubscriptionController@showWelcome')->middleware('subscribed');

});

require __DIR__.'/auth.php';
