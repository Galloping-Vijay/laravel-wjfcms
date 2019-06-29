<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\TraitResource;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    use TraitResource;

    public function __construct()
    {
        self::$model = Article::class;
        self::$controlName = 'article';
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/6/25
     * Time: 21:04
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $category_id = $request->input('category_id', '');
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

    /**
     * Description:
     * User: Vijay
     * Date: 2019/6/29
     * Time: 20:46
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $category_id = request()->input('category_id', '');
        $tree = Category::select('id', 'name', 'pid')->orderBy('id', 'asc')->get()->toArray();
        $category_list = Category::array2level($tree);
        $tags_list = Tag::all();
        return view('admin.' . self::$controlName . '.create',
            [
                'control_name' => self::$controlName,
                'category_list' => $category_list,
                'category_id' => $category_id,
                'tags_list' => $tags_list
            ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/5/27
     * Time: 22:28
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new self::$model;
        try {
            $data = $request->input();
            if (isset($data['tags']) && !empty($data['tags'])) {
                $keywordsArr = [];
                foreach ($data['tags'] as $key => $val) {
                    $keywordsArr[] = $key;
                }
                $data['keywords'] = implode(',', $keywordsArr);
            }
            if (isset($data['html'])) {
                $data['html'] = $data['markdown'] = htmlspecialchars($data['html']);
            }
            $model::create($data);
            return $this->resJson(0, '操作成功');
        } catch (\Exception $e) {
            return $this->resJson(1, $e->getMessage());
        }
    }

    /**
     * Description:上传图片
     * User: Vijay
     * Date: 2019/6/29
     * Time: 20:37
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('file')) {
            $date = date('Ymd');
            $path = $request->file('file')->store('', 'uploads');
            if ($path) {
                $data['src'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/uploads/' . $date . '/' . $path;
                $data['title'] = '文章图片';
                return self::resJson(0, '上传成功', $data);
            } else {
                return self::resJson(1, '上传失败');
            }
        }
        return self::resJson(1, '没有要上传的文件');
    }
}
