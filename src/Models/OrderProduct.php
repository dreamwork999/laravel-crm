<?php

namespace VentureDrake\LaravelCrm\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use VentureDrake\LaravelCrm\Traits\BelongsToTeams;

class OrderProduct extends Model
{
    use SoftDeletes;
    use BelongsToTeams;

    protected $guarded = ['id'];

    public function getTable()
    {
        return config('laravel-crm.db_table_prefix').'order_products';
    }

    public function order()
    {
        return $this->belongsTo(\VentureDrake\LaravelCrm\Models\Order::class);
    }

    public function product()
    {
        return $this->belongsTo(\VentureDrake\LaravelCrm\Models\Product::class);
    }

    public function productVariation()
    {
        return $this->belongsTo(\VentureDrake\LaravelCrm\Models\ProductVariation::class);
    }
}
