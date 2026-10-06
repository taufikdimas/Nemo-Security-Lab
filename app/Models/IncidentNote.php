<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_id',
        'user_id',
        'note',
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
