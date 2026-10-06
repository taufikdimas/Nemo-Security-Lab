<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'address',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Akun portal yang terhubung ke klien ini.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'client_id');
    }

    /**
     * Aset milik klien ini. Dipakai untuk scoping insiden di portal.
     */
    public function assets()
    {
        return $this->hasMany(Asset::class, 'client_id');
    }
}