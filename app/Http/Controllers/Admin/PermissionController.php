<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\TraitResource;
use App\Models\ModelHasPermissions;
use App\Models\RoleHasPermissions;
use Illuminate\Http\Request;
use App\Models\Permissions;
use Illuminate\Support\Facades\DB;

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
     * User: Vijay
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
            'guard_name_list' => Permissions::$guard_name_list,
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
        $superclass_id = request()->input('superclass_id', 0);
        return view('admin.' . self::$controlName . '.create', [
            'guard_name_list' => Permissions::$guard_name_list,
            'superclass_id' => $superclass_id,
            'control_name' => self::$controlName,
        ]);
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
            'guard_name_list' => Permissions::$guard_name_list,
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
        //当更新时,级别改变,下级要相应的调整,待完善
        if (isset($request->level)) {
            if ($info->level != $request->level) {
                return $this->resJson(1, '暂时不支持修改级别');
            }
        }
        try {
            $res = $info->update($request->input());
            return $this->resJson(0, '操作成功', $res);
        } catch (\Exception $e) {
            return $this->resJson(1, $e->getMessage());
        }
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/5/31
     * Time: 22:44
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();
            //存在子菜单
            $subMenu = self::$model::where('parent_id', $request->id)->first();
            if (!empty($subMenu)) {
                DB::rollBack();
                return $this->resJson(1, '存在子菜单,不能删除');
            }
            //存在角色中
            $resRolePermissions = RoleHasPermissions::where('permission_id', $request->id)->first();
            if (!empty($resRolePermissions)) {
                DB::rollBack();
                return $this->resJson(1, '存在改权限的角色,请先删除角色中的次权限');
            }
            //存在用户权限中
            $resadminPermissions = ModelHasPermissions::where('permission_id', $request->id)->first();
            if (!empty($resadminPermissions)) {
                DB::rollBack();
                return $this->resJson(1, '存在改权限的角色,请先删除角色中的次权限');
            }
            $res = self::$model::destroy($request->id);
            if ($res != true) {
                DB::rollBack();
                return $this->resJson(1, '删除失败');
            }
            DB::commit();
            return $this->resJson(0, '操作成功', $res);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->resJson(1, $e->getMessage());
        }

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
        $menu = self::$model::where('guard_name', $guard_name)->select('id', 'name', 'url', 'level', 'parent_id')->orderBy('level', 'asc')->get()->toArray();
        $list = self::$model::array2level($menu);
        return $this->resJson(0, '请求成功', $list);
    }

    /**
     * Description:获取dtree权限树结构
     * User: Vijay
     * Date: 2019/6/7
     * Time: 0:22
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function permissionTree(Request $request)
    {
        $guard_name = $request->input('guard_name', 'admin');
        $role_id = $request->input('role_id', 0);
        $permissions = self::$model::where('guard_name', $guard_name)->select('id', 'name as title', 'level', 'parent_id')->orderBy('level', 'asc')->get()->toArray();
        $list = Permissions::getAuthTree($permissions, 0, true, $role_id);
        return $this->resJson(0, '操作成功', $list, [
            'status' => [
                'code' => 200,
                'message' => '操作成功'
            ]
        ]);
    }

}
