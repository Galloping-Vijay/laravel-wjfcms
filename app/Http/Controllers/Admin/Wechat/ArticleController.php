<?php

namespace App\Http\Controllers\Admin\Wechat;

use App\Http\Traits\TraitResource;
use App\Models\Article;
use App\Models\WxArticle;
use Illuminate\Http\Request;
use EasyWeChat\Kernel\Messages\Article as WxApiArticle;

class ArticleController extends WechatBase
{
    use TraitResource;

    /**
     * KeywordController constructor.
     */
    public function __construct()
    {
        self::$model = WxArticle::class;
        self::$controlName = 'weChat/article';
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Description:
     * User: Vijay
     * Date: 2020/3/5
     * Time: 22:33
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //上传封面
        $art_id = $request->input('id');
        $art_id = 113;
        try {
            $art = Article::find($art_id);
            if (!$art) {
                return $this->resJson(1, '不存在文章');
            }
            $imgRes = $this->app->material->uploadImage($art->cover);
            $imgArr = json_decode($imgRes, true);
            // 上传单篇图文
            $article = new WxArticle([
                'title' => $art->title,
                'thumb_media_id' => $imgArr['mediaId'],
                'author' => $art->author,
                'digest' => $art->description,
                'show_cover_pic' => 1,
                'content' => $art->markdown,
                //...
            ]);
            $artRes = $this->app->material->uploadArticle($article);
            $artRes = json_decode($artRes, true);
            return $this->resJson(0, '上传成功', $artRes);
        } catch (\Exception $e) {
            return $this->resJson(1, $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
