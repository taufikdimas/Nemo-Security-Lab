<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public const DEPARTMENTS = [
        'Security Operations Center (SOC)',
        'Offensive Security (Red Team)',
        'Security Engineering & Architecture',
        'Digital Forensics & Incident Response (DFIR)',
        'Governance, Risk & Compliance (GRC)',
        'Information Security Management',
    ];

    public const POSITIONS = [
        'Security Operations Center (SOC)' => [
            'SOC Analyst',
            'Incident Responder',
            'Threat Hunter',
            'SOC Lead',
        ],
        'Offensive Security (Red Team)' => [
            'Penetration Tester',
            'Red Team Operator',
            'Vulnerability Researcher',
            'Offensive Security Lead',
        ],
        'Security Engineering & Architecture' => [
            'Security Engineer',
            'Network Security Engineer',
            'Cloud Security Engineer',
            'DevSecOps Engineer',
        ],
        'Digital Forensics & Incident Response (DFIR)' => [
            'Digital Forensics Analyst',
            'Malware Analyst',
            'DFIR Specialist',
        ],
        'Governance, Risk & Compliance (GRC)' => [
            'GRC Analyst',
            'Security Auditor',
            'Compliance Specialist',
        ],
        'Information Security Management' => [
            'Information Security Manager',
            'Security Project Manager',
            'CISO / Head of Security',
        ],
    ];

    protected $fillable = [
        'employee_number',
        'name',
        'email',
        'department',
        'position',
        'phone',
        'photo',
        'joined_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'joined_at' => 'date',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->photo) {
            if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://')) {
                return $this->photo;
            }
            return asset('storage/' . $this->photo);
        }

        $usr = $this->user;
        if ($usr && $usr->avatar) {
            if (str_starts_with($usr->avatar, 'http://') || str_starts_with($usr->avatar, 'https://')) {
                return $usr->avatar;
            }
            return asset('storage/' . $usr->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=1e293b&color=38bdf8&size=128&bold=true';
    }

    public static function allPositions(): array
    {
        $all = [];
        foreach (self::POSITIONS as $positions) {
            foreach ($positions as $pos) {
                $all[] = $pos;
            }
        }
        return array_unique($all);
    }
}