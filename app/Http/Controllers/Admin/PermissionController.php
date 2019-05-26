<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Traits\TraitResource;
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

    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);
            $list = self::$model::where([])->get();
            $data = self::getPageData($list, $page, $limit);
            self::resJson(0, '获取成功', $data);
        }
        dd('get');
        return view('admin.' . self::$controlName . '.index');
    }

}
