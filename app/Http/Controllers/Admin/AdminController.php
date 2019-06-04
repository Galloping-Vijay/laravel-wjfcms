<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\TraitResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
                    $list = self::$model::onlyTrashed()->where($where)->get();
                    break;
                case '2':
                    $list = self::$model::withTrashed()->where($where)->get();
                    break;
                default:
                    $list = self::$model::where($where)->get();
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
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * Description:
     * User: Vijay
     * Date: 2019/5/26
     * Time: 21:30
     */
    public function store(Request $request)
    {
        $data = $request->input();
        Validator::make(
            $data, [
                'username' => 'required|string|max:255',
                'account' => 'required|max:100|unique:admins',
                'password' => 'required|string|min:6',
            ]
        )->validate();
        $model = new self::$model;
        $data['password'] = Hash::make($request->password);
        $model::create($data);
        return $this->resJson(0, '操作成功');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * Description:
     * User: Vijay
     * Date: 2019/5/26
     * Time: 21:49
     */
    public function update(Request $request)
    {
        $info = self::$model::find($request->id);
        if (empty($info)) {
            return $this->resJson(1, '没有该条记录');
        }
        $data = $request->input();
        if (isset($request->password)) {
            $data['password'] = Hash::make($request->password);
        }
        $res = $info->update($data);
        return $this->resJson(0, '操作成功', $res);
    }

}
