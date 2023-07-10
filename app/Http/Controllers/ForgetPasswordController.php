<?php

namespace App\Http\Controllers;

use App\Mail\ForgetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgetPasswordController extends BaseController
{
    //寄送驗證信
    public function forget_password(Request $request)
    {
        //驗證email
        $request->validate([
            'email' => 'required|email|exists:users'
        ]);
        //使用輔助函數生成token
        $token = Str::random(64);
        //寫入資料庫
        DB::table("password_reset_tokens")->updateOrInsert(
            ['email' => $request->email],
            [
                "token" => $token,
                "created_at" => Carbon::now()
            ]
        );
        //寄送email
        Mail::to($request->email)->send(new ForgetPasswordMail($token));
        return "already send mail";
    }
    //執行更新密碼
    public function reset_password(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|min:4|max:16|confirmed',
            'password_confirmation' => 'required',
            'token' => 'required'
        ]);
        //確認是否有此帳號及token(原本在前端hidden傳入token)
        $updateUser = DB::table("password_reset_tokens")->where([
            "email" => $request->email,
            "token" => $request->token,
        ])->first();
        if (!$updateUser) {
            return $this->same(404, [$updateUser], "無此用戶");
        }
        //更新用戶數據
        User::where('email', $request->email)->update(["password" => Hash::make($request->password)]);
        //刪除忘記密碼中的token
        DB::table("password_reset_tokens")->where("email", $request->email)->delete();
        return "already update password";
    }
}
