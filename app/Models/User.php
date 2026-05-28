<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'plain_password',
        'role',
        'avatar',
        'phone',
        'is_active',
        'login_attempts',
        'locked_until',
        'last_login_ip',
        'last_login_at',
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
            'locked_until' => 'datetime',
            'login_attempts' => 'integer',
            'last_login_at' => 'datetime',
        ];
    }

    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function seances()
    {
        return $this->hasMany(Seance::class, 'teacher_id');
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'teacher_module')
            ->withPivot(['class_id', 'type'])
            ->withTimestamps();
    }

    public function markedAbsences()
    {
        return $this->hasMany(Absence::class, 'marked_by');
    }
}
