<?php

namespace App\Models;

use App\Http\Traits\TraitsModel;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use TraitsModel;

    /**
     * Author: Vijay  <1937832819@qq.com>
     * @var array
     */
    protected $fillable = [
        'content', 'created_at',
    ];
}
