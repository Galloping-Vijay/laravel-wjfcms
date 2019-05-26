<?php
/**
 * Description:
 * Created by PhpStorm.
 * User: VIjay
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
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Description:展示列表
     * User: VIjay
     * Date: 2019/5/26
     * Time: 14:01
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
        return view('admin.' . self::$controlName . '.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Description:
     * User: VIjay
     * Date: 2019/5/26
     * Time: 17:31
     */
    public function create()
    {
        return view('admin.' . self::$controlName . '.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * Description:
     * User: VIjay
     * Date: 2019/5/26
     * Time: 21:30
     */
    public function store(Request $request)
    {
        $model = new self::$model;
        $model::create($request->input());
        return $this->resJson(0, '操作成功');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * Description:
     * User: VIjay
     * Date: 2019/5/26
     * Time: 21:30
     */
    public function show($id)
    {
        $info = self::$model::find($id);
        return $this->resJson(0, '操作成功', $info);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Description:
     * User: VIjay
     * Date: 2019/5/26
     * Time: 21:32
     */
    public function edit($id)
    {
        $info = self::$model::find($id);
        return view('admin.' . self::$controlName . '.edit', [
            'info' => $info
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * Description:
     * User: VIjay
     * Date: 2019/5/26
     * Time: 21:20
     */
    public function update(Request $request, $id)
    {
        $info = self::$model::find($id);
        if (empty($info)) {
            return $this->resJson(1, '没有该条记录');
        }
        $res = $info->update($request->input());
        return $this->resJson(0, '操作成功', $res);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function restore($id)
    {

    }

    public function forceDelete($id)
    {

    }

    /**
     * @return null|string
     * Description:
     * User: VIjay
     * Date: 2019/5/26
     * Time: 14:47
     */
    protected static function getViewName()
    {
        return self::$controlName;
    }

    /**
     * @return null|string
     * Description:
     * User: VIjay
     * Date: 2019/5/26
     * Time: 14:46
     */
    protected static function getModel()
    {
        return self::$model;
    }

    /**
     * @param object|array $list 获取的数据
     * @param int $page 当前页数
     * @param int $limit 没有数量
     * @return array
     * Description:获取分页数据
     * User: VIjay
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
     * @param int $code
     * @param string $msg
     * @param null $data
     * @param array $additional
     * @param array $header
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * Description:
     * User: VIjay
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