<?php

namespace App\Providers;

use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Http\Receivers\AftershipProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // override the some voyager libraries
        $this->app->singleton('TCG\Voyager\Http\Middleware\VoyagerAdminMiddleware','App\Http\Middleware\CustomVoyagerAdminMiddleware');
        $this->app->singleton('TCG\Voyager\Http\Controllers\VoyagerAuthController','App\Http\Controllers\Admin\CustomVoyagerAuthController');
        $this->app->singleton('TCG\Voyager\Http\Controllers\Controller','App\Http\Controllers\CustomVoyagerController');
        $this->app->singleton('TCG\Voyager\Http\Controllers\VoyagerBaseController','App\Http\Controllers\Admin\CustomVoyagerBaseController');
        $this->app->singleton('TCG\Voyager\Http\Controllers\VoyagerBreadController','App\Http\Controllers\Admin\CustomVoyagerBreadController');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        Voyager::replaceAction('TCG\Voyager\Actions\ViewAction', '\App\Http\Controllers\Admin\Actions\ViewAction');
        Voyager::replaceAction('TCG\Voyager\Actions\RestoreAction', '\App\Http\Controllers\Admin\Actions\RestoreAction');
        Voyager::replaceAction('TCG\Voyager\Actions\EditAction', '\App\Http\Controllers\Admin\Actions\EditAction');
        Voyager::replaceAction('TCG\Voyager\Actions\DeleteAction', '\App\Http\Controllers\Admin\Actions\DeleteAction');

        
        $receiver = app('receiver');
        $receiver->extend('aftership' , function($app) {
           return new AftershipProvider(config('services.aftership.webhook_secret'));
        });

    }
}
