<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'title',
        'description',
        'priority',
        'status',
        'asset_id',
        'assigned_to',
        'reported_by',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function notes()
    {
        return $this->hasMany(IncidentNote::class)->orderBy('created_at');
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class, 'incident_assignees')->withTimestamps();
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'in_progress' => 'In Progress',
            'resolved' => 'Resolved',
            default => 'Open',
        };
    }
}
