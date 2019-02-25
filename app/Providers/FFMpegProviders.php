<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/15 0015
 * Time: 10:06
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FFMpegProviders extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

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
     *
     * @return void
     */
    public function register()
    {
        //注册视频转码服务
        $this->app->singleton('FFMpeg', function ($app) {
            return \FFMpeg\FFMpeg::create(array(
                'ffmpeg.binaries' => '/usr/local/bin/ffmpeg',
                'ffprobe.binaries' => '/usr/local/bin/ffprobe',
                'timeout' => 3600, // The timeout for the underlying process
                'ffmpeg.threads' => 12,   // The number of threads that FFMpeg should use//
            ));
        });

        //注册转码转成格式的驱动
        $this->app->singleton('Wav', function ($app) {
            return new \FFMpeg\Format\Audio\Wav();
        });
    }
}
