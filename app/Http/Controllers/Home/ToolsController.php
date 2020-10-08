<?php

namespace App\Http\Controllers\Home;

use App\Libs\api\driver\QqAi;
use App\Libs\api\driver\Tuling;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Vijay\Curl\Curl;

class ToolsController extends Controller
{
    /**
     * Description:
     * User: Vijay
     * Date: 2019/11/7
     * Time: 22:08
     */
    public function linkSubmit()
    {
        $baseUrl = env('BAIDU_SITE_BASE');
        $urls = [
            $baseUrl,
            $baseUrl . 'register',
            $baseUrl . 'login',
            $baseUrl . 'chat',
            $baseUrl . 'admin/login',
        ];
        $artIds = cache::remember('artIds', 86400, function () {
            return Article::query()->pluck('id');
        });
        if ($artIds) {
            foreach ($artIds as $ak => $av) {
                $urls[] = $baseUrl . 'article/' . $av;
            }
        }
        $categoryIds = Cache::remember('categoryIds', 86400, function () {
            return Category::query()->pluck('id');
        });
        if ($categoryIds) {
            foreach ($categoryIds as $ck => $cv) {
                $urls[] = $baseUrl . 'category/' . $cv;
            }
        }
        $tagIds = Cache::remember('tagIds', 86400, function () {
            return Tag::query()->pluck('id');
        });
        if ($tagIds) {
            foreach ($tagIds as $tk => $tv) {
                $urls[] = $baseUrl . 'tag/' . $tv;
            }
        }
        $api = env('BAIDU_SITE_API');
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $api,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => implode("\n", $urls),
            CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        $res = json_decode($result, true);
        if (isset($res['error'])) {
            return response()->json($res);
        } elseif (!isset($res['remain'])) {
            return response()->json([
                'code' => 1,
                'msg' => '请求失败'
            ]);
        }
        Cache::put('link_remain', $res['remain']);
        $remainArr = [
            'link_remain' => Cache::get('link_remain')
        ];
        $res = array_merge($res, $remainArr);
        return response()->json($res);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/12/1
     * Time: 21:03
     * @return \Illuminate\Http\JsonResponse
     */
    public function tuling()
    {
        //$res = Tuling::handle()->param('https://www.choudalao.com/images/config/avatar.jpg')->answer();
        $res = QqAi::handle()->param('https://www.choudalao.com/images/config/avatar.jpg')->answer();
        return response()->json($res);
    }

    /**
     * Description:验证邮箱
     * User: Vijay
     * Date: 2019/12/1
     * Time: 21:39
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmailCode(Request $request)
    {
        $email = $request->input('email');
        $info = User::where('email', $email)->first();
        if ($info) {
            $data = [
                'code' => 1,
                'msg' => '该邮箱已被注册',
                'data' => [],
                'create_time' => date('Y-m-d H:i:s', time())
            ];
            return response()->json($data);
        }
        //发送验证码
        $code = mt_rand(1000, 9999);
        Cache::put($email, $code, 60);
        $subject = env('MAIL_FROM_NAME');
        $msg = '【臭大佬】邮箱验证码：' . $code . '，如非本人操作，请忽略本消息。';
        try {
            Mail::raw($msg, function ($message) use ($email, $subject) {
                $message->to($email)->subject($subject);
            });
            //错误处理
            if (count(Mail::failures()) < 1) {
                $data = [
                    'code' => 0,
                    'msg' => '发送邮件成功，请查收！',
                    'data' => [],
                    'create_time' => date('Y-m-d H:i:s', time())
                ];
                return response()->json($data);
            } else {
                $data = [
                    'code' => 1,
                    'msg' => '邮箱验证码发送失败,请联系臭大佬(微信:wjf1937832819)',
                    'data' => [],
                    'create_time' => date('Y-m-d H:i:s', time())
                ];
                return response()->json($data);
            }
        } catch (\Exception $e) {
            $data = [
                'code' => 1,
                'msg' => '邮箱配置错误，请联系管理员',
                'data' => [],
                'create_time' => date('Y-m-d H:i:s', time())
            ];
            return response()->json($data);
        }
    }
}
