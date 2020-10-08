打开任意一个控制器（如：TagController ），会发现，里面就一个index方法，但是增删改查功能却都具备。

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\TraitResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    use TraitResource;

    public function __construct()
    {
        self::$model = Tag::class;
        self::$controlName = 'tag';
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/7/9
     * Time: 22:53
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);
            $where = [];
            $name = $request->input('name', '');
            $delete = $request->input('delete', 0);
            if ($name != '') {
                $where[] = ['name', 'like', '%' . $name . '%'];
            }
            switch ($delete) {
                case '1':
                    $list = self::$model::onlyTrashed()->where($where)->get();
                    break;
                case '2':
                    $list = self::$model::withTrashed()->where($where)->get();
                    break;
                default:
                    $list = self::$model::where($where)->get();
                    break;
            }
            $res = self::getPageData($list, $page, $limit);
            return self::resJson(0, '获取成功', $res['data'], [
                    'count' => $res['count']]
            );
        }
        return view('admin.' . self::$controlName . '.index', [
            'control_name' => self::$controlName,
            'delete_list' => self::$model::$delete,
        ]);
    }
}

```

找到TraitResource所在目录App\Http\Traits\TraitResource，具体的操作都在这里面。

```php
<?php
/**
 * Description:资源操作
 * Created by PhpStorm.
 * User: Vijay
 * Date: 2019/5/26
 * Time: 12:06
 */

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

trait TraitResource
{
    /**
     * @var null|string
     */
    private static $controlName = null;

    /**
     * @var null|string
     */
    private static $model = null;

    /**
     * Description:展示列表
     * User: Vijay
     * Date: 2019/5/27
     * Time: 22:28
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);
            $where = [];
            $list = self::$model::where($where)->get();
            $data = self::getPageData($list, $page, $limit);
            return response($data);
        }
        return view('admin.' . self::$controlName . '.index',
            [
                'control_name' => self::$controlName,
            ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/5/27
     * Time: 22:28
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.' . self::$controlName . '.create',
            [
                'control_name' => self::$controlName,
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
            $model::create($request->input());
            return $this->resJson(0, '操作成功');
        } catch (\Exception $e) {
            return $this->resJson(1, $e->getMessage());
        }
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/5/27
     * Time: 22:28
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $info = self::$model::find($id);
        return $this->resJson(0, '操作成功', $info);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/5/27
     * Time: 22:29
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $info = self::$model::find($id);
        return view('admin.' . self::$controlName . '.edit', [
            'info' => $info,
            'control_name' => self::$controlName,
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
        try {
            $res = $info->update($request->input());
            return $this->resJson(0, '操作成功', $res);
        } catch (\Exception $e) {
            return $this->resJson(1, $e->getMessage());
        }
    }

    /**
     * Description:删除
     * User: Vijay
     * Date: 2019/5/27
     * Time: 22:11
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $res = self::$model::destroy($request->id);
            return $this->resJson(0, '操作成功', $res);
        } catch (\Exception $e) {
            return $this->resJson(1, $e->getMessage());
        }
    }

    /**
     * Description:恢复数据
     * User: Vijay
     * Date: 2019/5/27
     * Time: 22:18
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        $info = self::$model::onlyTrashed()->find($request->id);
        if (empty($info)) {
            return $this->resJson(1, '没有该条记录');
        }
        try {
            $res = $info->restore();
            return $this->resJson(0, '操作成功', $res);
        } catch (\Exception $e) {
            return $this->resJson(1, $e->getMessage());
        }
    }

    /**
     * Description:彻底删除
     * User: Vijay
     * Date: 2019/5/27
     * Time: 22:18
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function forceDelete(Request $request)
    {
        $info = self::$model::onlyTrashed()->find($request->id);
        if (empty($info)) {
            return $this->resJson(1, '没有该条记录');
        }
        try {
            $res = $info->forceDelete();
            return $this->resJson(0, '操作成功', $res);
        } catch (\Exception $e) {
            return $this->resJson(1, $e->getMessage());
        }
    }

    /**
     * @param object|array $list 获取的数据
     * @param int $page 当前页数
     * @param int $limit 没有数量
     * @return array
     * Description:获取分页数据
     * User: Vijay
     * Date: 2019/5/26
     * Time: 15:45
     */
    private static function getPageData($list, $page, $limit): array
    {
        if (is_object($list)) {
            $listArr = $list->toArray();
        } elseif (is_array($list)) {
            $listArr = $list;
        } else {
            $listArr = [];
        }
        $count = count($list);
        $item = array_slice($listArr, ($page - 1) * $limit, $limit);
        $paginator = new LengthAwarePaginator($item, $count, $limit, $page);
        return [
            'data' => $paginator->items(),
            'count' => $count
        ];
    }

    /**
     * @param int $code 返回状态码
     * @param string $msg 返回信息
     * @param null $data 返回数据
     * @param array $additional 附加数据
     * @param array $header 头信息
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * Description:返回json数据
     * User: Vijay
     * Date: 2019/5/26
     * Time: 16:41
     */
    protected function resJson($code = 0, $msg = '', $data = null, array $additional = [], array $header = [])
    {
        $result = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ];
        if (count($additional) > 0) {
            foreach ($additional as $key => $val) {
                $result[$key] = $val;
            }
        }
        $result['create_time'] = date('Y-m-d H:i:s', time());
        return response($result)->withHeaders($header);
    }
}
```

 