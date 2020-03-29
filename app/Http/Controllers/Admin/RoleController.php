<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\TraitResource;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use TraitResource;

    public function __construct()
    {
        self::$model = Role::class;
        self::$controlName = 'role';
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/6/4
     * Time: 21:45
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
            $description = $request->input('description', '');
            $guard_name = $request->input('guard_name', '');
            $status = $request->input('status', '');
            $delete = $request->input('delete', 0);
            if ($name != '') {
                $where[] = ['name', 'like', '%' . $name . '%'];
            }
            if ($description != '') {
                $where[] = ['description', 'like', '%' . $description . '%'];
            }
            if ($guard_name != '') {
                $where[] = ['guard_name', '=', $guard_name];
            }
            if ($status != '') {
                $where[] = ['status', '=', $status];
            }
            switch ($delete) {
                case '1':
                    $list = Role::onlyTrashed()->where($where)->orderBy('id', 'desc')->get();
                    break;
                case '2':
                    $list = Role::withTrashed()->where($where)->orderBy('id', 'desc')->get();
                    break;
                default:
                    $list = Role::where($where)->orderBy('id', 'desc')->get();
                    break;
            }
            $res = self::getPageData($list, $page, $limit);
            return self::resJson(0, '获取成功', $res['data'], [
                    'count' => $res['count']]
            );
        }
        return view('admin.' . self::$controlName . '.index', [
            'control_name' => self::$controlName,
            'delete_list' => Role::$delete,
            'guard_name_list' => Role::$guard_name_list,
        ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/6/4
     * Time: 22:21
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.' . self::$controlName . '.create', [
            'guard_name_list' => Role::$guard_name_list,
            'control_name' => self::$controlName,
        ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/6/4
     * Time: 22:35
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $info = self::$model::find($id);
        return view('admin.' . self::$controlName . '.edit', [
            'info' => $info,
            'guard_name_list' => Role::$guard_name_list,
            'control_name' => self::$controlName,
        ]);
    }
}
