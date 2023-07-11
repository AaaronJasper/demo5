<?php

namespace App\Listeners;

use App\Events\UserRegister;
use App\Mail\UserRegisterMail;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailOwnerAboutRegistion
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegister $event): void
    {
        //使用輔助函數生成token
        $token = Str::random(64);
        //寫入資料庫
        DB::table("register_token")->updateOrInsert(
            ['email' => $event->email],
            [
                "token" => $token,
                "created_at" => Carbon::now()
            ]
        );
        Mail::to($event->email)->send(new UserRegisterMail($token));
    }
}
