<?php

namespace App\Http\Controllers\Home;

use App\Libs\Tuling;
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
        $urls    = array(
            'https://www.choudalao.com/',
            'https://www.choudalao.com/chat',
            'https://www.choudalao.com/category/1',
            'https://www.choudalao.com/category/3',
            'https://www.choudalao.com/category/4',
            'https://www.choudalao.com/category/5',
            'https://www.choudalao.com/category/6',
            'https://www.choudalao.com/category/7',
            'https://www.choudalao.com/category/8',
            'https://www.choudalao.com/tag/1',
            'https://www.choudalao.com/tag/2',
            'https://www.choudalao.com/tag/3',
            'https://www.choudalao.com/tag/4',
            'https://www.choudalao.com/tag/5',
            'https://www.choudalao.com/tag/6',
            'https://www.choudalao.com/tag/7',
            'https://www.choudalao.com/tag/8',
        );
        $api     = 'http://data.zz.baidu.com/urls?site=https://www.choudalao.com&token=nsdmyfRcySMSxYl1';
        $ch      = curl_init();
        $options = array(
            CURLOPT_URL            => $api,
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => implode("\n", $urls),
            CURLOPT_HTTPHEADER     => array('Content-Type: text/plain'),
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        $res    = json_decode($result, true);
        Cache::put('link_remain', $res['remain']);
        $remainArr = [
            'link_remain' => Cache::get('link_remain')
        ];
        $res       = array_merge($res, $remainArr);
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
        $res = Tuling::handle()->param('德玛西亚')->answer();
        dd($res);
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
        $info  = User::where('email', $email)->first();
        if ($info) {
            $data = [
                'code'        => 1,
                'msg'         => '该邮箱已被注册',
                'data'        => [],
                'create_time' => date('Y-m-d H:i:s', time())
            ];
            return response()->json($data);
        }
        //发送验证码
        $code = mt_rand(1000, 9999);
        Cache::put($email, $code, 60);
        $subject = env('MAIL_FROM_NAME');
        $msg     = '【臭大佬】邮箱验证码：' . $code . '，如非本人操作，请忽略本消息。';
        try {
            Mail::raw($msg, function ($message) use ($email, $subject) {
                $message->to($email)->subject($subject);
            });
            //错误处理
            if (count(Mail::failures()) < 1) {
                $data = [
                    'code'        => 0,
                    'msg'         => '发送邮件成功，请查收！',
                    'data'        => [],
                    'create_time' => date('Y-m-d H:i:s', time())
                ];
                return response()->json($data);
            } else {
                $data = [
                    'code'        => 1,
                    'msg'         => '邮箱验证码发送失败,请联系臭大佬(微信:wjf1937832819)',
                    'data'        => [],
                    'create_time' => date('Y-m-d H:i:s', time())
                ];
                return response()->json($data);
            }
        } catch (\Exception $e) {
            $data = [
                'code'        => 1,
                'msg'         => '邮箱配置错误，请联系管理员',
                'data'        => [],
                'create_time' => date('Y-m-d H:i:s', time())
            ];
            return response()->json($data);
        }
    }
}
