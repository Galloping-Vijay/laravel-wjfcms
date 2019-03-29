<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    //
    public function index()
    {
        $user = User::findOrFail(1);
        $profile = $user->profile;
        print_r($profile);
    }
}
