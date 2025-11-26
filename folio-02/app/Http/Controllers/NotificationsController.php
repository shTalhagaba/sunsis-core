<?php

namespace App\Http\Controllers;

use App\Filters\NotificationFilters;
use App\Helpers\AppHelper;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(NotificationFilters $filters)
    {
        $notifications = Notification::filter($filters)
            ->where('notifiable_id', auth()->user()->id)
            ->paginate(session('notifications_per_page', config('model_filters.default_per_page')));

        return view('notifications.index', compact('notifications', 'filters'));
    }

    public function markAsRead(Notification $notification)
    {
        abort_if($notification->notifiable_id !== auth()->user()->id, Response::HTTP_UNAUTHORIZED);

        $notification->markAsRead();
        AppHelper::cacheUnreadCountForUser(auth()->user());
        
		return response()->json([
            'title' => 'success',
        ]);
    }

    public function destroy(Notification $notification)
    {
        abort_if($notification->notifiable_id !== auth()->user()->id, Response::HTTP_UNAUTHORIZED);

        $notification->delete();
        AppHelper::cacheUnreadCountForUser(auth()->user());

		return response()->json([
            'message' => 'Notification is deleted successfully.',
            'title' => 'success',
        ]);
    }

}
