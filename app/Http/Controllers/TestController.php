<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class TestController extends Controller
{
    //
    public function index()
    {
        $user = User::where('name', '=', '魏花花')->first();
        echo 'owner:'. $user->hasRole('owner'); // false
        echo "<br/>";
        echo 'admin:'. $user->hasRole('admin'); // true
        echo "<br/>";
        echo 'edit-user:'.  $user->can('edit-user'); // true
        echo "<br/>";
        echo 'create-post:'.  $user->can('create-post'); // true
    }
}
