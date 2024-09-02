<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    const TYPE_PAYMENT = 'payment';
    const TYPE_REFUND = 'refund';
    const TYPE_PAYOUT = 'payout';

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'user_id',
        'pilot_id',
        'amount',
        'type',
        'status',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pilot()
    {
        return $this->belongsTo(Pilot::class);
    }
}