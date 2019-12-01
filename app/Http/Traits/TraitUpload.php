<?php
/**
 * Description:上传文件
 * Created by PhpStorm.
 * User: Vijay
 * Date: 2019/11/6
 * Time: 23:23
 */

namespace App\Http\Traits;

use Illuminate\Http\Request;

trait TraitUpload
{
    /**
     * Description:上传文件
     * User: Vijay
     * Date: 2019/11/6
     * Time: 23:23
     * @param string $name form表单中的name
     * @param string $path 文件保存的目录 相对于 /public 目录
     * @param array $allowExtension 允许上传的文件后缀
     * @param bool $childPath 是否按日期创建目录
     * @return array
     */
    public static function fileUpload($name, $path = 'uploads', $allowExtension = [], $childPath = true)
    {
        // 判断请求中是否包含name=file的上传文件
        if (!request()->hasFile($name)) {
            $data = [
                'status_code' => 401,
                'message' => '上传文件为空'
            ];
            return $data;
        }
        $file = request()->file($name);
        // 判断是否多文件上传
        if (!is_array($file)) {
            $file = [$file];
        }
        // 过滤所有的.符号
        $path = str_replace('.', '', $path);
        // 先去除两边空格
        $path = trim($path, '/');
        // 判断是否需要生成日期子目录
        $path = $childPath ? $path . '/' . date('Ymd') : $path;
        // 获取目录的绝对路径
        $publicPath = public_path($path . '/');
        // 如果目录不存在；先创建目录
        is_dir($publicPath) || mkdir($publicPath, 0755, true);
        // 上传成功的文件
        $success = [];
        // 循环上传
        foreach ($file as $k => $v) {
            //判断文件上传过程中是否出错
            if (!$v->isValid()) {
                $data = [
                    'status_code' => 500,
                    'message' => '文件上传出错'
                ];
                return $data;
            }
            // 获取上传的文件名
            $oldName = $v->getClientOriginalName();
            // 获取文件后缀
            $extension = strtolower($v->getClientOriginalExtension());
            // 判断是否是允许的文件类型
            if (!empty($allowExtension) && !in_array($extension, $allowExtension)) {
                $data = [
                    'status_code' => 500,
                    'message' => $oldName . '的文件类型不被允许'
                ];
                return $data;
            }
            // 组合新的文件名
            $newName = uniqid() . '.' . $extension;
            // 判断上传是否失败
            if (!$v->move($publicPath, $newName)) {
                $data = [
                    'status_code' => 500,
                    'message' => '保存文件失败'
                ];
                return $data;
            } else {
                $success[] = [
                    'name' => $oldName,
                    'path' => '/' . $path . '/' . $newName
                ];
            }
        }
        //上传成功
        $data = [
            'status_code' => 200,
            'message' => '上传成功',
            'data' => $success
        ];
        return $data;
    }

    /**
     * Description:上传图片
     * User: Vijay
     * Date: 2019/12/1
     * Time: 9:54
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request)
    {
        $date = date('Ymd');
        //复制到编辑器的图片,直接base64的图片
        if ($request->input('base64_img')) {
            //正则匹配
            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $request->input('base64_img'), $result)) {
                //获取图片
                $base64_img = base64_decode(str_replace($result[1], '', $request->input('base64_img')));
                //设置名称
                $src = date("YmdHis") . getRandomStr(6) . '.png';
                //设置路径
                $path = 'uploads/' . $date;
                //拼接完整文件路径
                $pathSrc = $path . '/' . $src;
                //路径检测和创建
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                //存储图片
                file_put_contents($pathSrc, $base64_img);//保存图片，返回的是字节数
                //设置返回值
                $data['src'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . $path . '/' . $src;
                $data['title'] = '文章图片';
                if (file_exists($pathSrc)) {
                    waterMarkImage($data['src']);
                    return self::resJson(0, '上传成功', $data);
                }
                return self::resJson(1, '上传失败');
            }
            return self::resJson(1, '不是base64格式');
        } elseif ($request->hasFile('file')) {
            //文件请求方式
            $path = $request->file('file')->store('', 'uploads');
            if ($path) {
                $data['src'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/uploads/' . $date . '/' . $path;
                $data['title'] = '文章图片';
                waterMarkImage($data['src'], true);
                return self::resJson(0, '上传成功', $data);
            } else {
                return self::resJson(1, '上传失败');
            }
        } elseif ($request->hasFile('editormd-image-file')) {
            //markdown添加图片
            $result = self::imageUpload('editormd-image-file');
            if ($result['status_code'] === 200) {
                $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $result['data'][0]['path'];
                waterMarkImage($url);
                $data = [
                    'success' => 1,
                    'message' => $result['message'],
                    'url' => $url,
                ];
            } else {
                $data = [
                    'success' => 0,
                    'message' => $result['message'],
                    'url' => '',
                ];
            }
            return response()->json($data);
        }
        return self::resJson(1, '没有要上传的文件');
    }

    /**
     * Description:上传图片
     * User: Vijay
     * Date: 2019/11/6
     * Time: 23:25
     * @param $name
     * @param string $path
     * @return array
     */
    public static function imageUpload($name, $path = 'uploads')
    {
        $allowExtension = [
            "jpg", "jpeg", "gif", "png", "bmp", "webp"
        ];
        return self::fileUpload($name, $path, $allowExtension);
    }
}
