<?php

namespace App\Models;

use App\Http\Traits\TraitsModel;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use TraitsModel;

    /**
     * Author: Vijay  <1937832819@qq.com>
     * @var array
     */
    protected $fillable = [
        'category_id', 'title', 'slug', 'author', 'status', 'markdown', 'html', 'description', 'keywords', 'cover', 'is_top', 'click', 'created_at'
    ];

    /**
     * @var array
     */
    public static $status = [
        0 => '待审核',
        1 => '已发布'
    ];

    /**
     * Instructions:获取此评论所属文章
     * Author: Vijay  <1937832819@qq.com>
     * Time: 2019/6/26 14:20
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo('App\Models\Comment');
    }

    /**
     * Instructions:关联分类
     * Author: Vijay  <1937832819@qq.com>
     * Time: 2019/6/26 14:22
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
}
