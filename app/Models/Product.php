<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function category()     { return $this->belongsTo(Category::class); }
    public function manufacturer() { return $this->belongsTo(Manufacturer::class); }
    public function supplier()     { return $this->belongsTo(Supplier::class); }
    public function unit()         { return $this->belongsTo(Unit::class); }
    public function orderItems()   { return $this->hasMany(OrderItem::class); }

    
    public function getFinalPriceAttribute(): float
    {
        return round($this->price * (100 - $this->discount) / 100, 2);
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo && file_exists(public_path($this->photo))) {
            return asset($this->photo);
        }

        return asset('images/picture.png');
    }
}
