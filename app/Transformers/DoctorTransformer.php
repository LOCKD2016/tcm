<?php

namespace App\Transformers;

use Carbon\Carbon;

/**
 * Class DoctorTransformer
 * @Auth: kingofzihua
 * @package App\Transformers
 */
class DoctorTransformer extends BaseTransformer
{
    /**
     * 可以
     * @Auth: kingofzihua
     * @var array
     */
    protected $availableIncludes = [
        'cliniques', 'sections', 'diseases', 'comments', 'schedules',
    ];

    /**
     * 默认
     * @Auth: kingofzihua
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * @Auth: kingofzihua
     * @param $model
     * @return array
     */
    public function transformData($model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name, //医生姓名
            'mobile' => $model->mobile, //手机号
            'birthday' => $model->birthday,
            'age' => $model->age,
            'sex' => $model->sex($model->sex),
            'photoLUrl' => false !==strpos($model->head_img,'http')?$model->head_img:config('app.url').$model->head_img,//photoSUrl 大头像地址
            'photoSUrl' => false !==strpos($model->head_img_L,'http')?$model->head_img_L:config('app.url').$model->head_img_L, //photoSUrl 小头像地址
            'address' => $model->address, //地址
            'intro' => $model->intro, //描述
            'desc' => $model->desc, //简介
            'title' => $model->title, //医生职编号
            'titleName' => $model->titleName, //医生职称
            'qrCode' => $model->qrCode,
            'level' => $this->level($model), //患者推荐指数
            'source' => $model->source, //医生来源
            'length' => $model->length, //间隔时间
            'web' => $model->web, //是否开通网诊 1:开通 0:关闭
            'web_amount' => $model->web_amount / 100, //网诊的价格(元)
            'video_amount' => $model->video_amount / 100, //视频的价格(元)
            'clinic' => $model->clinic, //是否门诊 1:开通 0:关闭
            'clinic_monopoly' => $model->clinic_monopoly, //医生独占
            'im_token' => $model->im_token, //im_token
            'status' => $model->status,
            'rest' => $model->rest,// 1休息0不休息
            'isSchedules' => $this->is_schedules($model->max_date),//$model->is_schedules($model->schedules),//schedules的标识，表示拥有排班
        ];
    }

    /**
     * 科室
     * @Auth: kingofzihua
     * @param $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeSections($model)
    {
        $section = $model->sections;

        if (!empty($section))

            return $this->collection($section, new SectionTransformer());
    }

    /**
     * 疾病
     * @Auth: kingofzihua
     * @param $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeDiseases($model)
    {
        $disease = $model->diseases;

        if (!empty($disease))

            return $this->collection($disease, new DiseaseTransformer());
    }

    /**
     * 获取评论
     * @Auth: kingofzihua
     * @param $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeComments($model)
    {
        $comments = $model->comments()->where('status', '1')->limit(3)->get();

        if (!empty($comments))

            return $this->collection($comments, new CommentTransformer());
    }

    /**
     * 医生的诊所
     * @param $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCliniques($model)
    {
        $cliniques = $model->cliniques;

        if (count($cliniques)) {//诊所 有诊所才有排班

            $cliniques = $cliniques->transform(function ($clinique) use ($model) {

//                $clinique->schedules = $model->schedules()->where('date', '>=', date('Y-m-d'))->where('clinique_id', $clinique->id)->groupBy('date')->limit(14)->get();

                $two_weeks_after_time = Carbon::parse('+1 month')->toDateTimeString();//两周后的时间

                $clinique->schedules = $model->schedules()->where('date', '>', date('Y-m-d'))->where('date', '<=', $two_weeks_after_time)->where('clinique_id', $clinique->id)->get();
                //添加一个是否有排班的判断
                if (count($clinique->schedules) > 0){
                    $clinique->isSchedules = 1;
                }else {
                    $clinique->isSchedules = 0;
                }
                return $clinique;
            });

            return $this->collection($cliniques, new CliniqueTransformer());
        }
    }

    /**
     *  是否使用后台自定义推荐指数
     * @desc
     * @author Eric
     * @DateTime 2018/3/22 11:44
     * @param $model
     */
    public function level($model)
    {
        if($model->use_diy==0)
            return $model->diy_level;
        if($model->use_diy==1)
            return $model->level;
        return $model->level;
    }

    /**
     * 判断出诊日期是否大于现在日期
     */
    public function is_schedules($model)
    {
        // return gettype(strtotime('2018-09-03'));
        // $time = date('Y-m-d', time());
        $time = time();//return $model;
        if (strtotime($model) > $time) {
            return 1;
        }else {
            return 0;
        }
        // foreach ($model as $key=>$value){
        //     return 123;
        //     return $value['date'];
        //     if (strtotime($value['date']) > $time){
        //         return strtotime($value['date']);
        //     }else{
        //         return strtotime($value['date']);
        //     }
        // }
        // return 22;
    }
}