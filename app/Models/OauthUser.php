<?php

namespace App\Models;

use App\Http\Traits\TraitsModel;
use Illuminate\Database\Eloquent\Model;

class OauthUser extends Model
{
    use TraitsModel;

    /**
     * Author: Vijay  <1937832819@qq.com>
     * @var array
     */
    protected $fillable = [
        'type', 'name', 'avatar', 'openid', 'access_token', 'last_login_ip', 'login_times', 'email', 'is_admin','created_at'
    ];
}
