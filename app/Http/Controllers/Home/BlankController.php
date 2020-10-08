<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\View;
use App\Models\Nav;

class BlankController extends Controller
{
    public function __construct()
    {
        //共享视图
        $navList = Nav::getMenuTree(Nav::orderBy('sort', 'asc')->get()->toArray());
        $categories = Category::getMenuTree(Category::orderBy('sort', 'asc')->select('id', 'name', 'pid')->get()->toArray());
        View::share([
            'nav_list' => $navList,
            'category_list' => $categories,
        ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/12/1
     * Time: 11:24
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('home.blank.index', [
            'message' => '页面错误'
        ]);
    }
}
