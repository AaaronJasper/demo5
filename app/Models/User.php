<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //帳號對權限進行多對多連接
    //function其名稱對應其屬性 ex:$user->permissions
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
    //用戶對image進行多型態一對一連接(第二個參數對應名稱)
    //function其名稱對應其屬性 ex:$user->image
    public function image()
    {
        return $this->morphOne(Image::class, "imageable");
    }
}
