<?php

namespace App\Models;

use App\Models\Abstracts\BaseGlobalModel;
use App\Support\Enums\NotificationChannel;
use App\Support\Enums\NotificationStatus;
use App\Support\Enums\NotificationType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationLog extends BaseGlobalModel
{
    protected $fillable = [
        'notification_class',
        'notification_id',
        'notifiable_type',
        'notifiable_id',
        'channel',
        'status',
        'driver',
        'recipient',
        'subject',
        'response',
        'error_message',
        'metadata',
        'sent_at',
        'failed_at',
        'retries',
        'job_id',
        'queue',
    ];

    protected $casts = [
        'notification_type' => NotificationType::class,
        'channel' => NotificationChannel::class,
        'status' => NotificationStatus::class,
        'payload' => 'json',
        'provider_response' => 'json',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'failed_at' => 'datetime',
        'last_attempt_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
