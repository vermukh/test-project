<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'order_date'    => 'date:d.m.Y',
        'delivery_date' => 'date:d.m.Y',
    ];

    public function status()      { return $this->belongsTo(OrderStatus::class, 'status_id'); }
    public function pickupPoint() { return $this->belongsTo(PickupPoint::class); }
    public function client()      { return $this->belongsTo(User::class, 'user_id'); }
    public function items()       { return $this->hasMany(OrderItem::class); }

    // "Артикул заказа" в формате исходных данных: АРТИКУЛ, кол-во, АРТИКУЛ, кол-во
    public function getCompositionAttribute(): string
    {
        return $this->items
            ->map(fn ($i) => $i->product->article . ', ' . $i->quantity)
            ->implode(', ');
    }
}
