<?php

namespace App\Models;

use App\Casts\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyncPendingPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'device_id',
        'collection_name',
        'expected_cursor',
        'next_cursor',
        'schema_version',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'page_id' => Uuid::class,
            'expected_cursor' => Uuid::class,
            'next_cursor' => Uuid::class,
            'schema_version' => 'integer',
            'expires_at' => 'datetime',
        ];
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(SyncDevice::class, 'device_id');
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}