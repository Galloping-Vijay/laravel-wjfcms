<?php

namespace App\Observers;

use App\Models\PointLog;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    public function saving(User $user)
    {
        Log::info('即将保存用户到数据库[' . $user->id . ']' . $user->name);
    }

    public function creating(User $user)
    {
        Log::info('即将插入用户到数据库[' . $user->id . ']' . $user->name);
    }

    public function created(User $user)
    {
        Log::info('已经插入用户到数据库[' . $user->id . ']' . $user->name);
    }

    public function saved(User $user)
    {
        Log::info('已经保存用户到数据库[' . $user->id . ']' . $user->name);
    }

    /**
     * Instructions:用户注册
     * Author: vijay <1937832819@qq.com>
     * @param User $user
     */
    public function registered(User $user)
    {
        // 用户注册成功后初始积分为30
        $user->point += PointLog::$OPT_POINT[PointLog::OPT_USER_REGISTER];
        $user->save();
        // 保存积分变更日志
        $pointLog = new PointLog();
        $pointLog->type = PointLog::OPT_USER_REGISTER;
        $pointLog->value = PointLog::$OPT_POINT[PointLog::OPT_USER_REGISTER];
        $user->pointLogs()->save($pointLog);
    }

    /**
     * Instructions:用户邮箱验证
     * Author: vijay <1937832819@qq.com>
     * @param User $user
     */
    public function verified(User $user)
    {
        // 用户验证邮箱后增加20积分
        $user->point += PointLog::$OPT_POINT[PointLog::OPT_EMAIL_VERIFY];
        $user->save();
        // 保存积分变更日志
        $pointLog = new PointLog();
        $pointLog->type = PointLog::OPT_EMAIL_VERIFY;
        $pointLog->value = PointLog::$OPT_POINT[PointLog::OPT_EMAIL_VERIFY];
        $user->pointLogs()->save($pointLog);
    }


    public function updating(User $user)
    {
        Log::info('即将更新用户到数据库[' . $user->id . ']' . $user->name);
    }

    public function updated(User $user)
    {
        Log::info('已经更新用户到数据库[' . $user->id . ']' . $user->name);
    }


    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Models\User $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\Models\User $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\Models\User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
