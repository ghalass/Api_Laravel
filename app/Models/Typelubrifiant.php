<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Typelubrifiant extends Model
{
    /** @use HasFactory<\Database\Factories\TypelubrifiantFactory> */
    use HasFactory;

    protected $fillable = ['name'];

    function lubrifiants(): HasMany
    {
        return $this->hasMany(Lubrifiant::class);
    }
}
