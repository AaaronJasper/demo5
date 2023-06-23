<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class LoginController extends BaseController
{
    //登入功能
    public function login(LoginRequest $request)
    {
        //檢查Cache，確任是否已登入
        if (Session::has('user_data')) {
            return $this->logintest();
        }
        //確認輸入帳密是否正確
        $email = $request->email;
        $password = $request->password;
        $user = User::where("email", $email)->where("password", $password)->first();
        if (empty($user)) {
            Session::put("user_data", [
                "name" => $user->name,
                "email" => $user->email
            ]);
            return $this->same([$user], "登錄成功", 200);
        } else {
            return $this->same([$user], "登錄失敗", 404);
        }
    }

    //確認是否登錄成功的功能
    public function logintest()
    {

        $user_data=Session::get('user_data');
        if ($user_data != null) {
            return $this->same([$user_data], "已登入", 200);;
        } else {
            return $this->same([$user_data], "尚未登入", 200);
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
