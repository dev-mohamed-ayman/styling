<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'notifiable_id',
        'notifiable_type',
        'title',
        'message',
        'type',
        'icon',
        'link',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Get the notifiable entity (admin or user).
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(): void
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread(): void
    {
        $this->update(['read_at' => null]);
    }

    /**
     * Check if notification is read.
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Check if notification is unread.
     */
    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read notifications.
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Get icon based on type.
     */
    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'success' => 'ti-circle-check',
            'warning' => 'ti-alert-triangle',
            'error' => 'ti-circle-x',
            default => 'ti-info-circle',
        };
    }

    /**
     * Get color class based on type.
     */
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'success' => 'text-success',
            'warning' => 'text-warning',
            'error' => 'text-danger',
            default => 'text-info',
        };
    }

    /**
     * Get background class based on type.
     */
    public function getTypeBgAttribute(): string
    {
        return match($this->type) {
            'success' => 'bg-label-success',
            'warning' => 'bg-label-warning',
            'error' => 'bg-label-danger',
            default => 'bg-label-info',
        };
    }
}
