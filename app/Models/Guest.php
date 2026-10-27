<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    /** @use HasFactory<\Database\Factories\GuestFactory> */
    use HasFactory;

    protected $fillable = [
        'nik',
        'name',
        'phone',
        'agency_name',
    ];

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }
}
