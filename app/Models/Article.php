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
}
