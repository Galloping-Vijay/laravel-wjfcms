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
        'category_id', 'title', 'author', 'markdown', 'html', 'description', 'keywords', 'cover', 'is_top', 'click','created_at'
    ];
}
