<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Saisielubrifiant extends Model
{
    /** @use HasFactory<\Database\Factories\SaisielubrifiantFactory> */
    use HasFactory;

    protected $fillable = [
        'du',
        'au',
        'engin_id',
        'lubrifiant_id',
        'qte',
    ];

    public function engin(): BelongsTo
    {
        return $this->belongsTo(Engin::class);
    }

    public function lubrifiant(): BelongsTo
    {
        return $this->belongsTo(Lubrifiant::class);
    }
}
