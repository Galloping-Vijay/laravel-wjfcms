<?php
/**
 * Description:
 * Created by PhpStorm.
 * User: Vijay <1937832819@qq.com>
 * Date: 2020/4/28
 * Time: 21:05
 */

namespace App\Http\Controllers\Admin\Baijiahao;

use App\Http\Traits\TraitResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Vijay\Curl\Curl;

class ArticleController extends BaijiahaoBase
{
    use TraitResource;

    /**
     * Description:百家号推送文章
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/4/28
     * Time: 21:33
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function publish(Request $request)
    {
        $id       = $request->input('id');
        $original = $request->input('original', 1);
        if (!$id) {
            return $this->resJson(1, '文章id不能为空');
        }
        $article = Article::query()->find($id);
        if (!$article) {
            return $this->resJson(1, '没有该条记录');
        }
        if ($article->status == 0) {
            return $this->resJson(1, '文章未审核通过');
        }

        if ($article->is_baijiahao == 1) {
            return $this->resJson(1, '文章已推送过了');
        }

        $url      = 'http://baijiahao.baidu.com/builderinner/open/resource/article/publish';
        $pushData = [
            'app_id'       => $this->config['app_id'],
            'app_token'    => $this->config['app_token'],
            'title'        => $article->title,
            'content'      => htmlspecialchars_decode($article->content),
            'origin_url'   => self::BLOG_URL . '/article/' . $id,
            'cover_images' => json_encode([
                ['src' => $article->cover]
            ], JSON_UNESCAPED_UNICODE),
            'is_original'  => $original,
        ];
        $curl     = new Curl();
        $res      = $curl->post($url, $pushData);

        if (!isset($res) || empty($res)) {
            return $this->resJson(1, '获取失败');
        }
        $resArr = json_decode($res, true);
        if (!isset($resArr['errmsg'])) {
            return $this->resJson(1, '请求失败');
        }
        if ($resArr['errno'] == 0 && isset($resArr['data'])) {
            $article->is_baijiahao = 1;
            $article->save();
            return $this->resJson(0, $resArr['errmsg']);
        } else {
            return $this->resJson(1, $resArr['errmsg']);
        }
    }

}