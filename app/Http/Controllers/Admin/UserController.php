<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\TraitResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use TraitResource;

    public function __construct()
    {
        self::$model       = User::class;
        self::$controlName = 'user';
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
            $page   = $request->input('page', 1);
            $limit  = $request->input('limit', 10);
            $where  = [];
            $name   = $request->input('name', '');
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
            'delete_list'  => self::$model::$delete,
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
        $model = new self::$model;
        try {
            $model::create([
                'name'              => $request->name,
                'email'             => $request->email,
                'password'          => Hash::make($request->password),
                'avatar'            => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/images/config/avatar_l.jpg',
                'email_verified_at' => date('Y-m-d H:i:s'),
            ]);
            return $this->resJson(0, '操作成功');
        } catch (\Exception $e) {
            return $this->resJson(1, $e->getMessage());
        }
    }
}
