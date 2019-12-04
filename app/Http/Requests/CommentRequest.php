<?php

namespace App\Http\Requests;


use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class CommentRequest extends RequestPost
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => ['required', function ($attribute, $value, $fail) {
                $info = Auth::user();
                if ($info->id != $value) {
                    $fail('用户ID错误');
                }
            }],
            'content' => ['required',],
            'article_id' => ['required', function ($attribute, $value, $fail) {
                $info = Article::find($value);
                if (!$info) {
                    $fail('不存在id为:' . $value . '的文章');
                }
            }]
        ];
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/12/4
     * Time: 22:10
     * @return array
     */
    public function attributes()
    {
        return [
            'user_id' => '评论用户',
            'article_id' => '文章',
            'content' => '评论内容',
        ];
    }
}
