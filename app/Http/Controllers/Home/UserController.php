<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\UserRequest;
use App\Http\Traits\TraitResource;
use App\Http\Traits\TraitUpload;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
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
     * Time: 23:49
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function modify(Request $request)
    {
        $info = Auth::user();
        if ($request->isMethod('post')) {
            $avatar = $request->input('avatar', '');
            if ($avatar == '') {
                return $this->resJson(1, '图像不能为空');
            }
            $info->avatar = $avatar;
            $res = $info->save();
            return $this->resJson(0, '操作成功', $res);
        }

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
        $email = $request->input('email', '');
        $email_code = $request->input('email_code', '');
        $name = $request->input('name', '');
        $sex = $request->input('sex', '');
        $tel = $request->input('tel', '');
        $city = $request->input('city', '');
        $intro = $request->input('intro', '');

        $info = Auth::user();
        if ($email != '') {
            if ($email_code != '') {
                $cacheEmail = Cache::get($email);
                if ($cacheEmail != $email_code) {
                    return $this->resJson(1, '邮箱验证码错误');
                }
                $info->email_verified_at = date('Y-m-d H:i:s');
            }
            $info->email = $email;
        }
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
     * Date: 2019/12/4
     * Time: 22:16
     * @param CommentRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function comment(CommentRequest $request)
    {
        $data = [
            'user_id' => $request->input('user_id'),
            'article_id' => $request->input('article_id'),
            'type' => 1,
            'pid' => $request->input('pid', 0),
            'origin_id' => $request->input('origin_id', 0),
            'status' => 1,
            'content' => htmlspecialchars($request->input('content')),
        ];
        $res = Comment::create($data);
        if (!$res) {
            return $this->resJson(1, '评论失败', []);
        }
        return $this->resJson(0, '评论成功', []);
    }


    public function commentAction(Request $request)
    {
        $action_type = $request->input('action_type', '');
        if (!in_array($action_type, ['zan', 'cai'])) {
            return $this->resJson(1, '操作类型错误');
        }
        $comment_id = $request->input('comment_id', 0);
        $info = Comment::find($comment_id);
    }

}
