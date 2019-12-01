<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Traits\TraitResource;
use App\Http\Traits\TraitUpload;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Support\Facades\View;
use App\Models\Nav;

class UserController extends Controller
{
    use TraitResource;
    use TraitUpload;

    /**
     * UserController constructor.
     */
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
     * Time: 8:35
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        return view('home.user.index', [
            'user' => $user,
        ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/12/1
     * Time: 11:44
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function modify()
    {
        $info = Auth::user();
        $sexList = User::$sexList;
        return view('home.user.modify', [
            'user' => $info,
            'sexList' => $sexList
        ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/12/1
     * Time: 11:15
     * @param UserRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(UserRequest $request)
    {
        $name = $request->input('name', '');
        $sex = $request->input('sex', '');
        $tel = $request->input('tel', '');
        $city = $request->input('city', '');
        $intro = $request->input('intro', '');

        $info = Auth::user();
        if ($name != '') {
            $info->name = $name;
        }
        if ($sex != '') {
            $info->sex = $sex;
        }
        if ($tel != '') {
            $info->tel = $tel;
        }
        if ($city != '') {
            $info->city = $city;
        }
        if ($intro != '') {
            $info->intro = $intro;
        }
        try {
            $res = $info->save();
            return $this->resJson(0, '操作成功', $res);
        } catch (\Exception $e) {
            return $this->resJson(1, $e->getMessage());
        }
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/12/1
     * Time: 11:52
     * @param $id
     * @return bool
     */
    public static function Rule($id)
    {
        $user = Auth::user();
        if ($user->id != $id) {
            return false;
        }
        return true;
    }
}
