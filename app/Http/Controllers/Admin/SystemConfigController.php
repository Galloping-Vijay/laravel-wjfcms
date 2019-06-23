<?php

namespace App\Http\Controllers\admin;

use App\Http\Traits\TraitResource;
use App\Models\SystemConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SystemConfigController extends Controller
{
    use TraitResource;

    //基本设置
    public function basal(Request $request)
    {
        if ($request->isMethod('post')) {//REQUEST_SCHEME
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
            //全部提交
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
