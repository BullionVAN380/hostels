<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Room;
use App\Models\Payment;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'check_in',
        'check_out',
        'nights',
        'total_amount',
        'status'
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            // Calculate nights if not set
            if (!$booking->nights) {
                $booking->nights = Carbon::parse($booking->check_in)
                    ->diffInDays(Carbon::parse($booking->check_out));
            }
            
            // Calculate total amount if not set
            if (!$booking->total_amount) {
                $booking->total_amount = $booking->nights * $booking->room->price_per_night;
            }

            // Set default status if not set
            if (!$booking->status) {
                $booking->status = 'pending';
            }
        });
    }
}
