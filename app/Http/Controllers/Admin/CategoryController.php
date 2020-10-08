<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\TraitResource;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    use TraitResource;

    public function __construct()
    {
        self::$model = Category::class;
        self::$controlName = 'category';
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/6/27
     * Time: 20:31
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $where = [];
            $name = $request->input('name', '');
            $status = $request->input('status', '');
            $delete = $request->input('delete', 0);
            if ($name != '') {
                $where[] = ['name', 'like', '%' . $name . '%'];
            }
            if ($status != '') {
                $where[] = ['status', '=', $status];
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
     * Date: 2019/6/29
     * Time: 11:51
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $superclass_id = request()->input('superclass_id', 0);
        $tree = self::$model::select('id', 'name', 'pid')->orderBy('id', 'asc')->get()->toArray();
        $list = self::$model::array2level($tree);
        return view('admin.' . self::$controlName . '.create', [
            'superclass_id' => $superclass_id,
            'control_name' => self::$controlName,
            'list' => $list
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
        $tree = self::$model::select('id', 'name', 'pid')->orderBy('id', 'asc')->get()->toArray();
        $list = self::$model::array2level($tree);
        return view('admin.' . self::$controlName . '.edit', [
            'info' => $info,
            'control_name' => self::$controlName,
            'list' => $list
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
            $subMenu = self::$model::where('pid', $request->id)->first();
            if (!empty($subMenu)) {
                DB::rollBack();
                return $this->resJson(1, '存在子菜单,不能删除');
            }
            //如果存在文章
            $subArt = Article::where('category_id',$request->id)->first();
            if (!empty($subArt)) {
                DB::rollBack();
                return $this->resJson(1, '该分类存在文章,不能删除');
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
