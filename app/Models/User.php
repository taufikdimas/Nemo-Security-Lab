<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'client_id',
        'avatar',
        'bio',
        'department',
        'position',
        'phone',
        'address',
        'is_active',
        'api_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function files()
    {
        return $this->hasMany(File::class, 'uploaded_by');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'created_by');
    }

    public function assignedIncidents()
    {
        return $this->hasMany(Incident::class, 'assigned_to');
    }

    public function reportedIncidents()
    {
        return $this->hasMany(Incident::class, 'reported_by');
    }

    public static function generateApiToken(string $email, $createdAt): string
    {
        return md5($email . Carbon::parse($createdAt)->format('Y-m-d H:i:s'));
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Akun portal klien. Peran ini tidak membawa privilege admin apa pun —
     * hanya akses ke /portal/* milik kliennya sendiri.
     */
    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function clientProfile(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Employee::class, 'email', 'email');
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
                return $this->avatar;
            }
            return asset('storage/' . $this->avatar);
        }

        $emp = $this->employee;
        if ($emp && $emp->photo) {
            if (str_starts_with($emp->photo, 'http://') || str_starts_with($emp->photo, 'https://')) {
                return $emp->photo;
            }
            return asset('storage/' . $emp->photo);
        }

        $bg = match($this->role) {
            'admin' => 'e11d48',
            'client' => '0284c7',
            default => '2563eb'
        };

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=' . $bg . '&color=ffffff&size=128&bold=true';
    }

    public function isActive()
    {
        return $this->is_active;
    }
}