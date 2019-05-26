<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\TraitResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    use TraitResource;

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        self::$model = Admin::class;
        self::$controlName = 'admin';
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * Description:
     * User: VIjay
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
            if (!empty($account)) {
                $where[] = ['account', 'like', '%' . $account . '%'];
            }
            if (!empty($username)) {
                $where[] = ['username', 'like', '%' . $username . '%'];
            }
            $list = self::$model::where($where)->get();
            $res = self::getPageData($list, $page, $limit);
            return self::resJson(0, '获取成功', $res['data'], [
                    'count' => $res['count']]
            );
        }
        return view('admin.' . self::$controlName . '.index', [
            'control_name' => self::$controlName
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'account' => 'required|unique:admins|max:255',
            'username' => 'required',
            'password' => 'required',
        ]);
        $model = new self::$model;
        $model->account = $request->account;
        $model->username = $request->username;
        $model->password = Hash::make($request->password);
        $model->save();
        return $this->resJson(0, '操作成功');
    }

    public function update(Request $request, $id)
    {
        $info = self::find($id);
        if (empty($info)) {
            return $this->resJson(1, '没有该条记录');
        }
        $res = $info->edit($request->param());
        if ($res === false) {
            $this->resJson(1, $info->getError());
        }
        $this->resJson(0, '操作成功');
    }
}
