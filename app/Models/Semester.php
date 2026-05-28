<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semester extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classe::class, 'level_semester', 'semester_id', 'classe_id')
            ->withPivot('order')
            ->withTimestamps();
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'current_semester_id');
    }
}
