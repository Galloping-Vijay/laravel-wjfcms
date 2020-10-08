<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class RequestPost extends FormRequest
{
    /**
     * @var
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2019/10/21
     * Time: 11:18
     */
    protected $msg;

    /**
     * Determine if the user is authorized to make this request.
     * 判断用户是否有权限做出此请求。
     *
     * @return bool
     */
    public function authorize()
    {
//        $user = Auth::user();
//        if ($user->is_admin) {
//            return true;
//        } else {
//            $this->getFailedResponse('该操作未经授权');
//        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Description:设置错误信息
     * User: Vijay <1937832819@qq.com>
     * Date: 2019/10/21
     * Time: 11:17
     * @param $msg
     * @return $this
     */
    protected function setMsg($msg)
    {
        $this->msg = $msg;
        return $this;
    }

    /**
     * Description:返回错误相应
     * User: Vijay <1937832819@qq.com>
     * Date: 2019/10/21
     * Time: 11:21
     * @param string $msg
     * @param int $code 1为错误,0为正常
     * @param null $data
     * @param int $status
     */
    protected function getFailedResponse($msg = '', $code = 1, $data = null, $status = 200)
    {
        throw (new HttpResponseException(response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ], $status)));
    }

    /**
     * Description:返回错误信息
     * User: Vijay <1937832819@qq.com>
     * Date: 2019/10/16
     * Time: 16:06
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
       $this->getFailedResponse($validator->errors()->first());
    }
}
