<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyncCheckpoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'collection_name',
        'cursor',
        'last_page_id',
        'schema_version',
    ];

    protected function casts(): array
    {
        return [
            'cursor' => 'uuid',
            'last_page_id' => 'uuid',
            'schema_version' => 'integer',
        ];
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(SyncDevice::class, 'device_id');
    }
}