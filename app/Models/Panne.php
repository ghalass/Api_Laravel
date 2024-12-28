<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Panne extends Model
{
    /** @use HasFactory<\Database\Factories\PanneFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'typepanne_id',
    ];

    public function typepanne(): BelongsTo
    {
        return $this->belongsTo(Typepanne::class);
    }

    public function saisierje(): HasMany
    {
        return $this->hasMany(Saisierje::class);
    }

    public function saisiehim(): HasMany
    {
        return $this->hasMany(Saisiehim::class);
    }
}