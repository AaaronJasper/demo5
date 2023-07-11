<?php

namespace App\Http\Controllers;

use App\Events\UserRegister;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RegisterController extends BaseController
{
    private $LoginController;
    public function __construct(LoginController $LoginController)
    {
        $this->LoginController = $LoginController;
    }

    public function store(RegisterRequest $request)
    {
        if (Session::has('user_data')) {
            return $this->LoginController->logintest();
        }
        //輸入用戶註冊資料
        $user = new User();
        $user->name = $request->input("name");
        $user->email = $request->input("email");
        //將密碼進行加密
        $user->password = password_hash($request->input("password"), PASSWORD_DEFAULT);
        $user->save();
        //存入session
        Session::put("user_data", [
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "is_locked" => $user->is_locked
        ]);
        //發送驗證信
        event(new UserRegister($request->input("email")));
        return $this->same(200, [$user], "尚未驗證");
    }

    //進行註冊信驗證
    public function verify($token)
    {
        $registerUser = DB::table("register_token")->where([
            "token" => $token,
        ])->first();
        if(!$registerUser){
            return "已註冊";
        }
        //用戶加上驗證時間
        $select_user = User::where("email", $registerUser->email)->first();
        $select_user->email_verified_at = Carbon::now();
        $select_user->save();
        //刪除token
        DB::table("register_token")->where("token", $token)->delete();
        //用post方法返回狀態碼
        //return $this->same(200, [null], "驗證成功");
        //用get方法返回頁面
        return view("success");
    }
}
