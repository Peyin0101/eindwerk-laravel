<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'voornaam',
        'naam',
        'straat',
        'huisnummer',
        'postcode',
        'woonplaats',
        'payment_method',
        'discount_code_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('quantity', 'size')
            ->withTimestamps();
    }
}
