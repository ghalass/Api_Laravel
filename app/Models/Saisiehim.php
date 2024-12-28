<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Saisiehim extends Model
{
    /** @use HasFactory<\Database\Factories\SaisiehimFactory> */
    use HasFactory;

    protected $fillable = [
        'datesaisie',
        'engin_id',
        'panne_id',
        'him',
        'ni',
    ];

    public function engin(): BelongsTo
    {
        return $this->belongsTo(Engin::class);
    }

    public function panne(): BelongsTo
    {
        return $this->belongsTo(Panne::class);
    }
}