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
     * Date: 2019/5/27
     * Time: 22:29
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);
            $list = self::$model::where([])->get();
            $data = self::getPageData($list, $page, $limit);
            self::resJson(0, '获取成功', $data);
        }
        return view('admin.' . self::$controlName . '.index');
    }

}
