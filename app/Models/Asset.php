<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'hostname',
        'ip_address',
        'asset_type',
        'os_version',
        'owner_department',
        'client_id',
        'last_scan_date',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'last_scan_date' => 'date',
        ];
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function openIncidents()
    {
        return $this->hasMany(Incident::class)->whereIn('status', ['open', 'in_progress']);
    }

    /**
     * Days elapsed since the last authorised scan. Assets that have never been
     * scanned are reported separately so the inventory can flag them apart from
     * assets whose scan has simply gone stale.
     */
    public function scanAgeDays(): ?int
    {
        if (! $this->last_scan_date) {
            return null;
        }

        return (int) $this->last_scan_date->startOfDay()->diffInDays(now()->startOfDay());
    }

    public function scanFreshnessLabel(): string
    {
        $days = $this->scanAgeDays();

        return match (true) {
            $days === null => 'Belum pernah dipindai',
            $days <= 30 => 'Segar',
            $days <= 90 => 'Perlu Diperbarui',
            default => 'Kritis',
        };
    }

    public function scanFreshnessClass(): string
    {
        $days = $this->scanAgeDays();

        return match (true) {
            $days === null => 'bg-secondary',
            $days <= 30 => 'bg-success',
            $days <= 90 => 'bg-warning text-dark',
            default => 'bg-danger',
        };
    }
}
