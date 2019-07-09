<?php

namespace App\Models;

use App\Http\Traits\TraitsModel;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use TraitsModel;

    /**
     * Author: Vijay  <1937832819@qq.com>
     * @var array
     */
    protected $fillable = [
        'user_id', 'type', 'pid', 'article_id', 'content', 'status', 'created_at'
    ];

    /**
     * @var array
     */
    public static $status = [
        0 => '待审核',
        1 => '已发布'
    ];

    /**
     * @var array
     */
    public static $type = [
        1 => '文章评论'
    ];

    /**
     * Description:关联分类
     * User: Vijay
     * Date: 2019/6/30
     * Time: 17:41
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
