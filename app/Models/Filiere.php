<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Filiere extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'coordinator_id',
    ];

    public function classes(): HasMany
    {
        return $this->hasMany(Classe::class);
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }
}
