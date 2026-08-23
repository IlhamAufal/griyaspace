<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function page(Request $request): View
    {
        $user = $request->user();
        $filter = $request->get('filter', 'all');

        $query = $user->notifications();

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($filter !== 'all') {
            $query->where('type', 'App\\Notifications\\' . ucfirst($filter) . 'Notification');
        }

        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $unreadCount = $user->unreadNotifications()->count();

        return view('pages.notifications.index', compact('notifications', 'unreadCount', 'filter'));
    }

    public function api(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->notifications()
            ->limit(15)
            ->get()
            ->map(fn($n) => [
                'id' => $n->id,
                'type' => $n->type,
                'data' => $n->data,
                'read_at' => $n->read_at,
                'created_at' => $n->created_at,
                'time_ago' => $n->created_at->diffForHumans(),
            ]);

        $unreadCount = $request->user()->unreadNotifications()->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->find($id);

        if ($notification && is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return response()->json([
            'unread_count' => $request->user()->unreadNotifications()->count(),
        ]);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications()->markAsRead();

        return response()->json([
            'unread_count' => 0,
        ]);
    }
}
