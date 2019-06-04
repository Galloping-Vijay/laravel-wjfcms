<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;
    use HasRoles;

    protected $error = null;

    /**
     * @var array
     */
    public static $sex = [
        -1 => '保密',
        0 => '男',
        1 => '女'
    ];

    /**
     * @var array
     */
    public static $status = [
        0 => '禁用',
        1 => '正常'
    ];

    /**
     * @var array
     */
    public static $delete = [
        0 => '正常',
        1 => '软删除',
        2 => '全部'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account', 'username', 'password', 'tel', 'email', 'sex', 'status', 'created_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @return null
     * Description:
     * User: Vijay
     * Date: 2019/5/26
     * Time: 20:38
     */
    public function getError()
    {
        return $this->error;
    }
}
