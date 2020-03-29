<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permissions;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $user = Auth::user();
        //$permissions  = $user->getAllPermissions();
        $role = Role::find(1);
        //$role->givePermissionTo('删除管理员');
        $res =$user->can('删除管理员', 'admin');
        //$res = $role->revokePermissionTo('删除管理员');
        dd($res);
    }

    public function cc(){
        dd(22);
    }
}
