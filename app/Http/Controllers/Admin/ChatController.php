<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\TraitResource;
use App\Models\Chat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    use TraitResource;

    public function __construct()
    {
        self::$model = Chat::class;
        self::$controlName = 'chat';
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/7/9
     * Time: 22:50
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);
            $where = [];
            $content = $request->input('content', '');
            $delete = $request->input('delete', 0);
            if ($content != '') {
                $where[] = ['content', 'like', '%' . $content . '%'];
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
}
