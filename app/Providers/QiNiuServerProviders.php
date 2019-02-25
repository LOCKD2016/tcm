<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/15 0015
 * Time: 10:06
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Qiniu\Auth;
use Qiniu\Processing\PersistentFop;
class QiNiuServerProviders extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    //protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *七牛音频转码服务注册
     * @return void
     */
    public function register()
    {
        $config = config('filesystems.disks.qiniu');

        $this->app->singleton('PersistentFop', function ($app) use($config) {
            //return new PersistentFop(new Auth($config['access_key'],$config['secret_key']), $config['bucket'], $config['pipeline']);
            return new PersistentFop(new Auth($config['access_key'],$config['secret_key']));
        });
    }
}
