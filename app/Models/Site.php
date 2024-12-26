<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    /** @use HasFactory<\Database\Factories\SiteFactory> */
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function engins(): HasMany
    {
        return $this->hasMany(Engin::class);
    }

    public function saisierje(): HasMany
    {
        return $this->hasMany(Saisierje::class);
    }
}
