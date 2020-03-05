<?php

namespace App\Models;

use App\Http\Traits\TraitsModel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use phpDocumentor\Reflection\Types\Self_;

class User extends Authenticatable
{
    use TraitsModel;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'created_at', 'remember_token', 'avatar', 'provider_id', 'provider', 'tel', 'sex', 'city', 'intro', 'email_verified_at'
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

    /**
     * @var array
     */
    public static $sexList = [
        '保密', '男', '女'
    ];

    /**
     * Description:
     * User: Vijay
     * Date: 2019/12/1
     * Time: 9:34
     * @return mixed
     */
    public function getSexTextAttribute()
    {
        return Self::$sexList[$this->sex];
    }
}
