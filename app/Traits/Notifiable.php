<?php

namespace App\Traits;

use App\Models\Notification;

trait Notifiable
{
    /**
     * Get all notifications for the model.
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    /**
     * Get unread notifications.
     */
    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }

    /**
     * Create a notification for this model.
     */
    public function notify(string $title, string $message, string $type = 'info', ?string $link = null, ?string $icon = null): Notification
    {
        return $this->notifications()->create([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'link' => $link,
            'icon' => $icon,
        ]);
    }

    /**
     * Send notification to all admins.
     */
    public static function notifyAdmins(string $title, string $message, string $type = 'info', ?string $link = null, ?string $icon = null): void
    {
        $admins = \App\Models\Admin::all();

        foreach ($admins as $admin) {
            $admin->notify($title, $message, $type, $link, $icon);
        }
    }

    /**
     * Send notification to specific admin.
     */
    public static function notifyAdmin(int $adminId, string $title, string $message, string $type = 'info', ?string $link = null, ?string $icon = null): ?Notification
    {
        $admin = \App\Models\Admin::find($adminId);

        if ($admin) {
            return $admin->notify($title, $message, $type, $link, $icon);
        }

        return null;
    }
}
