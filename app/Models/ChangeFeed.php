<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChangeFeed extends Model
{
    use HasFactory;

    protected $fillable = [
        'change_id',
        'lecturer_id',
        'collection_name',
        'document_id',
        'operation',
        'data',
        'server_timestamp',
        'sequence',
        'source_revision',
        'event_id',
        'schema_version',
    ];

    protected function casts(): array
    {
        return [
            'change_id' => 'uuid',
            'data' => 'array',
            'server_timestamp' => 'datetime',
            'sequence' => 'integer',
            'source_revision' => 'integer',
            'schema_version' => 'integer',
        ];
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }
}