<?php

namespace App\Repository;

use Carbon\Carbon;
use App\Models\Message;
use App\Models\MessageList;

/**
 * Class MessageRepository
 * @package App\Repository
 */
class MessageRepository extends Repository
{

    /**
     * 创建列表
     * @param $data
     * @return static
     */
    public function list_create($data)
    {
        return $this->list_model()->create($data);
    }

    /**
     * 发送系统消息
     * @param $list_id 列表编号
     * @param string $message
     * @param null $time
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function send_system_message($list_id, $message = '系统消息', $time = null)
    {
        $data = [
            'list_id' => $list_id,
            'msg_type' => 'text', //消息类型 text image audio card
            'type' => '3', //谁发送的 1患者 2医生 3系统
            'content' => [
                'text' => $message,
                'extra' => [
                    'timeShow' => '1',
                    'cType' => '',
                ],
            ],
            'created_at' => $time ?? Carbon::now(),
            'updated_at' => $time ?? Carbon::now(),
        ];

        return $this->create($data);
    }


    /**
     * 发送普通问诊单消息
     * @param $list_id
     * @param $inquiry_id
     * @param null $time
     * @return array
     */
    public function send_inquiry_message($list_id, $inquiry_id, $time = null)
    {
        $data = [
            'list_id' => $list_id,
            'msg_type' => 'card', //消息类型 text image audio cardsend_inquiry_message
            'type' => '1', //谁发送的 1患者 2医生 3系统
            'content' => [
                'text' => '标准问诊单',
                'extra' => [
                    'timeShow' => '1',
                    'cType' => '2', //2:普通问诊单
                    'id' => $inquiry_id,
                    'con' => '我已经填写完问诊单，请尽快查看'
                ],
            ],
            'created_at' => $time ?? Carbon::now(),
            'updated_at' => $time ?? Carbon::now(),
        ];

        return $this->create($data);
    }

    /**
     * 发送个性化问诊单
     * @param $list_id
     * @param $exam_id
     * @param null $time
     * @return array
     */
    public function send_exam_message($list_id, $exam_id, $time = null)
    {
        $data = [
            'list_id' => $list_id,
            'msg_type' => 'card', //消息类型 text image audio card
            'type' => '2', //谁发送的 1患者 2医生 3系统
            'content' => [
                'text' => '个性化问诊单',
                'extra' => [
                    'timeShow' => '1',
                    'cType' => '1', //1:个性化诊单
                    'id' => $exam_id,
                    'con' => '医生已为您定制了一份专属问诊单，请尽快填写',
                ],
            ],
            'created_at' => $time ?? Carbon::now(),
            'updated_at' => $time ?? Carbon::now(),
        ];

        return $this->create($data);
    }

    /**
     * 发送个性化问诊单回答完毕的消息
     * @param $list_id
     * @param $exam_id
     * @param $clinic_id 诊疗编号
     * @param null $time
     * @return array
     */
    public function send_exam_complete_message($list_id, $exam_id, $clinic_id, $time = null)
    {
        $data = [
            'list_id' => $list_id,
            'msg_type' => 'card',
            'type' => 1,
            'content' => [
                "text" => "个性化问诊单",
                "extra" => [
                    "cType" => 4,
                    "timeShow" => 1,
                    "id" => $exam_id,
                    'clinic_id' => $clinic_id,
                    "con" => '我已经填写完问诊单，请尽快查看'
                ]
            ],
            'created_at' => $time ?? Carbon::now(),
            'updated_at' => $time ?? Carbon::now(),
        ];

        return $this->create($data);
    }

    /**
     * 发送门诊药方信息
     * @desc
     * @author Eric
     * @DateTime 2018/3/20 11:49
     * @param $clinique_recipe
     */
    public function send_clinique_recipe_message($list_id, $clinique_recipe)
    {
        $str = '客户名称：'.$clinique_recipe[0]['CUSTOMER_NAME'].',    诊断名称：'
            .$clinique_recipe[0]['DiagnosisName'] . '    ';
        foreach ($clinique_recipe as $k=>$v){
            $str .= $v->SERVICE_NAME.$v->Dose.'('.$v->DoseUnitName.')   ';
        }
        $data = [
            'list_id' => $list_id,
            'msg_type' => 'card',
            'type' => 1,
            'content' => [
                "text" => "门诊处方",
                "extra" => [
                    "cType" => 5,
                    "timeShow" => 1,
                    "id" => 0,
                    "code" => $clinique_recipe[0]['RCPCLASS_CODE_NO'],
                    'clinic_id' => 0,
                    "con" => $str
                ]
            ],
            'created_at' => $time ?? Carbon::now(),
            'updated_at' => $time ?? Carbon::now(),
        ];

        return $this->create($data);
    }

