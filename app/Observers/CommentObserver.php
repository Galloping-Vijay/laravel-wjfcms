<?php

namespace App\Observers;

use App\Models\Comment;

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
        if ($comment->origin_id == 0) {
            $comment->origin_id = $comment->id;
            $comment->save();
        }
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
        if ($comment->origin_id == 0) {
            $comment->origin_id = $comment->id;
            $comment->save();
        }
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
