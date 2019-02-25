<?php

namespace App\Repository;

use App\Models\Message;
use App\Models\Prescription;

/**
 * Class PrescriptionRepository
 * @Auth: kingofzihua
 * @package App\Repository
 */
class PrescriptionRepository extends Repository
{
    /**
     * 通过诊疗编号查询
     * @param $clinic_id
     * @return mixed
     */
    public function get_data_by_clinic_id($clinic_id)
    {
        return $this->model->queryClinicId($clinic_id)->first();
    }

    /**
     * 通过订单编号获取 数据
     * @Auth: kingofzihua
     * @param $order_id
     * @return mixed
     */
    public function get_data_by_order_id($order_id)
    {
        return $this->model->queryOrderId($order_id)->first();
    }

    /** 通过订单id修改药方为支付状态
     * @desc
     * @author Eric Chow
     * @DateTime 2018/2/6 16:56
     * @param $order_id
     */
    public function set_pay_status_by_order_id($order_id)
    {
        $prescription = $this->model->queryOrderId($order_id)->first();
        $prescription->is_price = 3;//已支付
        $prescription->save();
    }

    /**
     * 给药方添加单位 药方表和消息表都要更新
     * @desc
     * @author Eric Chow
     * @DateTime 2018/3/1 17:08
     * @param $order_id
     */
    public function add_prescription_unit($order_id)
    {
        $prescription = $this->model->queryOrderId($order_id)->first();
        if(isset($prescription->recipe)){
            $recipe = $prescription->recipe;
            foreach ($recipe as $k=>$v){
                $medicine = (new MedicineRepository())->get_medicine_by_name($v['name']);
                if(isset($medicine->unit))
                    $recipe[$k]['unit'] = $medicine->unit;
            }
            $prescription->recipe = $recipe;
            $prescription->save();

            if(isset($prescription->clinic_id) && $prescription->clinic_id > 0)
                $this->add_prescription_to_message($prescription->id, $recipe,$prescription->clinic_id, $prescription->is_price);
        }

    }

    /**
     * 修改message消息记录
     * @desc
     * @author Eric Chow
     * @DateTime 2018/3/1 17:25
     * @param $prescription_id //药方id
     * @param $is_price //药方状态
     * @param $recipe //药方
     * @param $clinic_id //诊疗id
     */
    public function add_prescription_to_message($prescription_id, $recipe, $clinic_id, $is_price)
    {

        $str = '';
        foreach ($recipe as $k=>$v) {
            $str .= $v['name'] . '(' . $v['dosage'] . $v['unit'] . ')';
        }
        $messages_list = (new MessageRepository())->get_message_lists_by_clinic_id($clinic_id);
        if(isset($messages_list->id) && $messages_list->id > 0){
            $messages = Message::where(['list_id' => $messages_list->id, 'msg_type' => 'card', 'type' => 2])->get();
            foreach ($messages as $k => $v) {
                if (isset($v->content['extra']['cType']) && $v->content['extra']['cType'] == 3 && $v->content['extra']['id'] == $prescription_id) {
                    $arr = $v->content;
                    $arr['extra']['recipe'] = $str;
                    $arr['extra']['is_price'] = $is_price;
                    $v->content = $arr;
                    $v->save();
                }
            }
        }

    }

    /**
     * 药方过期，修改message消息记录
     * @desc
     * @author Eric Chow
     * @DateTime 2018/3/2 10:41
     * @param $order_id
     */
    public function set_prescription_overdue_status($order_id)
    {
        $prescription = $this->model->queryOrderId($order_id)->first();

        if(isset($prescription->clinic_id) && $prescription->clinic_id > 0){
            $messages_list = (new MessageRepository())->get_message_lists_by_clinic_id($prescription->clinic_id);

            if(isset($messages_list->id) && $messages_list->id > 0){
                $messages = Message::where(['list_id' => $messages_list->id, 'msg_type' => 'card', 'type' => 2])->get();
                foreach ($messages as $k => $v) {
                    if (isset($v->content['extra']['cType']) && $v->content['extra']['cType'] == 3 && $v->content['extra']['id'] == $prescription->id) {
                        $arr = $v->content;
                        $arr['extra']['is_price'] = 8;
                        $v->content = $arr;
                        $v->save();
                    }
                }
            }
        }


    }

    /**
     * @Auth: kingofzihua
     * @return Prescription
     */
    public function model()
    {
        return new Prescription();
    }

    /*********************************************************************大国医接口**************************************************************************/
    /**
     * 获取药方列表
     * @param $search
     */
    public function get_platform_recription_list($search)
    {
        return $this->model
            ->with('doctor', 'user')
            ->queryDoctorCustomerCode($search['doctorCode'] ?? '')//医生编号
            ->queryUserCustomerCode($search['userCode'] ?? '')//患者编号
            ->get();
    }

}