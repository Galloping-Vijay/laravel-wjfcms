<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\TraitResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    use TraitResource;

    /**
     * CommentController constructor.
     */
    public function __construct()
    {
        self::$model = Comment::class;
        self::$controlName = 'comment';
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/7/8
     * Time: 23:31
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);
            $where = [];
            $username = $request->input('username', '');
            $status = $request->input('status', '');
            $delete = $request->input('delete', 0);
            $title = $request->input('title', '');
            if ($username != '') {
                $where[] = ['users.name', 'like', '%' . $username . '%'];
            }
            if ($title != '') {
                $where[] = ['articles.title', 'like', '%' . $title . '%'];
            }
            if ($status != '') {
                $where[] = ['comments.status', '=', $status];
            }
            switch ($delete) {
                case '1':
                    $list = self::$model::onlyTrashed()
                        ->where($where)
                        ->leftJoin('users', 'users.id', '=', 'comments.user_id')
                        ->leftJoin('articles', 'articles.id', '=', 'comments.article_id')
                        ->select('comments.*', 'users.name as username', 'articles.title')
                        ->orderBy('comments.id', 'desc')
                        ->get();
                    break;
                case '2':
                    $list = self::$model::withTrashed()
                        ->where($where)
                        ->leftJoin('users', 'users.id', '=', 'comments.user_id')
                        ->leftJoin('articles', 'articles.id', '=', 'comments.article_id')
                        ->select('comments.*', 'users.name as username', 'articles.title')
                        ->orderBy('comments.id', 'desc')
                        ->get();
                    break;
                default:
                    $list = self::$model::where($where)
                        ->leftJoin('users', 'users.id', '=', 'comments.user_id')
                        ->leftJoin('articles', 'articles.id', '=', 'comments.article_id')
                        ->select('comments.*', 'users.name as username', 'articles.title')
                        ->orderBy('comments.id', 'desc')
                        ->get();
                    break;
            }
            $res = self::getPageData($list, $page, $limit);
            return self::resJson(0, '获取成功', $res['data'], [
                    'count' => $res['count'],
                ]
            );
        }
        return view('admin.' . self::$controlName . '.index', [
            'control_name' => self::$controlName,
            'delete_list' => Comment::$delete,
            'status_list' => Comment::$status,
        ]);
    }

    /**
     * Description:替换留言文本内容
     * User: Vijay
     * Date: 2019/7/8
     * Time: 23:09
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function replace(Request $request)
    {
        $search = $request->input('search');
        $replace = $request->input('replace');
        try {
            $data = Comment::select('id', 'content')
                ->where('content', 'like', "%$search%")
                ->get();
            foreach ($data as $k => $v) {
                Comment::find($v->id)->update([
                    'content' => str_replace($search, $replace, $v->content),
                ]);
            }
            return $this->resJson(0, '操作成功');
        } catch (\Exception $e) {
            return $this->resJson(1, $e->getMessage());
        }
    }
}
