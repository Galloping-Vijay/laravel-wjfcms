<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Article;
use App\Models\Chat;
use App\Models\Comment;
use App\Models\Permissions;
use App\Http\Controllers\Controller;
use App\Models\User;
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
     * User: Vijay
     * Date: 2019/5/24
     * Time: 22:42
     */
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getAllPermissions();
        $menu = Permissions::getMenuTree($permissions->toArray());
        return view('admin.index.index', [
            'admin' => $user,
            'menus' => $menu
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Description:
     * User: Vijay
     * Date: 2019/5/24
     * Time: 22:40
     */
    public function main()
    {
        $articleList = Article::leftJoin('categories', 'categories.id', '=', 'articles.category_id')
            ->select('articles.id', 'articles.title', 'articles.click', 'categories.name as cate_name')
            ->orderBy('articles.click', 'desc')
            ->get();
        $chatList = Chat:: select('id', 'content', 'created_at')
            ->get();
        $commentNum = Comment::count();
        $adminNum = Admin::count();
        $userNum = User::count();
        return view('admin.index.main', [
            'articleList' => $articleList,
            'chatList' => $chatList,
            'articleNum' => count($articleList),
            'commentNum' => $commentNum,
            'adminNum' => $adminNum,
            'userNum' => $userNum,
        ]);
    }
}
