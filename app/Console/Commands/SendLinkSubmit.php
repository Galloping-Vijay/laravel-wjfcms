<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
//use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\Types\Self_;

class SendLinkSubmit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:linkSubmit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '向百度主动提交链接';

    /**
     * @var string
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2019/11/25
     * Time: 17:33
     */
    private $api = 'http://data.zz.baidu.com/urls?site=https://www.choudalao.com&token=nsdmyfRcySMSxYl1';

    /**
     * @var string
     */
    private $baseUrl = 'https://www.choudalao.com/';

    /**
     * @var string
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2019/11/25
     * Time: 17:35
     */
    private $toUser = '184521508@qq.com';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/11/16
     * Time: 13:00
     */
    public function handle()
    {
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $this->api,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => implode("\n", self::getUrl()),
            CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        $res = json_decode($result, true);
        $this->info($result);
        $toUser = $this->toUser;
        if (isset($res['success'])) {
            Cache::put('link_remain', $res['remain']);
            $resStr = json_encode([
                'type' => 'linkSubmit',
                'status' => '成功',
                'remain' => $res['remain'],
                'link_remain' => Cache::get('link_remain')
            ]);
            $this->info($resStr);
            //Log::info($resStr);
            //成功也发送
            // Mail::to($toUser)->send(new Alarm($msg));
        } else {
            $msg = $res['message'] ?? '请求过于频繁';
            if ($msg == 'over quota') {
                return false;
            }
            $resStr = json_encode([
                'type' => 'linkSubmit',
                'status' => '失败',
                'msg' => $res['message'],
            ]);
            $this->info($resStr);
            //Log::info($resStr);
            $subject = env('MAIL_FROM_NAME');
//            //方式一
//            Mail::to($toUser)->send(new Alarm($msg));
//            //方式二
//            Mail::send('emails.alarm.text', [
//                'name' => env('MAIL_FROM_NAME'),
//                'title' => env('MAIL_FROM_NAME'),
//                'content' => $msg,
//            ], function ($message) use ($toUser, $subject) {
//                $message->to($toUser)->subject($subject);
//                //发送附件
//                //$attachment = storage_path('xls/files/test.xls');
//                //$message->attach($attachment, ['as' => '中文文档.xls']);
//            });
            //方式三 发送纯文本
            Mail::raw($msg, function ($message) use ($toUser, $subject) {
                $message->to($toUser)->subject($subject);
            });
            //错误处理
            if (count(Mail::failures()) < 1) {
                $this->info('发送邮件成功，请查收！');
            } else {
                $this->info('发送邮件失败，请重试！');
            }
        }
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2020/1/10
     * Time: 21:47
     * @return array
     */
    public function getUrl()
    {
        $urls = [
            $this->baseUrl,
            $this->baseUrl . 'register',
            $this->baseUrl . 'login',
            $this->baseUrl . 'chat',
            $this->baseUrl . 'admin/login',
        ];
        $artIds = cache::remember('artIds', 86400, function () {
            return Article::query()->pluck('id');
        });
        if ($artIds) {
            foreach ($artIds as $ak => $av) {
                $urls[] = $this->baseUrl . 'article/' . $av;
            }
        }
        $categoryIds = Cache::remember('categoryIds', 86400, function () {
            return Category::query()->pluck('id');
        });
        if ($categoryIds) {
            foreach ($categoryIds as $ck => $cv) {
                $urls[] = $this->baseUrl . 'category/' . $cv;
            }
        }
        $tagIds = Cache::remember('tagIds', 86400, function () {
            return Tag::query()->pluck('id');
        });
        if ($tagIds) {
            foreach ($tagIds as $tk => $tv) {
                $urls[] = $this->baseUrl . 'tag/' . $tv;
            }
        }
        return $urls;
    }
}
