<?php
/**
 * Description:
 * User: Vijay <1937832819@qq.com>
 * Date: 2020/04/28
 * Time: 12:21
 */

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Libs\Encrypt\AesEncrypt;
use App\Models\Article;
use Illuminate\Http\Request;

class BaiduController extends Controller
{
    /**
     * @var array
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/28
     * Time: 12:41
     */
    private $config = [];

    /**
     * BaiduController constructor.
     */
    public function __construct()
    {
        $this->getConfig();
    }

    public function index()
    {
        //数据解密
        $appId   = 'your appId';
        $aes_key = 'your aesKey';

        $dataCoder = new AesEncrypt($appId, $aes_key);
        //百家号服务器发送的消息


        $data = [
            'signature' => '',
            'timestamp' => '',
            'nonce'     => '',
            'encrypt'   => '',
        ];
        print_r($dataCoder->decrypt($data['encrypt']));
    }

    /**
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/28
     * Time: 12:46
     */
    public function serve(Request $request)
    {
        $timestamp    = $request->input('timestamp');
        $nonce        = $request->input('nonce');
        $signature    = $request->input('signature');
        $encrypt      = $request->input('encrypt');
        $strSignature = $this->getSHA1($this->config['you_token'], $timestamp, $nonce);
        if ($strSignature !== $signature) {
            echo 'failed';
            return false;

        }
        $dataCoder = new AesEncrypt($this->config['app_id'], $this->config['encoding_AESKey']);
        print_r($dataCoder->decrypt($encrypt));
    }

    /**
     * 用sha1算法生成安全签名
     * @param string $strToken message_token
     * @param string $intTimeStamp 时间
     * @param string $strNonce 随机字符串
     * @param string $strEncryptMsg 密文消息
     * @return string
     */
    private function getSHA1($strToken, $intTimeStamp, $strNonce, $strEncryptMsg = '')
    {
        $arrParams = array(
            $strToken,
            $intTimeStamp,
            $strNonce,
        );
        if (!empty($strEncryptMsg)) {
            array_unshift($arrParams, $strEncryptMsg);
        }
        sort($arrParams, SORT_STRING);
        $strParam = implode($arrParams);
        return sha1($strParam);
    }

    /**
     * Description:设置参数
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/28
     * Time: 12:45
     * @return $this
     */
    private function getConfig()
    {
        $this->config = [
            'app_id'          => env('BAIJIAHAO_APP_ID'),
            'app_token'       => env('BAIJIAHAO_APP_TOKEN'),
            'you_token'       => env('BAIJIAHAO_APP_YOU_TOKEN'),
            'encoding_AESKey' => env('BAIJIAHAO_APP_Encoding_AESKe'),
        ];

        return $this;
    }
}
