<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)->latest()->get();

        return response()->json($notifications);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient' => 'required|email',
            'title' => 'required|string',
            'subject' => 'required|string',
            'message' => 'required|string'
        ]);

        $recipient = User::where('email', $validated['recipient'])->firstOrFail();

        $notification = Notification::create([
            'user_id' => $recipient->id,
            'sender_id' => $request->user()->id,
            'title' => $validated['title'],
            'subject' => $validated['subject'],
            'message' => $validated['message']
        ]);

        return response()->json($notification, 201);
    }

    public function markAsRead(Request $request, Notification $notification)
    {
        if ($notification->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->update([
            'read' => true
        ]);

        return response()->json($notification);
    }

    public function markAllAsRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)
            ->where('read', false)
            ->update([
                'read' => true
            ]);

        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function destroy(Request $request, Notification $notification)
    {
        if ($notification->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted successfully'
        ]);
    }

    public function deleteAll(Request $request)
    {
        Notification::where('user_id', $request->user()->id)->delete();

        return response()->json([
            'message' => 'All notifications deleted'
        ]);
    }
}
