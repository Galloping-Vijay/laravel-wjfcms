<?php

namespace App\Observers;

use App\Models\Comment;
use Illuminate\Support\Facades\Mail;

class CommentObserver
{
    /**
     * Handle the comment "created" event.
     *
     * @param  \App\Models\Comment $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        // 新评论邮箱提醒
        $subject = env('MAIL_FROM_NAME');
        $email =  env('MAIL_TO_ADDRESS');
        $app_url = env('APP_URL');
        $msg     = "【{$subject}】有新评论，<a href='{$app_url}/article/{$comment->article_id}' title='{$subject}' target='_blank'>点击查看</a>";
        Mail::raw($msg, function ($message) use ($email, $subject) {
            $message->to($email)->subject($subject);
        });
    }

    /**
     * Handle the comment "updated" event.
     *
     * @param  \App\Models\Comment $comment
     * @return void
     */
    public function updated(Comment $comment)
    {
        //
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/12/5
     * Time: 21:57
     * @param Comment $comment
     */
    public function saved(Comment $comment)
    {

    }

    /**
     * Handle the comment "deleted" event.
     *
     * @param  \App\Models\Comment $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        //
    }

    /**
     * Handle the comment "restored" event.
     *
     * @param  \App\Models\Comment $comment
     * @return void
     */
    public function restored(Comment $comment)
    {
        //
    }

    /**
     * Handle the comment "force deleted" event.
     *
     * @param  \App\Models\Comment $comment
     * @return void
     */
    public function forceDeleted(Comment $comment)
    {
        //
    }
}
