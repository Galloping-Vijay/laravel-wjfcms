<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Alarm extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private $content = '';

    /**
     * Alarm constructor.
     * @param $text
     */
    public function __construct($text)
    {
        //设置主题
        $this->subject(env('MAIL_FROM_NAME'));
        //设置发件人
        $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        //设置内容
        $this->setContent($text);
    }

    /**
     * Description:发送
     * User: Vijay
     * Date: 2019/11/16
     * Time: 12:54
     * @return Alarm
     */
    public function build()
    {
        return $this->view('emails.alarm.text')->with([
            'title' => env('MAIL_FROM_NAME'),
            'content' => $this->content,
        ]);
    }

    /**
     * Description:设置发送内容
     * User: Vijay
     * Date: 2019/11/16
     * Time: 13:10
     * @param string $text
     * @return $this
     */
    public function setContent($text = '')
    {
        $this->content .= '时间:' . date('Y-m-d H:i:s') . '，';
        $this->content .= '内容:' . $text;
        return $this;
    }
}
