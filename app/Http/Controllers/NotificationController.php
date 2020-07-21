<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{
    public function readNotifications()
    {
        auth()->user()->notifications()->delete();

        return back();
    }

    public function readNotification($notification)
    {
        $notification = auth()->user()->notifications()->findOrFail($notification);

        $notification->delete();

        return redirect($notification->data['link']);
    }
}
