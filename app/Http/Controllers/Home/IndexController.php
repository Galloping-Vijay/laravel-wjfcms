<?php

namespace App\Http\Controllers\Home;

use App\Http\Requests\LinkRequest;
use App\Http\Traits\TraitFront;
use App\Mail\Alarm;
use App\Models\Article;
use App\Models\Category;
use App\Models\Chat;
use App\Models\Comment;
use App\Models\FriendLink;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Vijay\Curl\Curl;
use App\Models\Nav;

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
        //登录验证
        //$this->middleware('auth');
        //共享视图
        $navList    = Nav::cacheNav();
        $categories = Category::cacheCategory();
        View::share([
            'nav_list'      => $navList,
            'category_list' => $categories,
        ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/8/4
     * Time: 16:30
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keytitle   = $request->input('keytitle', '');
        $topArticle = Article::topArticle();
        $where      = [
            ['status', '=', '1']
        ];
        if ($keytitle != '') {
            $where[] = ['title', 'like', '%' . $keytitle . '%'];
        }
        $articles = Article::where($where)
            ->orderBy('created_at', 'desc')
            ->orderBy('click', 'desc')
            ->paginate(15);
        return view('home.index.index', [
            'top_article' => $topArticle,
            'articles'    => $articles,
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
        if (empty($info) || $info->status == 0) {
            return redirect()->route('blank');
        }
        $info->click += 1;
        $info->save();

        $infoTags = [];
        if (!empty($info->keywords)) {
            $infoTags = Tag::whereIn('name', explode(',', $info->keywords))
                ->select('id', 'name')
                ->get()->toArray();
        }
        $pre  = Article::select('id', 'title')->find($id - 1);
        $next = Article::select('id', 'title')->find($id + 1);
        return view('home.index.article', [
            'is_article' => true,
            'info'       => $info,
            'info_tags'  => $infoTags,
            'pre'        => $pre,
            'next'       => $next
        ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/8/11
     * Time: 15:41
     * @param Category $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function category(Category $category)
    {
        $where    = [
            ['status', '=', '1'],
            ['category_id', '=', $category->id]
        ];
        $articles = Article::where($where)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('home.index.category', [
            'info'     => $category,
            'articles' => $articles,
        ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/8/11
     * Time: 17:53
     * @param Tag $tag
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tag(Tag $tag)
    {
        $where    = [
            ['status', '=', '1'],
            ['keywords', 'like', '%' . $tag->name . '%']
        ];
        $articles = Article::where($where)
            ->orderBy('created_at', 'desc')
            ->paginate(9);
        $count    = count($articles);
        $data     = [];
        $j        = 0;
        for ($i = 0; $i < $count; $i++) {
            if ($i % 3 == 0) {
                $j++;
            }
            $data[$j][] = $articles[$i]->toArray();
        }
        return view('home.index.tag', [
            'info'     => $tag,
            'articles' => $articles,
            'data'     => $data
        ]);
    }

    /**
     * @Description: 文章归档
     * @User: Vijay <1937832819@qq.com>
     * @Date: 2020-10-18 22:23:42
     * @param {type} 
     * @return {type} 
     */
    public function archive()
    {
        // 获取所有分类
        $category = Category::query()
            ->orderBy('sort')
            ->orderBy('id')
            ->select('id', 'name')
            ->get();
        // 获取分类下的文章
        $arts = Article::query()
            ->where('status', 1)
            ->select('id', 'title', 'category_id')
            ->orderBy('id', 'ASC')
            ->get();
        $res = [];
        foreach ($category as $cat) {
            $res[$cat['id']]['id'] = $cat['id'];
            $res[$cat['id']]['name'] = $cat['name'];
            $res[$cat['id']]['art'] = [];
        }
        foreach ($arts as $art) {
            if (!isset($res[$art['category_id']])) {
                continue;
            }
            $res[$art['category_id']]['art'][] = [
                'id' => $art['id'],
                'title' => $art['title'],
            ];
        }
        return view('home.index.archive', [
            'data' => $res
        ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/8/11
     * Time: 17:53
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function chat()
    {
        $chats = Chat::orderBy('id', 'desc')
            ->paginate(30);
        return view('home.index.chat', [
            'chats' => $chats
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
        $url  = 'http://www.jiahengfei.cn:33550/port/history?dispose=detail&key=jiahengfei&month=' . date('m') . '&day=' . date('d');
        $res  = $curl->get($url);
        return $res;
    }

    /**
     * Description:提交友情链接
     * User: Vijay
     * Date: 2019/9/21
     * Time: 14:08
     * @param LinkRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function applyLink(LinkRequest $request)
    {
        $model = new FriendLink();
        try {
            $model::create($request->input());
            Mail::to(env('MAIL_TO_ADDRESS'))->send(new Alarm('友情链接申请,请及时处理'));
            return $this->resJson(0, '操作成功');
        } catch (\Exception $e) {
            return $this->resJson(1, $e->getMessage());
        }
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
        $links = FriendLink::query()
            ->where('status', 1)
            ->select('name', 'url')
            ->orderBy('sort', 'desc')
            ->get();
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

    /**
     * Description:
     * User: Vijay
     * Date: 2019/12/4
     * Time: 22:54
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function ajaxComment(Request $request)
    {
        $article_id    = $request->input('article_id');
        $comment_limit = $request->input('comment_limit', 2);
        $comment_page  = $request->input('comment_page', 1);
        $art           = Article::find($article_id);
        if (!$art) {
            return $this->resJson('1', '不存在该文章', []);
        }
        //获取评论
        $comments = Comment::getComment($article_id, $comment_page, $comment_limit);
        return $this->resJson('0', '获取成功', $comments);
    }
}
