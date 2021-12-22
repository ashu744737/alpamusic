<?php

namespace App\Http\Middleware;

use App\UserTypes;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;


class SetUserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $permissions = [];

        // If the User is assigned with a role but not an administrator

        if (Auth::user()->type_id) {
            $usertype = UserTypes::findById(Auth::user()->type_id);
            $data = $usertype->permissions;

            if (count($data) > 0) {
                // Coverting to Array
                $permissions = array_column(json_decode(json_encode($data), True), 'name');
            }
        }
        $userType = UserTypes::where('id', auth()->user()->type_id)->pluck('type_name');
        $userType = $userType[0];
        // Set User Permissions
        Config::set('constants.user_permissions', $permissions);
        // Set User Type
        Config::set('constants.user_type', $userType);
        return $next($request);
    }
}
