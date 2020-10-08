<?php

namespace App\Observers;

use App\Models\SystemConfig;

class SystemConfigObserver
{
    /**
     * Handle the system config "created" event.
     *
     * @param  \App\Models\SystemConfig;  $systemConfig
     * @return void
     */
    public function created(SystemConfig $systemConfig)
    {
        //
    }

    /**
     * Handle the system config "updated" event.
     *
     * @param  \App\Models\SystemConfig;  $systemConfig
     * @return void
     */
    public function updated(SystemConfig $systemConfig)
    {
        //
    }


    /**
     * Handle the system config "deleted" event.
     *
     * @param  \App\Models\SystemConfig;  $systemConfig
     * @return void
     */
    public function deleted(SystemConfig $systemConfig)
    {
        //
    }

    /**
     * Handle the system config "restored" event.
     *
     * @param  \App\Models\SystemConfig; $systemConfig
     * @return void
     */
    public function restored(SystemConfig $systemConfig)
    {
        //
    }

    /**
     * Handle the system config "force deleted" event.
     *
     * @param  \App\Models\SystemConfig;  $systemConfig
     * @return void
     */
    public function forceDeleted(SystemConfig $systemConfig)
    {
        //
    }

    /**
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/09/14
     * Time: 10:56
     * @param SystemConfig $systemConfig
     */
    public function saved(SystemConfig $systemConfig)
    {
        $systemConfig::getConfigList(false);
    }
}
