<?php

namespace App\Http\Controllers\Admin\Wechat;

use App\Http\Requests\KeywordRequest;
use App\Http\Traits\TraitResource;
use App\Models\WxKeyword;
use Illuminate\Http\Request;

class KeywordController extends WechatBase
{
    use TraitResource;

    /**
     * KeywordController constructor.
     */
    public function __construct()
    {
        self::$model = WxKeyword::class;
        self::$controlName = 'weChat/keyword';
        parent::__construct();
    }

    /**
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/03/05
     * Time: 17:41
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
            if ($name != '') {
                $where[] = ['key_name', 'like', '%' . $name . '%'];
            }
            $list = WxKeyword::query()->where($where)->orderBy('id', 'desc')->get();
            $res = self::getPageData($list, $page, $limit);
            return self::resJson(0, '获取成功', $res['data'], [
                    'count' => $res['count']]
            );
        }
        return view('admin.' . self::$controlName . '.index', [
            'control_name' => self::$controlName,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(KeywordRequest $request)
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
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(KeywordRequest $request)
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
}
