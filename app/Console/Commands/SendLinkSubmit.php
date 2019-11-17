<?php

namespace App\Console\Commands;

use App\Mail\Alarm;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

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
        //
        $urls = array(
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
        $api = 'http://data.zz.baidu.com/urls?site=https://www.choudalao.com&token=nsdmyfRcySMSxYl1';
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
        $this->info($result);
        $toUser = '184521508@qq.com';
        if (isset($res['success'])) {
            $msg = '推送成功';
            $this->info($msg . $res['success'] . '条');
            //成功也发送
            Mail::to($toUser)->send(new Alarm($msg));
        } else {
            $msg = $res['message'] ?? '请求过于频繁';
            $this->info('推送失败');
            $this->info('原因：' . $msg);
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
}
