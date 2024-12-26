<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Typeparc extends Model
{
    /** @use HasFactory<\Database\Factories\TypeparcFactory> */
    use HasFactory;

    protected $fillable = ['name', 'description'];
}
