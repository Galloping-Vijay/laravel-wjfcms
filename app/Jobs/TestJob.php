<?php

namespace App\Jobs;

use App\Models\FriendLink;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $friendLink = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(FriendLink $friendLink)
    {
        //
        $this->friendLink = $friendLink;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->friendLink->id = 4) {
            $this->friendLink->name = '悦';
            $this->friendLink->save();
            Log::info('执行队列成功');
        }else{
            Log::info('执行队列失败');
        }
    }
}
