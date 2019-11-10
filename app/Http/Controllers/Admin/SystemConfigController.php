<?php

namespace App\Http\Controllers\admin;

use App\Http\Traits\TraitResource;
use App\Models\SystemConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SystemConfigController extends Controller
{
    use TraitResource;

    /**
     * Description:基本设置
     * User: Vijay
     * Date: 2019/6/25
     * Time: 0:12
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function basal(Request $request)
    {
        if ($request->isMethod('post')) {
            //上传文件
            if ($request->hasFile('file')) {
                $date = date('Ymd');
                $path = $request->file('file')->store('', 'uploads');
                if ($path) {
                    $fileUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/uploads/' . $date . '/' . $path;
                    return self::resJson(0, '上传成功', $fileUrl);
                } else {
                    return self::resJson(1, '上传失败');
                }
            }
            try {
                //全部提交
                DB::beginTransaction();
                foreach ($request->input() as $key => $val) {
                    if (empty($val)) {
                        continue;
                    }
                    if (!in_array($key, SystemConfig::$keysList)) {
                        continue;
                    }
                    $info = SystemConfig::where('key', $key)->first();
                    if (empty($info)) {
                        continue;
                    }
                    if ($key == 'site_tongji') {
                        $info->value = htmlspecialchars($val);
                    } else {
                        $info->value = $val;
                    }
                    $info->save();
                    $info = null;
                    SystemConfig::getConfigCache($key, false);
                }
                DB::commit();
                return self::resJson(0, '操作成功');
            } catch (\Exception $e) {
                DB::rollBack();
                return self::resJson(1, $e->getMessage());
            }
        }
        $list = SystemConfig::where([['status', '=', '1'],
            ['config_type', '=', '0']])->select('key', 'value')->get();
        $config = [];
        foreach ($list as $key => $val) {
            $config[$val['key']] = $val['value'];
        }
        return view('admin.systemConfig.basal', [
            'config' => $config,
        ]);
    }
}
