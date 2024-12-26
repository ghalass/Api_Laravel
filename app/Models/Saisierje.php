<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Saisierje extends Model
{
    /** @use HasFactory<\Database\Factories\SaisierjeFactory> */
    use HasFactory;

    protected $fillable = [
        'daterje',
        'engin_id',
        'site_id',
        'panne_id',
        'hrm',
        'him',
        'nho',
        'ni',
    ];

    public function engin(): BelongsTo
    {
        return $this->belongsTo(Engin::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function panne(): BelongsTo
    {
        return $this->belongsTo(Panne::class);
    }
}
