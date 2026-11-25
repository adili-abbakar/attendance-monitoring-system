<?php

namespace App\Models;

use App\Casts\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_uuid',
        'lecture_session_id',
        'student_id',
        'lecturer_id',
        'status',
        'scanned_at',
        'source_revision',
        'schema_version',
        'change_version',
    ];

    protected function casts(): array
    {
        return [
            'record_uuid' => Uuid::class,
            'scanned_at' => 'datetime',
            'source_revision' => 'integer',
            'schema_version' => 'integer',
            'change_version' => 'integer',
        ];
    }

    public function lectureSession(): BelongsTo
    {
        return $this->belongsTo(LectureSession::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }
}