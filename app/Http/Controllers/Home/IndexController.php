<?php

namespace App\Http\Controllers\Home;

use App\Http\Traits\TraitFront;
use App\Models\Article;
use App\Models\Category;
use App\Models\FriendLink;
use App\Models\Nav;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Vijay\Curl\Curl;

class IndexController extends Controller
{
    use TraitFront;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index(Request $request)
    {
        $keytitle = $request->input('keytitle', '');
        $topArticle = Article::where([
            ['is_top', '=', '1'],
            ['status', '=', '1']
        ])->select('id', 'title', 'cover')
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get();
        $where = [
            ['status', '=', '1']
        ];
        if ($keytitle != '') {
            $where[] = ['title', 'like', '%' . $keytitle . '%'];
        }
        $articles = Article::where($where)
            ->orderBy('updated_at', 'desc')
            ->paginate(15);
        return view('home.index.index', [
            'top_article' => $topArticle,
            'articles' => $articles,
        ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/8/3
     * Time: 14:29
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function article($id)
    {
        $info = Article::find($id);
        if (empty($info)) {
            return redirect('/');
        }
        return view('home.index.article', [
            'info' => $info,
        ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/7/31
     * Time: 13:51
     * @param Request $request
     * @return array|bool|mixed
     */
    public function history(Request $request)
    {
        $curl = new Curl();
        $url = 'http://www.jiahengfei.cn:33550/port/history?dispose=detail&key=jiahengfei&month=' . date('m') . '&day=' . date('d');
        $res = $curl->get($url);
        return $res;
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/8/3
     * Time: 14:04
     * @param Request $request
     * @return mixed
     */
    public function clickArticle(Request $request)
    {
        $clickArticle = Article::where([
            ['status', '=', '1']
        ])->select('id', 'title', 'cover', 'description')
            ->orderBy('click', 'desc')
            ->limit(6)
            ->get();
        return $this->resJson('0', '获取成功', $clickArticle);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/8/3
     * Time: 14:21
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function friendLinks(Request $request)
    {
        $links = FriendLink::select('name', 'url')->get();
        return $this->resJson('0', '获取成功', $links);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/8/3
     * Time: 14:26
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function ajaxTags(Request $request)
    {
        $tags = Tag::select('id', 'name')->get();
        return $this->resJson('0', '获取成功', $tags);
    }
}
