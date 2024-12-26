<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Engin extends Model
{
    /** @use HasFactory<\Database\Factories\EnginFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'parc_id',
        'site_id',
    ];

    public function parc(): BelongsTo
    {
        return $this->belongsTo(Parc::class)->with('typeparc');
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function saisierje(): HasMany
    {
        return $this->hasMany(Saisierje::class);
    }

    public function saisielubrifiant(): HasMany
    {
        return $this->hasMany(Saisielubrifiant::class);
    }
}
