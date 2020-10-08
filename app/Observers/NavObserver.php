<?php

namespace App\Observers;

use App\Models\Nav;

class NavObserver
{
    /**
     * Handle the nav "created" event.
     *
     * @param \App\Models\Nav $nav
     * @return void
     */
    public function created(Nav $nav)
    {
        //
    }

    /**
     * Handle the nav "updated" event.
     *
     * @param \App\Models\Nav $nav
     * @return void
     */
    public function updated(Nav $nav)
    {
        //
    }

    /**
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/07/31
     * Time: 12:52
     * @param Nav $nav
     */
    public function saved(Nav $nav)
    {
        Nav::cacheNav(true);
    }

    /**
     * Handle the nav "deleted" event.
     *
     * @param \App\Models\Nav $nav
     * @return void
     */
    public function deleted(Nav $nav)
    {
        //
    }

    /**
     * Handle the nav "restored" event.
     *
     * @param \App\Models\Nav $nav
     * @return void
     */
    public function restored(Nav $nav)
    {
        //
    }

    /**
     * Handle the nav "force deleted" event.
     *
     * @param \App\Models\Nav $nav
     * @return void
     */
    public function forceDeleted(Nav $nav)
    {
        //
    }
}
