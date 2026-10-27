<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visit extends Model
{
    /** @use HasFactory<\Database\Factories\VisitFactory> */
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'department_id',
        'purpose',
        'visit_date',
        'visit_time',
        'status',
        'qr_code_token',
        'check_in_time',
        'check_out_time',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'check_in_time' => 'datetime',
            'check_out_time' => 'datetime',
        ];
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
