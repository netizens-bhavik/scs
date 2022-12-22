<?php

namespace App\Observers;

use App\Models\SystemLog;

class SystemObserver
{
    /**
     * Handle the SystemLog "created" event.
     *
     * @param  \App\Models\SystemLog  $systemLog
     * @return void
     */
    public function created(SystemLog $systemLog)
    {
        //
    }

    /**
     * Handle the SystemLog "updated" event.
     *
     * @param  \App\Models\SystemLog  $systemLog
     * @return void
     */
    public function updated(SystemLog $systemLog)
    {
        //
    }

    /**
     * Handle the SystemLog "deleted" event.
     *
     * @param  \App\Models\SystemLog  $systemLog
     * @return void
     */
    public function deleted(SystemLog $systemLog)
    {
        //
    }

    /**
     * Handle the SystemLog "restored" event.
     *
     * @param  \App\Models\SystemLog  $systemLog
     * @return void
     */
    public function restored(SystemLog $systemLog)
    {
        //
    }

    /**
     * Handle the SystemLog "force deleted" event.
     *
     * @param  \App\Models\SystemLog  $systemLog
     * @return void
     */
    public function forceDeleted(SystemLog $systemLog)
    {
        //
    }
}
