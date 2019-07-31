<?php

namespace App\Http\Controllers\Home;

use App\Models\Article;
use App\Models\FriendLink;
use App\Models\Nav;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Vijay\Curl\Curl;

class IndexController extends Controller
{
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
        $navList = Nav::getMenuTree(Nav::orderBy('sort', 'asc')->get()->toArray());
        $topArticle = Article::where([
            ['is_top', '=', '1'],
            ['status', '=', '1']
        ])->select('id', 'title', 'cover')
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get();
        $clickArticle = Article::where([
            ['status', '=', '1']
        ])->select('id', 'title', 'cover', 'description')
            ->orderBy('click', 'desc')
            ->limit(6)
            ->get();
        $tags = Tag::select('id', 'name')->get();
        $links = FriendLink::select('name', 'url')->get();
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
            'nav_list' => $navList,
            'top_article' => $topArticle,
            'click_article' => $clickArticle,
            'articles' => $articles,
            'tags' => $tags,
            'links' => $links,
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
    public function history(Request $request){
        $curl = new Curl();
        $url = 'http://www.jiahengfei.cn:33550/port/history?dispose=detail&key=jiahengfei&month='.date('m').'&day='.date('d');
        $res = $curl->get($url);
       return $res;
    }
}
