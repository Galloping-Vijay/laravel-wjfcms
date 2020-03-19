<?php

namespace App\Http\Requests;

use App\Models\FriendLink;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;

class LinkRequest extends RequestPost
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->merge(['client_ip' => getIP()]);
        return [
            'email' => ['required', 'email', 'unique:friend_links'],
            'url' => ['required', 'url', 'unique:friend_links'],
        ];
    }

    /**
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2019/12/18
     * Time: 15:31
     * @param Validator $validator
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator){
            //查询这个ip在5分钟内是否有重复提交
            $start_time = date('Y-m-d H:i:s', time() - 5 * 60);
            $info = FriendLink::query()
                ->where('client_ip', $this->client_ip)
                ->where('created_at', '>=', $start_time)
                ->first();
            if ($info) {
                $validator->errors()->add('client_ip', '您已提交过了,不要重复提交哦');
                return false;
            }
        });
    }
}
