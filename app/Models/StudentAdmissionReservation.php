<?php

namespace App\Models;

use App\Casts\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAdmissionReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'admission_number_hash',
        'lecturer_id',
        'student_record_uuid',
        'reserved_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'student_record_uuid' => Uuid::class,
            'reserved_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}