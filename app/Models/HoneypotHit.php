<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoneypotHit extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'method',
        'ip_address',
        'user_agent',
        'referer',
        'payload',
        'user_id',
        'severity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function severityLabel(): string
    {
        return ucfirst($this->severity);
    }
}
