<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\TraitResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    use TraitResource;

    public function __construct()
    {
        self::$model = Comment::class;
        self::$controlName = 'comment';
    }

    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);
            $where = [];
            $title = $request->input('title', '');
            $author = $request->input('author', '');
            $status = $request->input('status', '');
            $delete = $request->input('delete', 0);
            if ($title != '') {
                $where[] = ['title', 'like', '%' . $title . '%'];
            }
            if ($author != '') {
                $where[] = ['author', 'like', '%' . $author . '%'];
            }
            if ($category_id != '') {
                $where[] = ['category_id', '=', $category_id];
            }
            if ($status != '') {
                $where[] = ['status', '=', $status];
            }
            switch ($delete) {
                case '1':
                    $list = Article::onlyTrashed()
                        ->where($where)
                        ->leftJoin('categories', 'categories.id', '=', 'articles.category_id')
                        ->select('articles.*', 'categories.name as cate_name')
                        ->get();
                    break;
                case '2':
                    $list = Article::withTrashed()
                        ->where($where)
                        ->leftJoin('categories', 'categories.id', '=', 'articles.category_id')
                        ->select('articles.*', 'categories.name as cate_name')
                        ->get();
                    break;
                default:
                    $list = Article::where($where)
                        ->leftJoin('categories', 'categories.id', '=', 'articles.category_id')
                        ->select('articles.*', 'categories.name as cate_name')
                        ->get();
                    break;
            }
            $res = self::getPageData($list, $page, $limit);
            return self::resJson(0, '获取成功', $res['data'], [
                    'count' => $res['count'],
                ]
            );
        }
        $tree = Category::select('id', 'name', 'pid')->orderBy('id', 'asc')->get()->toArray();
        $category_list = Category::array2level($tree);
        return view('admin.' . self::$controlName . '.index', [
            'control_name' => self::$controlName,
            'delete_list' => Article::$delete,
            'status_list' => Article::$status,
            'category_list' => $category_list,
            'category_id' => $category_id
        ]);
    }
}
