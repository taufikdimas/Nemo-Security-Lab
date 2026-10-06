<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'client_name',
        'start_date',
        'end_date',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    /**
     * Project completion is derived from elapsed calendar time between the
     * agreed start and end dates, so the progress bar always reflects the
     * real schedule instead of a manually typed percentage.
     */
    public function progressPercent(): int
    {
        if (! $this->start_date || ! $this->end_date) {
            return 0;
        }

        $total = $this->start_date->diffInDays($this->end_date);

        if ($total <= 0) {
            return 100;
        }

        return (int) min(100, max(0, round($this->start_date->diffInDays(now()) / $total * 100)));
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments()
    {
        return $this->hasMany(ProjectComment::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')->withTimestamps();
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%")
                     ->orWhere('description', 'LIKE', "%{$search}%")
                     ->orWhere('client_name', 'LIKE', "%{$search}%");
    }
}