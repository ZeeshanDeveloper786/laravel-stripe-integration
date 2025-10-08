<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_plan_id');
            $table->string('name');
            $table->string('billing_method'); // per seat, per user
            $table->string('description')->nullable();
            $table->string('interval_count')->default(1); // monthly, yearly
            $table->string('price');
            $table->string('currency')->default('usd');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
