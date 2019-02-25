<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';
    protected $api_namespace = 'App\Http\Controllers\Api';
    protected $app_namespace = 'App\Http\ApiControllers';
    protected $wx_namespace = 'App\Http\WxControllers';

    /**
     * 支付平台的控制器 路径
     * @Auth: kingofzihua
     * @var string
     */
    protected $platform_namespace = 'App\Http\PlatformControllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        $api_router = app('Dingo\Api\Routing\Router');
        $api_router->group([
            'version' => config('api.version'),
            'namespace' => $this->api_namespace,
            'middleware' => ['web', 'auth']
        ], function ($router) {
            require base_path('routes/api.php');
        });

        $api_router->group([
            'version' => config('api.wx_version'),
            'namespace' => $this->wx_namespace,
            'middleware' => ['web']
        ], function ($router) {
            //require base_path('routes/app.php');
            require base_path('routes/weixin.php');
        });

        /**
         * app端的路由
         * @auther kingofzihua
         * @date 2017/06/06
         */
        $api_router->group([
            'version' => config('api.app_version'),
            'namespace' => $this->app_namespace,
//            'middleware'=>['api']
        ], function ($router) {
            require base_path('routes/app.php');
        });

        /**
         * 给泰和国医提供接口
         * @auther kingofzihua
         * @date 2017/06/06
         */
        $api_router->group([
            'version' => config('api.platform_version'),
            'namespace' => $this->platform_namespace,
        ], function ($router) {
            require base_path('routes/platform.php');
        });

    }
}
