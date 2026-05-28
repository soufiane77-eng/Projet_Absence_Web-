<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Absence extends Model
{
    protected $fillable = [
        'student_id',
        'seance_id',
        'module_id',
        'marked_by',
        'status',
        'is_justified',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_justified' => 'boolean',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function seance(): BelongsTo
    {
        return $this->belongsTo(Seance::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function marker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    public function justification(): HasOne
    {
        return $this->hasOne(Justification::class);
    }

    public function scopeUnjustified($query)
    {
        return $query->where('is_justified', false)
            ->where('status', 'absent');
    }
}
