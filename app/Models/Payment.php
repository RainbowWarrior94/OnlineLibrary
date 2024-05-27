<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'borrow_id',
        'payment_amount',
        'payment_date',
    ];

    public function borrow()
    {
        return $this->belongsTo(Borrow::class);
    }
}
