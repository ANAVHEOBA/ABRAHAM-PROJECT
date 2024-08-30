<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_COMPLETED = 'completed';

    // Fillable attributes
    protected $fillable = [
        'user_id',
        'pickup_location',
        'delivery_location',
        'status',
        'pilot_id',
        'total_amount',
        'total', // You can choose to use either 'total_amount' or 'total', or keep both if necessary
    ];

    // Cast attributes
    protected $casts = [
        'total_amount' => 'decimal:2',
        'total' => 'decimal:2', // If you want to keep both attributes
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pilot()
    {
        return $this->belongsTo(Pilot::class);
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

