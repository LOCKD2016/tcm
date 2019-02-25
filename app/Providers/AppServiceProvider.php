<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use App\Models\Smscode;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //短信验证
        Validator::extend('smscode', function($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            $mobile = $data['mobile'];
            $smscode = Smscode::query()->where([
                'mobile'=>$mobile,
                'code'=>$value,
                'status'=>0,
                'type' => $parameters[0]
            ])->orderBy("created_at","desc")->first();
            if($smscode){
                if(count($parameters)<2){
                    $smscode->where(['mobile'=>$mobile,'status'=>0])->update(['status'=>1]);
                }
                return true;
            }else{
                $validator->errors()->add($attribute,"验证码错误");
                return false;
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
