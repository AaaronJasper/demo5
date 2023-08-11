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
        return $request->id;
        $user=User::find($request->id);
        return $user->image;
        return $user->permissions;
    }  
}
