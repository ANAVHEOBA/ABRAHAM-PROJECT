<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'current_latitude',
        'current_longitude',
        'destination_latitude',
        'destination_longitude',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}