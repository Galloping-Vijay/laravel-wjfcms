<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\TraitResource;
use App\Http\Traits\TraitUpload;
use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    use TraitResource;
    use TraitUpload;

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
                $where[] = ['articles.title', 'like', '%' . $title . '%'];
            }
            if ($author != '') {
                $where[] = ['articles.author', 'like', '%' . $author . '%'];
            }
            if ($category_id != '') {
                $where[] = ['articles.category_id', '=', $category_id];
            }
            if ($status != '') {
                $where[] = ['articles.status', '=', $status];
            }
            switch ($delete) {
                case '1':
                    $list = Article::onlyTrashed()
                        ->where($where)
                        ->leftJoin('categories', 'categories.id', '=', 'articles.category_id')
                        ->select('articles.*', 'categories.name as cate_name')
                        ->orderBy('created_at', 'desc')
                        ->get();
                    break;
                case '2':
                    $list = Article::withTrashed()
                        ->where($where)
                        ->leftJoin('categories', 'categories.id', '=', 'articles.category_id')
                        ->select('articles.*', 'categories.name as cate_name')
                        ->orderBy('created_at', 'desc')
                        ->get();
                    break;
                default:
                    $list = Article::where($where)
                        ->leftJoin('categories', 'categories.id', '=', 'articles.category_id')
                        ->select('articles.*', 'categories.name as cate_name')
                        ->orderBy('created_at', 'desc')
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
        $data = $request->input();
        if (isset($data['tags']) && !empty($data['tags'])) {
            $keywordsArr = [];
            foreach ($data['tags'] as $key => $val) {
                $keywordsArr[] = $key;
            }
            $data['keywords'] = implode(',', $keywordsArr);
        }
        if (isset($data['editor-html-code'])) {
            $data['content'] = htmlspecialchars($data['editor-html-code']);
        }
        $data['markdown'] = $data['editor-html-doc'];
        $data['cover'] = $data['cover'] ?? $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/images/config/default-img.jpg';
        try {
            $model::create($data);
            return $this->resJson(0, '操作成功');
        } catch (\Exception $e) {
            return $this->resJson(1, $e->getMessage());
        }
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/6/30
     * Time: 16:40
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $info = self::$model::find($id);
        if (!empty($info->keywords)) {
            $info->tags = explode(',', $info->keywords);
        }
        $tree = Category::select('id', 'name', 'pid')->orderBy('id', 'asc')->get()->toArray();
        $info->content = htmlspecialchars_decode($info->content);
        $category_list = Category::array2level($tree);
        $tags_list = Tag::all();
        return view('admin.' . self::$controlName . '.edit', [
            'info' => $info,
            'control_name' => self::$controlName,
            'category_list' => $category_list,
            'tags_list' => $tags_list
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * Description:
     * User: Vijay
     * Date: 2019/5/26
     * Time: 21:20
     */
    public function update(Request $request)
    {
        $info = self::$model::find($request->id);
        if (empty($info)) {
            return $this->resJson(1, '没有该条记录');
        }
        $data = $request->input();
        if (isset($data['tags']) && !empty($data['tags'])) {
            $keywordsArr = [];
            foreach ($data['tags'] as $key => $val) {
                $keywordsArr[] = $key;
            }
            $data['keywords'] = implode(',', $keywordsArr);
        }
        if (isset($data['editor-html-code']) && !empty($data['editor-html-code'])) {
            $data['content'] = htmlspecialchars($data['editor-html-code']);
        }
        if (isset($data['editor-html-doc']) && !empty($data['editor-html-doc'])) {
            $data['markdown'] = $data['editor-html-doc'];
        }
        if (isset($data['cover']) && !empty($data['cover'])) {
            $data['cover'] = $data['cover'] ?? $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/images/config/default-img.jpg';
        }
        try {
            $res = $info->update($data);
            return $this->resJson(0, '操作成功', $res);
        } catch (\Exception $e) {
            return $this->resJson(1, $e->getMessage());
        }
    }

    /**
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2019/08/26
     * Time: 11:59
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();
            //如果存在评论
            $relation = Comment::where('article_id', $request->id)->first();
            if (!empty($relation)) {
                DB::rollBack();
                return $this->resJson(1, '该文章存在评论,不能删除');
            }
            $res = self::$model::destroy($request->id);
            DB::commit();
            return $this->resJson(0, '操作成功', $res);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->resJson(1, $e->getMessage());
        }
    }
}
