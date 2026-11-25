<?php

namespace App\Models;

use App\Casts\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LectureSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_uuid',
        'course_id',
        'lecturer_id',
        'session_number',
        'week_number',
        'lecture_date',
        'from_time',
        'to_time',
        'duration_minutes',
        'status',
        'started_at',
        'schema_version',
        'change_version',
    ];

    protected function casts(): array
    {
        return [
            'record_uuid' => Uuid::class,
            'lecture_date' => 'date',
            'from_time' => 'datetime:H:i',
            'to_time' => 'datetime:H:i',
            'duration_minutes' => 'integer',
            'started_at' => 'datetime',
            'schema_version' => 'integer',
            'change_version' => 'integer',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}