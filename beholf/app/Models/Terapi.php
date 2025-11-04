<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Terapi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_user',
        'name',
        'spesialis',
        'jenis_kelamin',
        'no_hp',
        'kode_terapi',
        'deskripsi',
        'foto',
        'metode_terapi',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'id_terapi');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'kode_terapi', 'kode_terapi');
    }



    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
