<?php

// Check User Permission

use App\UserNotification;
use App\NotificationTarget;
use App\User;
use App\Investigations;
use App\Mail\NotificationEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

if (!function_exists('check_perm')) {
    function check_perm($permission)
    {
        $user_perm = config('constants.user_permissions');
        $user_perm = ($user_perm) ?? [];
        if (isset(auth()->user()->type_id) && (isAdmin() || isSM())) {
            return TRUE;
        } else if (is_array($permission)) {
            return array_intersect($permission,  $user_perm);
        } else {
            return (in_array($permission, $user_perm)) ? TRUE : FALSE;
        }
    }
}
// Check User Permission Menu
if (!function_exists('is_menu_enable')) {
    function is_menu_enable($menu)
    {
        if (isset(auth()->user()->type_id) && (isAdmin() || isSM())) {
            return TRUE;
        } else {
            $permissions = config('constants.user_permissions');

            if (is_array($permissions) && count($permissions) > 0) {
                if (is_array($menu)) {
                    foreach ($menu as $m) {
                        $matches  = preg_grep('/^' . $m . '(\w+)/i', $permissions);

                        if (count($matches) > 0) {
                            return TRUE;
                        }
                    }
                    return FALSE;
                } else {
                    $matches  = preg_grep('/^' . $menu . '(\w+)/i', $permissions);
                    return (count($matches) > 0) ? TRUE : FALSE;
                }
            }
        }

        return FALSE;
    }
}

// Get User Notification Data
if (!function_exists('getNotificationdata')) {
    function getNotificationdata()
    {
        $notificationdata = NotificationTarget::with('notification')
                        ->where('user_id', auth()->user()->id)
                        ->where('is_read', 0)
                        ->orderBy('created_at', 'DESC')
                        ->get();

        $returnHTML = view('layouts.partials.notification-data', compact('notificationdata'))->render();
        return response()->json(
            array(
                'status'        => 1,
                'html'          => $returnHTML
            )
        );
        // return $returnHTML;
    }
}

// Read User Notifications
if (!function_exists('readNotifications')) {
    function readNotifications($id)
    {
        $notificationData = NotificationTarget::where('id', $id)
            ->update([
                'is_read' => 1,
                'read_at' => now()
            ]);

        return true;
    }
}


if (!function_exists('insertNotifications')) {
    /*
    *
    * $data : data insterted to be in Notification table, like message, investigation no
    * $type: ['admin', 'sm', 'user'] target user's type
    * $uid pass if need to display message to specific user   
    */
    function insertNotifications($data, $type, $uid = array(), $message = array())
    {
        $users= [];
        // Fetch all admin and add notification for each one
        if(in_array('admin', $type)){

            $admin = User::whereHas(
                            'user_type',
                            function ($query) {
                                return $query->where('type_name', env('USER_TYPE_ADMIN'));
                            }
                        )
                    ->where('status','approved')
                    ->pluck('id')->toArray();
            $users = array_merge($users,$admin);
        }


        // Fetch all sm and add notification for each one
        if(in_array('sm', $type)){
            $smQuery = User::whereHas(
                            'user_type',
                            function ($query) {
                                return $query->where('type_name', env('USER_TYPE_STATION_MANAGER'));
                            }
                        );

            //if status is for investigation only related sm should receive notifications
            if(isset($data['investigation_id']) && !empty($data['investigation_id'])){
                $investigation = Investigations::find($data['investigation_id']);
                $categotyId = !is_null($investigation->product->category)?$investigation->product->category->id:'';
            
                $smQuery->whereHas(
                        'userCategories',
                        function ($query) use ($categotyId){
                            return $query->where('category_id', $categotyId);
                        }
                    );
            }

            $sm = $smQuery->where('status','approved')
                    ->pluck('id')->toArray();

            $users  = array_merge($users,$sm);
        }

        if(in_array('user', $type) && !empty($uid)){
            $users  = array_merge($users,$uid);
        }

        //remove duplicate user and loggedin user
        $users = array_unique(array_diff( $users, [Auth::id()] ));

        $notificationId = UserNotification::create($data)->id;

        foreach ($users as $key => $userId) {
            $user = User::where('id', $userId)->first();
            $notificationText = $message;
            
            Mail::to($user->email)->queue(new NotificationEmail($user, $notificationText));

            NotificationTarget::create(['notification_id' => $notificationId, 'user_id'=> $userId]);
        }
    }
}

// Check User is Admin
function isAdmin(){
    if(config('constants.user_type') == env('USER_TYPE_ADMIN')){
        return true;
    }
    return false;
}

// Check User is Station manager
function isSM(){
    if(config('constants.user_type') == env('USER_TYPE_STATION_MANAGER')){
        return true;
    }
    return false;
}

// Check User is Client
function isClient(){
    if(config('constants.user_type') == env('USER_TYPE_CLIENT')){
        return true;
    }
    return false;
}

// Check User is Accountant
function isAccountant(){
    if(config('constants.user_type') == env('USER_TYPE_ACCOUNTANT')){
        return true;
    }
    return false;
}

// Check User is Investigator
function isInvestigator(){
    if(config('constants.user_type') == env('USER_TYPE_INVESTIGATOR')){
        return true;
    }
    return false;
}

// Check User is Deliveryboy
function isDeliveryboy(){
    if(config('constants.user_type') == env('USER_TYPE_DELIVERY_BOY')){
        return true;
    }
    return false;
}

// Check User is Secretary
function isSecretary(){
    if(config('constants.user_type') == env('USER_TYPE_SECRETARY')){
        return true;
    }
    return false;
}
