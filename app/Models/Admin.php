<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use SoftDeletes;

    // Spatie Permission trait
    use \Spatie\Permission\Traits\HasRoles;

    protected $guarded = [];
    protected $hidden = [
        'password',
        'is_active',
    ];
    protected $appends = ['image'];

    // Accessors
    public function getImageAttribute()
    {
        return 'https://ui-avatars.com/api/?name=' . $this->name;
    }

    // Notifications
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }

    /**
     * Create a notification for this admin.
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
}
