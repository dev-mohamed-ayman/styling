<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Notification;

class Notifications extends Component
{
    public $notifications = [];
    public $unreadCount = 0;
    public $isOpen = false;

    protected $listeners = [
        'notification-received' => 'loadNotifications',
        'echo-private:admin-notifications' => 'loadNotifications',
    ];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $admin = auth('admin')->user();

        if ($admin) {
            $this->notifications = $admin->notifications()->take(10)->get()->toArray();
            $this->unreadCount = $admin->unreadNotifications()->count();
        }
    }

    public function toggleDropdown()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function closeDropdown()
    {
        $this->isOpen = false;
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);

        if ($notification && $notification->notifiable_id === auth('admin')->id()) {
            $notification->markAsRead();
            $this->loadNotifications();

            // Redirect if link exists
            if ($notification->link) {
                return redirect($notification->link);
            }
        }
    }

    public function markAllAsRead()
    {
        $admin = auth('admin')->user();

        if ($admin) {
            $admin->unreadNotifications()->update(['read_at' => now()]);
            $this->loadNotifications();
        }
    }

    public function deleteNotification($notificationId)
    {
        $notification = Notification::find($notificationId);

        if ($notification && $notification->notifiable_id === auth('admin')->id()) {
            $notification->delete();
            $this->loadNotifications();
        }
    }

    public function render()
    {
        return view('livewire.dashboard.notifications');
    }
}
