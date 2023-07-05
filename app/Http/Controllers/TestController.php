<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TestController extends BaseController
{
    public function index(Request $request)
    {
        $user=User::find($request->id);
        return $user->permissions;
        //$user->permissions()->attach(2);
        foreach($user->permissions as $permission){
            if($permission["id"]==2){
                return "true";
            }
        } 
        return false;
    }    
}
