<?php

namespace App\Providers;

use App\Models\Comment;
use App\Observers\CommentObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            'SocialiteProviders\QQ\QqExtendSocialite@handle',
            'SocialiteProviders\\Weibo\\WeiboExtendSocialite@handle',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        //观察者监听模型事件
        //Article::observe(ArticleObserver::class);
        Comment::observe(CommentObserver::class);
    }
}
