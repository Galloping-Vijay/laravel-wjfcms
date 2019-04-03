<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;
    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function pointLogs()
    {
        return $this->hasMany(PointLog::class);
    }

    protected $dispatchesEvents = [

    ];

    /**
     * Instructions:
     * Author: vijay <1937832819@qq.com>
     * @return mixed|string
     */
    public function generateToken()
    {
        if (empty($this->api_token)) {
            $this->api_token = substr(md5(uniqid()), 0, 30) . substr(md5(uniqid()), 0, 30);
            $this->save();
        }
        return $this->api_token;
    }
}
