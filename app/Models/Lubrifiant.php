<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lubrifiant extends Model
{
    /** @use HasFactory<\Database\Factories\LubrifiantFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'typelubrifiant_id',
    ];

    public function typelubrifiant(): BelongsTo
    {
        return $this->belongsTo(Typelubrifiant::class);
    }

    public function saisielubrifiant(): HasMany
    {
        return $this->hasMany(Saisielubrifiant::class);
    }
}
