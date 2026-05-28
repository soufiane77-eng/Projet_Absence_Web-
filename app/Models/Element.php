<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Element extends Model
{
    protected $fillable = [
        'name',
        'code',
        'module_id',
        'coefficient',
        'total_hours',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}
