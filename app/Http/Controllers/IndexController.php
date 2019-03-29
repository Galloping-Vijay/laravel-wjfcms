<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    //
    public function index(Request $request)
    {
//        $user = Auth::user();  // 获取当前登录用户的完整信息
//        $userId = Auth::id();  // 获取当前登录用户 ID
        $user = $request->user();
        dd($user);
    }
}
