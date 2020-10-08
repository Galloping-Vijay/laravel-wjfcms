<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\TraitResource;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    use TraitResource;

    public function __construct()
    {
        self::$model = Admin::class;
        self::$controlName = 'admin';
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * Description:
     * User: Vijay
     * Date: 2019/5/26
     * Time: 16:33
     */
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);
            $where = [];
            $account = $request->input('account', '');
            $username = $request->input('username', '');
            $sex = $request->input('sex', '');
            $status = $request->input('status', '');
            $delete = $request->input('delete', 0);
            if ($account != '') {
                $where[] = ['account', 'like', '%' . $account . '%'];
            }
            if ($username != '') {
                $where[] = ['username', 'like', '%' . $username . '%'];
            }
            if ($sex != '') {
                $where[] = ['sex', '=', $sex];
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
            $res = self::getPageData($list, $page, $limit);
            return self::resJson(0, '获取成功', $res['data'], [
                    'count' => $res['count']]
            );
        }
        return view('admin.' . self::$controlName . '.index', [
            'control_name' => self::$controlName,
            'sex_list' => Admin::$sex,
            'delete_list' => Admin::$delete,
            'status_list' => Admin::$status,
        ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/5/27
     * Time: 22:28
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $roleList = Role::where('status', '=', 1)->select('id', 'name')->get();
        return view('admin.' . self::$controlName . '.create',
            [
                'control_name' => self::$controlName,
                'role_list' => $roleList
            ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/5/27
     * Time: 22:28
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $res = Admin::create($request->input());
        if ($res !== true) {
            return $this->resJson(1, $res);
        } else {
            return $this->resJson(0, '操作成功');
        }
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
        $roleList = Role::where('status', '=', 1)->select('id', 'name')->get();
        $selectedRole = [];
        if ($info->role_names) {
            $selectedRole = explode(',', $info->role_names);
        }
        return view('admin.' . self::$controlName . '.edit', [
            'info' => $info,
            'control_name' => self::$controlName,
            'role_list' => $roleList,
            'selected_role' => $selectedRole
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
        $res = $info->update($request->input());
        if ($res !== true) {
            return $this->resJson(1, $info->getError());
        } else {
            return $this->resJson(0, '操作成功');
        }
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/7/28
     * Time: 14:30
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function info()
    {
        $roleList = Role::where('status', '=', 1)->select('id', 'name')->get();
        $selectedRole = [];
        if (Auth::user()->role_names) {
            $selectedRole = explode(',', Auth::user()->role_names);
        }
        return view('admin.' . self::$controlName . '.info', [
            'control_name' => self::$controlName,
            'role_list' => $roleList,
            'selected_role' => $selectedRole
        ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/7/29
     * Time: 23:00
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function password(Request $request)
    {
        if ($request->isMethod('post')) {
            $info = self::$model::find($request->id);
            if (empty($info)) {
                return $this->resJson(1, '没有该条记录');
            }
            $res = $info->setmypass($request->input());
            if ($res !== true) {
                return $this->resJson(1, $info->getError());
            } else {
                return $this->resJson(0, '操作成功，请重新登录。');
            }
        }
        return view('admin.' . self::$controlName . '.password', [
            'control_name' => self::$controlName,
        ]);
    }
}
