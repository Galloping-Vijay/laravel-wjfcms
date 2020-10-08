<?php

namespace App\Models;

use App\Http\Traits\TraitsModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Article extends Model
{
    use TraitsModel;

    /**
     * Author: Vijay  <1937832819@qq.com>
     * @var array
     */
    protected $fillable = [
        'category_id', 'title', 'slug', 'author', 'status', 'content', 'markdown', 'description', 'keywords', 'cover', 'is_top', 'click', 'created_at'
    ];

    /**
     * @var array
     */
    public static $status = [
        0 => '待审核',
        1 => '已发布'
    ];

    /**
     * Description:关联分类
     * User: Vijay
     * Date: 2019/6/30
     * Time: 17:41
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Description:获取markdown字段
     * User: Vijay <1937832819@qq.com>
     * Date: 2019/11/08
     * Time: 13:07
     * @param $value
     * @return mixed
     */
    public function getMarkdownAttribute($value)
    {
        return $value ? $value : $this->content;
    }

    /**
     * Description:缓存置顶文章
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/07/31
     * Time: 11:12
     * @param bool $isCache
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|mixed
     */
    public static function topArticle($isCache = false)
    {
        if ($isCache == true) {
            $topArticle = Article::query()
                ->where([
                    ['is_top', '=', '1'],
                    ['status', '=', '1']
                ])->select('id', 'title', 'cover')
                ->orderBy('updated_at', 'desc')
                ->limit(3)
                ->get();

            Cache::forever('top_article', $topArticle);
            $topArticle = Cache::get('top_article');
        } else {
            $topArticle = Cache::rememberForever('top_article', function () {
                $topArticle = Article::query()
                    ->where([
                        ['is_top', '=', '1'],
                        ['status', '=', '1']
                    ])->select('id', 'title', 'cover')
                    ->orderBy('updated_at', 'desc')
                    ->limit(3)
                    ->get();
                return $topArticle;
            });
        }
        return $topArticle;
    }
}
