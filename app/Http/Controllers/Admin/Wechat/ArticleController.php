<?php

namespace App\Http\Controllers\Admin\Wechat;

use App\Models\Article;
use Illuminate\Http\Request;
use EasyWeChat\Kernel\Messages\Article as WxArticle;

class ArticleController extends WechatBase
{
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $app = app('wechat.official_account');
        //上传封面
        $art_id = $request->input('id');
        $art = Article::find($art_id);
        if(!$art){

        }
        $imgRes = $app->material->uploadImage($art->cover);
        // 上传单篇图文
        $article = new WxArticle([
            'title' => $art->title,
            'thumb_media_id' => $imgRes->mediaId,
            'author' => $art->author,
            'digest' => $art->description,
            'show_cover_pic' => 1,
            'content' => $art->markdown,
            //...
        ]);
        $app->material->uploadArticle($article);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
