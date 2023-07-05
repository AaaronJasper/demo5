<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class LoginController extends BaseController
{
    //登入功能
    public function login(LoginRequest $request)
    {
        //檢查session，確任是否已登入
        if (Session::has('user_data')) {
            return $this->logintest();
        }
        //確認輸入帳密是否正確
        $email = $request->email;
        $password = $request->password;
        $user = User::where("email", $email)->first();
        //檢查是不是黑名單
        if($user->is_locked==1){
            $user = new UserResource($user);
            return $this->same(404,[$user], "登錄失敗");
        }
        if (password_verify($password, $user->password)) {
            Session::put("user_data", [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "is_locked" => $user->is_locked
            ]);
            $user = new UserResource($user);
            return $this->same(200,[$user], "登錄成功");
        } else {
            $user = new UserResource($user);
            return $this->same(404,[$user], "登錄失敗");
        }
    }

    //確認是否登錄成功的功能
    public function logintest()
    {
        $user_data = Session::get('user_data');
        if ($user_data != null) {
            return $this->same(200, [$user_data], "已登入");;
        } else {
            return $this->same(200, [$user_data], "尚未登入");
        }
    }


    //登出功能
    public function logout()
    {
        if (Session::has('user_data')) {
            Session::pull('user_data');
            return "已登出";
        } else {
            return "尚未登入";
        }
    }
}
