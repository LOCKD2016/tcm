<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SocialiteProviders\Manager\OAuth2\User as OauthUser;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
/**
 * App\Models\BaseModel
 *
 * @mixin \Eloquent
 */
class Platform extends Model
{
    public function user(){
        return $this->belongsTo('App\Models\AppUser');
    }

    public function login(OauthUser $user){
        $pf = $this->where("openid",$user->getId())->first();
        if($pf){//不是第一次登录
            $user = AppUser::find($pf->user_id);
            if($user){
                return $pf->user;
            }else{
                return false;
            }
        }else{
            DB::beginTransaction();
            //创建用户账号 , 可能有异常
            $appuser = AppUser::create([
                "mobile" => Str::random(11),
                "nickname" =>  $user->getNickname(),
                "headimgurl" => $user->getAvatar(),
            ]);
            if(!$appuser){
                return false;
            }
            //创建第三方关联
            $this->user_id = $appuser->id;
            $this->openid = $user->getId();
            $this->nickname = $user->getNickname();
            $this->avatar = $user->getAvatar();
            $this->country = $user->country;
            $this->province = $user->province;
            $this->city = $user->city;
            $this->sex = $user->sex;
            $this->access_token = $user->token;
            $this->first_login = 0;
            $pf = $this->save();
            if(!$pf){
                DB:rollback();
                return false;
            }
            DB::commit();//提交事务
            return $appuser;
        }
    }
    protected function getExtraParams($model)
    {
        $data = [
            "did" => $model->id,
            "mobile" => $model->mobile,
            "nickname" => $model->nickname,
            "headimgurl" => $model->headimgurl,
            "email" => $model->email,
            "birthday" => $model->birthday,
            "height" => $model->height,
            "stage" => is_null($model->stage)?0:$model->stage,
            "marital" => is_null($model->marital)?0:$model->marital,
            "province" => $model->province,
            "city" => $model->city,
            "area" => $model->area,
            "notice_status" => $model->notice_status,
            "collection_num" => $model->collection_num,
            "first_login" => $this->first_login?0:1
        ];
        // 转换 null 字段为空字符串
        foreach (array_keys($data) as $key) {
            if (! isset($data[$key])) {
                $data[$key] = '';
                continue;
            }
            if (is_null($data[$key])) {
                $data[$key] = '';
            }
        }
        return $data;
    }
}