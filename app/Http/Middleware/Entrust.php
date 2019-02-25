<?php

namespace App\Http\Middleware;

use Closure;
class Entrust
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(app('Illuminate\Contracts\Auth\Guard')->guest()){
            abort(401,'登录过期');
        }
        $api_router = app('Dingo\Api\Routing\Router');
        $name = $api_router->currentRouteName();
        //dd($api_router->currentRouteAction());
        //if($api_router->currentRouteAction() == NULL) return $next($request);
        list($controller, $method) = explode("@", $api_router->currentRouteAction());
        $controller = str_replace('\\', '.', substr($controller, 21, -10));
        //dd($controller);
        $permission = strtolower("{$controller}.{$method}");
        //if($name){
            $permissionObj = app('App\Models\Permission');
            if(! $permissionObj->where(['name'=>$permission])->first()){
                $permissionObj->firstOrCreate(['name'=>$permission,'display_name'=>$name]);
            }
        //}
        if($request->user()->user_id !=1 && !$request->user()->hasRole("admin")) {
            if (!$request->user()->can($permission)) {
                abort(403,'没有权限');
            }
        }
        return $next($request);
    }
}
