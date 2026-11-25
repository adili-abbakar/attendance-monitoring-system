<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeletionMarker extends Model
{
    use HasFactory;

    protected $fillable = [
        'marker_id',
        'record_uuid',
        'record_type',
        'lecturer_id',
        'course_id',
        'session_id',
        'attendance_record_id',
        'student_id',
        'deleted_at',
        'schema_version',
        'change_version',
    ];

    protected function casts(): array
    {
        return [
            'record_uuid' => 'uuid',
            'deleted_at' => 'datetime',
            'schema_version' => 'integer',
            'change_version' => 'integer',
        ];
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }
}