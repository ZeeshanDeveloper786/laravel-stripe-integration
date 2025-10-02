<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{

    protected $fillable = [
        "stripe_plan_id",
        "name",
        "billing_method",
        "description",
        "interval_count",
        "price",
        "currency"
    ];
}
