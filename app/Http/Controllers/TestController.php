<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    //
    public function index()
    {
        $user = new User();
        $user->name = substr(md5(uniqid()), 0, 5);
        $user->email = substr(md5(uniqid()), 0, 5).'@qq.org';
        $user->password = bcrypt('secret');
        $user->api_token = substr(md5(uniqid()), 0, 30) . substr(md5(uniqid()), 0, 30);
        $user->save();
    }
}
