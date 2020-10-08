<?php

namespace App\Observers;

use App\Models\Article;

class ArticleObserver extends BaseObserver
{

    /**
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2019/11/08
     * Time: 11:46
     * @param Article $article
     */
    public function creating(Article $article)
    {
        //Log::info('即将插入用户到数据库');
    }

    /**
     * Handle the article "created" event.
     *
     * @param \App\Models\Article $article
     * @return void
     */
    public function created(Article $article)
    {
        if ($article->is_top == 1 && $article->status == 1) {
            Article::topArticle(true);
        }
    }

    /**
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2019/11/08
     * Time: 11:46
     * @param Article $article
     */
    public function updating(Article $article)
    {
        //Log::info('即将更新用户到数据库');
    }

    /**
     * Handle the article "updated" event.
     *
     * @param \App\Models\Article $article
     * @return void
     */
    public function updated(Article $article)
    {
        // 缓存置顶文章
        if ($article->isDirty('is_top') || $article->isDirty('status')) {
            Article::topArticle(true);
        }
    }

    /**
     * Description:即将保存用户到数据库
     * User: Vijay <1937832819@qq.com>
     * Date: 2019/11/08
     * Time: 11:46
     * @param Article $article
     */
    public function saving(Article $article)
    {
        //Log::info('即将保存用户到数据库');
    }

    /**
     * Description:保存后
     * User: Vijay <1937832819@qq.com>
     * Date: 2019/11/08
     * Time: 12:46
     * @param Article $article
     */
    public function saved(Article $article)
    {
//        if (isset($article->cover) && !empty($article->cover)) {
//            waterMarkImage($article->cover, true);
//        }
//        if (isset($article->markdown) && !empty($article->markdown)) {
//            // 获取文章中的全部图片
//            preg_match_all('/!\[.*?\]\((\S*(?<=png|gif|jpg|jpeg)).*?\)/i', $article->markdown, $images);
//            if (!empty($images[1])) {
//                // 循环给图片添加水印
//                foreach ($images[1] as $k => $v) {
//                    //添加水印
//                   waterMarkImage($v);
//                }
//            }
//        }
    }


    /**
     * Handle the article "deleted" event.
     *
     * @param \App\Models\Article $article
     * @return void
     */
    public function deleted(Article $article)
    {
        //
    }

    /**
     * Handle the article "restored" event.
     *
     * @param \App\Models\Article $article
     * @return void
     */
    public function restored(Article $article)
    {
        //
    }

    /**
     * Handle the article "force deleted" event.
     *
     * @param \App\Models\Article $article
     * @return void
     */
    public function forceDeleted(Article $article)
    {
        //
    }
}
