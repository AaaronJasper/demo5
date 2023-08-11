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
        $user = new User();
        $user->name = $request->input("name");
        $user->email = $request->input("email");
        //將密碼進行加密
        $user->password = password_hash($request->input("password"), PASSWORD_DEFAULT);
        $user->save();
        return $user;
    }  
}
