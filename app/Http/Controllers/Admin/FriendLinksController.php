<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\TraitResource;
use App\Mail\Alarm;
use App\Models\FriendLink;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class FriendLinksController extends Controller
{
    use TraitResource;

    public function __construct()
    {
        self::$model = FriendLink::class;
        self::$controlName = 'friendLinks';
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
                    $list = self::$model::onlyTrashed()->where($where)->orderBy('id', 'desc')->get();
                    break;
                case '2':
                    $list = self::$model::withTrashed()->where($where)->orderBy('id', 'desc')->get();
                    break;
                default:
                    $list = self::$model::where($where)->orderBy('id', 'desc')->get();
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

    /**
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2019/12/18
     * Time: 15:41
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $info = self::$model::find($request->id);
        if (empty($info)) {
            return $this->resJson(1, '没有该条记录');
        }
        try {
            $res = $info->update($request->input());
            if ($info->client_ip) {
                $status = $request->input('status', '');
                if ($status == 1) {
                    $msg = '您的友链申请已通过,详情请查看https://www.choudalao.com';
                    Mail::to($info->email)->send(new Alarm($msg));
                } elseif ($status == 0) {
                    $msg = '您的友链申请已被关闭,详情请查看https://www.choudalao.com';
                    Mail::to($info->email)->send(new Alarm($msg));
                }
            }
            return $this->resJson(0, '操作成功', $res);
        } catch (\Exception $e) {
            return $this->resJson(1, $e->getMessage());
        }
    }

}
