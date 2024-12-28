<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Saisiehrm extends Model
{
    /** @use HasFactory<\Database\Factories\SaisiehrmFactory> */
    use HasFactory;

    protected $fillable = [
        'datesaisie',
        'engin_id',
        'site_id',
        'hrm',
        'nho',
    ];

    public function engin(): BelongsTo
    {
        return $this->belongsTo(Engin::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}