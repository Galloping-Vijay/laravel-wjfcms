<?php

namespace App\Models;

use App\Http\Traits\TraitsModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    use TraitsModel;

    /**
     * Author: Vijay  <1937832819@qq.com>
     * @var array
     */
    protected $fillable = [
        'user_id', 'type', 'pid', 'origin_id', 'article_id', 'content', 'status', 'created_at'
    ];

    /**
     * @var array
     */
    public static $status = [
        0 => '待审核',
        1 => '已发布'
    ];

    /**
     * @var array
     */
    public static $type = [
        1 => '文章评论'
    ];

    /**
     * Description:关联分类
     * User: Vijay
     * Date: 2019/6/30
     * Time: 17:41
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/12/4
     * Time: 22:42
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Description:获取菜单树结构
     * User: Vijay
     * Date: 2019/7/31
     * Time: 11:43
     * @param array $data
     * @param int $pid
     * @return array
     */
    public static function getMenuTree(array $data = [], int $pid = 0): array
    {
        $resArr = [];
        foreach ($data as $key => &$val) {
            if ($val['pid'] == $pid) {
                $val['child'] = self::getMenuTree($data, $val['id']);
                $resArr[] = $val;
            }
        }
        return $resArr;
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/12/4
     * Time: 23:30
     * @param $art_id
     * @param int $comment_limit
     * @param int $comment_page
     * @return mixed
     */
    public static function getComment($art_id, $comment_page = 1, $comment_limit = 5)
    {
        //第一级
        $comments = Comment::leftJoin('users', 'users.id', '=', 'comments.user_id')
            ->where([
                ['comments.article_id', '=', $art_id],
                ['comments.status', '=', 1],
                ['comments.type', '=', 1],
                ['comments.pid', '=', 0],
            ])->select('comments.id', 'comments.pid', 'comments.origin_id', 'comments.content', 'comments.created_at', 'comments.user_id', 'comments.zan', 'comments.cai', 'users.name as user_name', 'users.avatar')
            ->orderBy('comments.created_at', 'desc')
            ->paginate($comment_limit, ['*'], 'comment_page', $comment_page);
        //第二级
        if (!$comments->isEmpty()) {
            foreach ($comments as $key => $val) {
                $comments[$key]['content'] = htmlspecialchars_decode($val['content']);
                $comments[$key]['child'] = self::getTwoLevels($val['id']);
            }
        }
        return $comments;
    }

    /**
     * Description:两级评论
     * User: Vijay
     * Date: 2019/12/5
     * Time: 21:29
     * @param $origin_id
     * @return mixed
     */
    public static function getTwoLevels($origin_id)
    {
        $commentArr = Comment::leftJoin('users', 'users.id', '=', 'comments.user_id')
            ->where([
                ['comments.status', '=', 1],
                ['comments.type', '=', 1],
                ['comments.origin_id', '=', $origin_id],
            ])->select('comments.id', 'comments.pid', 'comments.origin_id', 'comments.content', 'comments.zan', 'comments.cai', 'comments.created_at', 'comments.user_id', 'users.name as user_name', 'users.avatar')
            ->orderBy('comments.created_at', 'desc')
            ->get()
            ->toArray();

        if (count($commentArr) > 0) {
            foreach ($commentArr as $key => &$val) {
                $commentArr[$key]['content'] = htmlspecialchars_decode($val['content']);
                $commentArr[$key]['answered_user_name'] = Comment::leftJoin('users', 'users.id', '=', 'comments.user_id')->where('comments.id', $val['pid'])->select('users.name')->first()->name;
            }
        }
        return $commentArr;
    }

    /**
     * Description:树形评论
     * User: Vijay
     * Date: 2019/12/5
     * Time: 21:30
     * @param $pid
     * @return mixed
     */
    public static function getChild($pid)
    {
        $commentArr = Comment::leftJoin('users', 'users.id', '=', 'comments.user_id')
            ->where([
                ['comments.status', '=', 1],
                ['comments.type', '=', 1],
                ['comments.pid', '=', $pid],
            ])->select('comments.id', 'comments.pid', 'comments.content', 'comments.created_at', 'comments.user_id', 'users.name', 'users.avatar')
            ->orderBy('comments.created_at', 'desc')
            ->get()
            ->toArray();
        if (count($commentArr) > 0) {
            foreach ($commentArr as $key => &$val) {
                $commentArr[$key]['content'] = htmlspecialchars_decode($val['content']);
                $commentArr[$key]['child'] = self::getChild($val['id']);
            }
        }
        return $commentArr;
    }
}
