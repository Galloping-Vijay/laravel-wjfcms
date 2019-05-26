<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permissions;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Description:
     * User: VIjay
     * Date: 2019/5/24
     * Time: 22:42
     */
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->permissions;
        $menu = Permissions::getMenuTree($permissions->toArray());
        return view('admin.index.index', [
            'admin' => $user,
            'menus' => $menu
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Description:
     * User: VIjay
     * Date: 2019/5/24
     * Time: 22:40
     */
    public function main()
    {
        return view('admin.index.main');
    }
}
