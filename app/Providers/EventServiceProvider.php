<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Nav;
use App\Models\SystemConfig;
use App\Observers\ArticleObserver;
use App\Observers\CategoryObserver;
use App\Observers\CommentObserver;
use App\Observers\NavObserver;
use App\Observers\SystemConfigObserver;
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
        Registered::class                                     => [
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
        Article::observe(ArticleObserver::class);
        Comment::observe(CommentObserver::class);
        Nav::observe(NavObserver::class);
        Category::observe(CategoryObserver::class);
        SystemConfig::observe(SystemConfigObserver::class);
    }
}
