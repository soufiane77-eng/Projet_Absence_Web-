<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classe extends Model
{
    protected $fillable = [
        'name',
        'code',
        'filiere_id',
        'level',
        'student_count',
    ];

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class);
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class, 'class_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function seances(): HasMany
    {
        return $this->hasMany(Seance::class, 'class_id');
    }

    public function teacherModules(): HasMany
    {
        return $this->hasMany(TeacherModule::class, 'class_id');
    }

    public function semesters(): BelongsToMany
    {
        return $this->belongsToMany(Semester::class, 'level_semester', 'classe_id', 'semester_id')
            ->withPivot('order')
            ->withTimestamps()
            ->orderByPivot('order');
    }
}