    /**
     *  发送门诊病历到聊天列表中
     * @desc
     * @author Eric
     * @DateTime 2018/3/22 14:05
     * @param $clinique_exam_list
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function send_clinique_exam_message($list_id, $clinique_exam_list)
    {

        foreach ($clinique_exam_list as $k=>$v){
            $str = '预约显示编号：'.$v->Bespeak_CODE_NO.', ◇◇◇客户名称：'
                .$v->CUSTOMER_NAME
                .', ◇◇◇主诉：'.$v->ChiefComplete.', ◇◇◇现病史：'.$v->HisIllness.', ◇◇◇既往史：'
                .$v->Casehistory.', ◇◇◇家族史：'.$v->Familyhistory
                .', ◇◇◇过敏史：'.$v->Allergy.', ◇◇◇个人史：'.$v->PersonageHistory
                .', ◇◇◇婚育史：'.$v->Bearinghistory.', ◇◇◇医生名称：'.$v->RCP_DOC_NAME
                .', ◇◇◇部门名称：'.$v->DEPARTMENT_NAME.', ◇◇◇创建时间：'.$v->CreateDateTime;

            $data = [
                'list_id' => $list_id,
                'msg_type' => 'card',
                'type' => 1,
                'content' => [
                    "text" => "门诊病历",
                    "extra" => [
                        "cType" => 5,
                        "timeShow" => 1,
                        "id" => 0,
                        'clinic_id' => 0,
                        "con" => $str
                    ]
                ],
                'created_at' => $time ?? Carbon::now(),
                'updated_at' => $time ?? Carbon::now(),
            ];

            $this->create($data);
        }

    }


    /**
     * 通过用户获取列表
     * @param $user_id
     * @return mixed
     */
    public function get_list_by_user($user_id)
    {
        return $this->list_model()->queryUser($user_id)->orderBy('updated_at', 'desc')->paginate($this->page);
    }

    /**
     * 通过医生获取聊天列表数据
     * @param $doctor_id
     * @return mixed
     */
    public function get_list_by_doctor($name,$doctor_id)
    {
        $list = $this->list_model()->queryDoctor($doctor_id)->orderBy('updated_at', 'desc');

        if (isset($name) && !empty($name)) {

            return $list->whereHas('user',function($query)use($name){
                $query->where('nickname', 'like', '%' . $name . '%');
            })->paginate($this->page);

        } else {
            return $list->paginate($this->page);
        }
        //return $this->list_model()->queryDoctor($doctor_id)->orderBy('updated_at', 'desc')->paginate($this->page);
    }

    /**
     * 通过医生和用户 查询列表数据
     * @param $user_id
     * @param $doctor_id
     * @return mixed
     */
    public function get_lists_by_user_and_doctor($user_id, $doctor_id)
    {
        return $this->list_model()->queryDoctor($doctor_id)->queryUser($user_id)->first();
    }

    /**
     * 医生消息已读 => 消息列表未读数量归零
     * @param $list_id
     * @return mixed
     */
    public function doctor_message_list_read($list_id)
    {
        return $this->list_model()->queryId($list_id)->update(['doctor_new_num' => '0']);
    }

    /**
     * 用户的消息已读 => 消息列表未读数量归零
     * @param $list_id
     * @return mixed
     */
    public function user_message_list_read($list_id)
    {
        return $this->list_model()->queryId($list_id)->update(['user_new_num' => '0']);
    }

    /**
     * 通过编号获取消息列表详情
     * @param $list_id
     * @return mixed
     */
    public function get_message_lists_by_id($list_id)
    {
        return $this->list_model()->find($list_id);
    }

    /**
     * 通过编号获取消息列表详情
     * @param $clinic_id
     * @return mixed
     */
    public function get_message_lists_by_clinic_id($clinic_id)
    {
        return $this->list_model()->queryClinicId($clinic_id)->first();
    }

    /**
     * @return MessageList
     */
    public function list_model()
    {
        return new MessageList();
    }

    /**
     * @return Message
     */
    public function model()
    {
        return new Message();
    }

}