<?php

namespace App\Repository;

use App\Models\AppUser;
use App\Models\Clinic;
use App\Models\CliniqueExam;
use App\Models\CliniqueRecipe;
use App\Services\SoapServices;

/**
 * Class ClinicRepository
 * @Auth: kingofzihua
 * @package App\Repository
 */
class ClinicRepository extends Repository
{
    /**
     * 通过用户获取最后一条诊疗信息
     * @return mixed
     */
    public function get_last_clinic_data_by_user($user_id)
    {
        return $this->model->queryUser($user_id)->orderBy('id', 'desc')->first();
    }

    /**
     * 通过用户编号和医生编号获取列表数据
     * @param $user_id
     * @return mixed
     */
    public function get_list_data_by_user_and_doctor($user_id, $doctor_id = '')
    {
        return $this->model->queryUser($user_id)->queryDoctor($doctor_id)->orderBy('id','desc')->paginate($this->page);
    }

    /**
     * desc 获取未评论的诊疗分页列表
     * Created on 2018/1/8 16:29
     * Create by zhoupeng
     * @return mixed
     */
    public function get_list_by_user_without_comments()
    {
        return $this->model->queryUser(\Auth::id())->where('comment','1')->orderBy('created_at','desc')->paginate($this->page);
    }

    /**
     *  通过HIS获取门诊处方并保存到本地
     * @desc
     * @author Eric
     * @DateTime 2018/3/22 13:51
     * @param $bespeak
     * @return array|string
     */
    public function get_clinique_recipe_by_user_and_doctor_and_save($bespeak)
    {
        if(count($bespeak) && isset($bespeak->type) && !$bespeak->type){
            $user = AppUser::with('cliniques')->find(14);
            $user_code = $user->cliniques()->first()->pivot->code;

            $list = (new SoapServices())->getRecipe(['customer_code'=>$user_code]);//接口返回的药方信息

            CliniqueRecipe::where('user_id', $bespeak->user_id)->delete();

            if($list['code']==200 && isset($list['data'][0]) && count($list['data'][0])){
                foreach ($list['data'] as $k=>$v){
                    $recipe_data = [
                        "user_id"=> $bespeak->user_id,
                        "Bespeak_CODE_NO" => $v['Bespeak_CODE_NO'] ? $v['Bespeak_CODE_NO'] : '',
                        "CUSTOMER_NAME" => $v['CUSTOMER_NAME'] ? $v['CUSTOMER_NAME'] : '',
                        "RCPCLASS_CODE_NO" => $v['RCPCLASS_CODE_NO'] ? $v['RCPCLASS_CODE_NO'] : '',
                        "SERVICE_CODE_NO" => $v['SERVICE_CODE_NO'] ? $v['SERVICE_CODE_NO'] : '',
                        "SERVICE_NAME" => $v['SERVICE_NAME'] ? $v['SERVICE_NAME'] : '',
                        "DrugUsageName" => $v['DrugUsageName'] ? $v['DrugUsageName'] : '',
                        "Dose" => $v['Dose'] ? $v['Dose'] : '',
                        "DoseUnitName" => $v['DoseUnitName'] ? $v['DoseUnitName'] : '',
                        "FrequencyName" => $v['FrequencyName'] ? $v['FrequencyName'] : '',
                        "Quantity" => $v['Quantity'] ? $v['Quantity'] : '',
                        "QuantityUnitName" => $v['QuantityUnitName'] ? $v['QuantityUnitName'] : '',
                        "AGENT_NUM" => $v['AGENT_NUM'] ? $v['AGENT_NUM'] : '',
                        "TISANES_WAY_NAME" => $v['TISANES_WAY_NAME'] ? $v['TISANES_WAY_NAME'] : '',
                        "TISANES_DOSE_NAME" => $v['TISANES_DOSE_NAME'] ? $v['TISANES_DOSE_NAME'] : '',
                        "DiagnosisName" => $v['DiagnosisName'] ? $v['DiagnosisName'] : '',
                        "RCP_DOC_NAME" => $v['RCP_DOC_NAME'] ? $v['RCP_DOC_NAME'] : '',
                        "DEPARTMENT_NAME" => $v['DEPARTMENT_NAME'] ? $v['DEPARTMENT_NAME'] : '',
                        "RCP_DATE" => $v['DEPARTMENT_NAME'] ? date('Y-m-d H:i:s', strtotime($v['DEPARTMENT_NAME'])) : '',
                    ];
                    (new CliniqueRecipe())->create($recipe_data);
                }

                //查询两次处方
                $recipe_code_arr = CliniqueRecipe::where('user_id', $bespeak->user_id)->orderBy('RCP_DATE', 'desc')->groupBy('RCPCLASS_CODE_NO')->limit(2)->pluck('RCPCLASS_CODE_NO')->toArray();
                $first_recipe = CliniqueRecipe::where('user_id', $bespeak->user_id)->where('RCPCLASS_CODE_NO', $recipe_code_arr[0])->orderBy('RCP_DATE', 'desc')->get();
                $second_recipe = CliniqueRecipe::where('user_id', $bespeak->user_id)->where('RCPCLASS_CODE_NO', $recipe_code_arr[1])->orderBy('RCP_DATE', 'desc')->get();
                return ['first_recipe'=>$first_recipe, 'second_recipe' => $second_recipe];
            }
        }
        return ['first_recipe'=>'', 'second_recipe' => ''];
    }

    // 获取门诊的病历并保存到本地
    public function get_clinique_exam_by_user_and_doctor_and_save($bespeak)
    {
        if(count($bespeak) && isset($bespeak->type) && !$bespeak->type) {
            $user = AppUser::with('cliniques')->find(14);
            $user_code = $user->cliniques()->first()->pivot->code;

            $list = (new SoapServices())->getExam(['customer_code' => $user_code]);//接口返回的病历信息

            CliniqueExam::where('user_id', $bespeak)->delete(); // 删除之前该用户的数据

            if($list['code']==200 && isset($list['data'][0]) && count($list['data'][0])){
                foreach ($list['data'] as $k=>$v){
                    $exam_data = [
                        'user_id' => $bespeak->user_id,
                        'Bespeak_CODE_NO' => $v['Bespeak_CODE_NO']?:'',
                        'CUSTOMER_NAME' => $v['Bespeak_CODE_NO']?:'',
                        'ChiefComplete' => $v['Bespeak_CODE_NO']?:'',
                        'HisIllness' => $v['Bespeak_CODE_NO']?:'',
                        'Casehistory' => $v['Bespeak_CODE_NO']?:'',
                        'Familyhistory' => $v['Bespeak_CODE_NO']?:'',
                        'Allergy' => $v['Bespeak_CODE_NO']?:'',
                        'PersonageHistory' => $v['Bespeak_CODE_NO']?:'',
                        'Bearinghistory' => $v['Bespeak_CODE_NO']?:'',
                        'RCP_DOC_NAME' => $v['Bespeak_CODE_NO']?:'',
                        'DEPARTMENT_NAME' => $v['Bespeak_CODE_NO']?:'',
                        'CreateDateTime' => $v['CreateDateTime'] ? date('Y-m-d H:i:s', strtotime($v['CreateDateTime'])) : '',
                    ];

                    (new CliniqueExam())->create($exam_data);
                }

                // 查询最新两次的病历信息
                return CliniqueExam::orderBy('CreateDateTime', 'desc')->limit(2)->get();
            }
        }
        return '';
    }

    /**
     * @Auth: kingofzihua
     * @return Clinic
     */
    public function model()
    {
        // TODO: Implement model() method.

        return new Clinic();
    }
}