<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    // Disable updated_at timestamp because migration only has created_at
    public $timestamps = false;

    protected $fillable = [
        'kode_booking',
        'tanggal_booking',
        'id_user',
        'riwayat_penyakit',
        'kode_terapi',
        'jadwal',
        'biaya_layanan',
        'status',
        'keluhan',
        'created_at',
        'created_by',
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
        'created_at' => 'datetime',
    ];

    // Relations

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function terapi()
    {
        return $this->belongsTo(Terapi::class, 'kode_terapi', 'kode_terapi');
    }
public function pembayaran()
{
    return $this->hasOne(Pembayaran::class, 'kode_booking', 'kode_booking');
}

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal');
    }
}
