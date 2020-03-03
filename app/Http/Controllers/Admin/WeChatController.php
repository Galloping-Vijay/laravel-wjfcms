<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\KeywordRequest;
use App\Models\WxKeyword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

class WeChatController extends Controller
{
    /**
     * Description:
     * User: Vijay
     * Date: 2020/3/3
     * Time: 23:39
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function keywordIndex(Request $request)
    {
        if ($request->isMethod('post')) {
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);
            $where = [];
            $name = $request->input('name', '');
            if ($name != '') {
                $where[] = ['key_name', 'like', '%' . $name . '%'];
            }
            $list = WxKeyword::query()->where($where)->orderBy('id', 'desc')->get();
            $res = self::getPageData($list, $page, $limit);
            return self::resJson(0, '获取成功', $res['data'], [
                    'count' => $res['count']]
            );
        }
        return view('admin.weChat.keywordIndex', [
            'control_name' => 'weChat',
        ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2020/3/3
     * Time: 23:39
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function keywordCreate()
    {
        return view('admin.weChat.keywordCreate',
            [
                'control_name' => 'weChat',
            ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2020/3/3
     * Time: 23:39
     * @param KeywordRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function keywordStore(KeywordRequest $request)
    {
        $data = $request->input();
        try {
            WxKeyword::query()->create($data);
            return $this->resJson(0, '操作成功');
        } catch (\Exception $e) {
            return $this->resJson(1, $e->getMessage());
        }
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2020/3/3
     * Time: 23:40
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function keywordShow($id)
    {
        $info = WxKeyword::query()->find($id);
        return $this->resJson(0, '操作成功', $info);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2020/3/3
     * Time: 23:40
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function keywordEdit($id)
    {
        $info = WxKeyword::query()->find($id);
        return view('admin.weChat.keywordEdit', [
            'info' => $info,
            'control_name' => 'weChat',
        ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2020/3/3
     * Time: 23:40
     * @param KeywordRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function keywordUpdate(KeywordRequest $request)
    {
        $info = WxKeyword::query()->find($request->id);
        if (empty($info)) {
            return $this->resJson(1, '没有该条记录');
        }
        $data = $request->input();
        try {
            $res = $info->update($data);
            return $this->resJson(0, '操作成功', $res);
        } catch (\Exception $e) {
            return $this->resJson(1, $e->getMessage());
        }
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2020/3/3
     * Time: 23:41
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function keywordDestroy(Request $request)
    {
        try {
            $res = WxKeyword::destroy($request->id);
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
