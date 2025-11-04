<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    // Disable updated_at because your table only has created_at timestamp
    public $timestamps = false;

    // Define the created_at field as timestamp manually
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'nomor_pembayaran',
        'kode_booking',
        'grand_total',
        'metode_pembayaran',
        'foto_pembayaran',
        'status',
        'created_at',
        'created_by',
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'kode_booking', 'kode_booking');
    }
}
