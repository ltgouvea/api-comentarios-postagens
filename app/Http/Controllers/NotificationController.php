<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

const EXPIRATION_TIME_IN_HOURS = 72;

class NotificationController extends Controller
{

    /**
     * Display an paginated list of the user's all/read/unread notifications.
     * GET /notifications
     *
     * @param Request $request
     * @return Response
     */
    public function getNotificationsFromUser(Request $request)
    {
        $notifications = $request->user()->notifications();

        if ($request->has('read') || $request->has('unread')) {
            if ($request->input('read')) {
                $notifications = $request->user()->readNotifications();
            } elseif ($request->input('unread')) {
                $notifications = $request->user()->unreadNotifications();
            }
        }

        $now = \Carbon\Carbon::now();

        // Filtra notificações para as últimas 72 horas
        $notifications = $notifications->where(\DB::raw("(EXTRACT(EPOCH FROM '$now'::timestamp) - EXTRACT(EPOCH FROM created_at))/3600"), '<=', EXPIRATION_TIME_IN_HOURS)->get();

        return $this->sendResponse($notifications->forPage($request->page, 25), 'Notifications retrieved succesfully');
    }

    /**
     * Display a list of the user's the unread notifications.
     * GET /notifications/unread
     *
     * @param Request $request
     * @return Response
     */
    public function getUnreadNotificationsFromUser(Request $request)
    {
        $now = \Carbon\Carbon::now();
        $unreadNotifications = $request->user()
                                       ->unreadNotifications()
                                       ->where(\DB::raw("(EXTRACT(EPOCH FROM '$now'::timestamp) - EXTRACT(EPOCH FROM created_at))/3600"), '<=', EXPIRATION_TIME_IN_HOURS)
                                       ->get();

        return $this->sendResponse($unreadNotifications, 'Unread Notifications retrieved succesfully');
    }

    /**
     * Mark a notification as read
     * PATCH notifications/read/{id}
     *
     * @param Request $request
     * @return Response
     */
    public function markNotificationAsRead($id, Request $request)
    {
        return $this->sendResponse($request->user()->notifications->where('id', $id)->markAsRead(), 'Notification marked as read sucessfully');
    }
}
