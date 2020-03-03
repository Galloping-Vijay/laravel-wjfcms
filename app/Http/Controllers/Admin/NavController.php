<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\TraitResource;
use App\Models\Nav;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NavController extends Controller
{
    use TraitResource;

    public function __construct()
    {
        self::$model = Nav::class;
        self::$controlName = 'nav';
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/7/9
     * Time: 23:21
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
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
            return self::resJson(0, '获取成功', $list);
        }
        return view('admin.' . self::$controlName . '.index', [
            'control_name' => self::$controlName,
            'delete_list' => self::$model::$delete,
        ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/7/9
     * Time: 23:23
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $superclass_id = request()->input('superclass_id', 0);
        $tree = self::$model::select('id', 'name', 'pid')->orderBy('id', 'asc')->get()->toArray();
        $list = self::$model::array2level($tree);
        return view('admin.' . self::$controlName . '.create', [
            'target_list' => self::$model::$targetList,
            'superclass_id' => $superclass_id,
            'control_name' => self::$controlName,
            'list' => $list
        ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/7/9
     * Time: 23:23
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $info = self::$model::find($id);
        $tree = self::$model::select('id', 'name', 'pid')->orderBy('id', 'asc')->get()->toArray();
        $list = self::$model::array2level($tree);
        return view('admin.' . self::$controlName . '.edit', [
            'info' => $info,
            'target_list' => self::$model::$targetList,
            'control_name' => self::$controlName,
            'list' => $list
        ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/7/9
     * Time: 23:23
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
            return $this->resJson(0, '操作成功', $res);
        } catch (\Exception $e) {
            return $this->resJson(1, $e->getMessage());
        }
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/7/9
     * Time: 23:22
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();
            //存在子菜单
            $subMenu = self::$model::where('pid', $request->id)->first();
            if (!empty($subMenu)) {
                DB::rollBack();
                return $this->resJson(1, '存在子菜单,不能删除');
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
}
