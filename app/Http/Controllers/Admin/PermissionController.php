<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\TraitResource;
use Illuminate\Http\Request;
use App\Models\Permissions;

class PermissionController extends Controller
{
    use TraitResource;

    /**
     * PermissionController constructor.
     */
    public function __construct()
    {
        self::$model = Permissions::class;
        self::$controlName = 'permission';
    }

    /**
     * Description:
     * User: VIjay
     * Date: 2019/5/30
     * Time: 21:30
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $where = [];
            $name = $request->input('name', '');
            $guard_name = $request->input('guard_name', '');
            $display_menu = $request->input('display_menu', '');
            $delete = $request->input('delete', 0);
            if ($name != '') {
                $where[] = ['name', 'like', '%' . $name . '%'];
            }
            if ($guard_name != '') {
                $where[] = ['guard_name', 'like', '%' . $guard_name . '%'];
            }
            if ($display_menu != '') {
                $where[] = ['display_menu', '=', $display_menu];
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
            return self::resJson(0, '获取成功', $list);
        }
        return view('admin.' . self::$controlName . '.index', [
            'control_name' => self::$controlName,
            'display_menu' => Permissions::$display_menu,
            'delete_list' => Permissions::$delete,
        ]);
    }

    /**
     * Instructions:
     * Author: Vijay  <1937832819@qq.com>
     * Time: 2019/5/31 10:13
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.' . self::$controlName . '.create', [
            'guard_name_list' => Permissions::$guard_name_list
        ]);
    }

    /**
     * Instructions:获取下拉菜单
     * Author: Vijay  <1937832819@qq.com>
     * Time: 2019/5/31 11:15
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function menu(Request $request)
    {
        $guard_name = $request->input('guard_name', 'admin');
        $menu = Permissions::where('guard_name', $guard_name)->select('id', 'name', 'url', 'level', 'parent_id')->orderBy('level', 'asc')->get()->toArray();
        $list = Permissions::array2level($menu);
        return $this->resJson(0, '请求成功', $list);
    }

}
