<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VulnDb extends Model
{
    use HasFactory;

    protected $fillable = [
        'cve_id',
        'name',
        'description',
        'severity',
        'cvss_score',
        'category',
        'affected_systems',
        'published_year',
        'remediation',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'cvss_score' => 'float',
        ];
    }

    public function severityLabel(): string
    {
        return match ($this->severity) {
            'critical' => 'Critical',
            'high' => 'High',
            'medium' => 'Medium',
            default => 'Low',
        };
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}