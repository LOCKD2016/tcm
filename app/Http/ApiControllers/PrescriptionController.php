<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017/11/20
 * Time: 17:31
 */
namespace App\Http\ApiControllers;

use App\Models\Bespeak;
use App\Models\Prescription;
use App\Models\Clinic;
use App\Models\Orders;
use App\Models\Message;
use App\Models\Medicine;
use App\Models\Disease;
use App\Models\MessageList;
use App\Models\Comment;
use App\Repository\TemplateRepository;
use Auth;
use App\Util\Tools;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\ApiTransformers\PrescriptionTransformer;
/**
 * Class PrescriptionController
 * @package App\Http\ApiControllers
 */
class PrescriptionController extends Controller
{
    /**
     * @var
     */
    protected $prescription;

    /**
     * PrescriptionController constructor.
     * @param $prescription
     */
    public function __construct(Prescription $prescription)
    {
        $this->prescription = $prescription;
    }

    //测试
    public function test(Request $request){

    }




    /**
     * 开处方
     * @param $request
     * @return mixed

     */
    public function add(Request $request,Clinic $Clinic,Bespeak $bespeak)
    {

        //验证
        $validator = $this->prescription->verification_data($request->all());

        if ($validator->fails()) {
            return $this->error(101, $validator->errors()->first());
        }
//        $list_id = $request->list_id;
//        $recipe = '';
//        if($request->recipe && is_array($request->recipe)){
//            foreach($request->recipe as $key=>$val){
//                $recipe .= $val['name'].'('.$val['dosage'].$val['unit'].')';
//            }
//        }
        $recipe = [];
        if(isset($request->recipe)){
            $recipe = $request->recipe;
            foreach($recipe as $key=>$val){
                $recipe[$key]['unit'] = '';
            }
        }
        if(!count($recipe))
            return $this->error(101,'没有获取到药方');

        //一个诊疗开一个药方的时候可以在这查询是否已经开过药方了
        $recipeHas = $this->prescription->get_clinic_id($request->clinic_id);

        if (!$recipeHas) {//没有开过药方
            //获取诊疗
            $clinic_find = $Clinic->getFind($request->clinic_id);

            if ($clinic_find) {
                //查询父订单的编号 根据预约的ID 获取预约的订单编号ID
                $bespeak_find = $bespeak->getFind($clinic_find->bespeak_id);
                if ($bespeak_find) {

                    //获取父订单信息
                    $order = Orders::find($bespeak_find->order_id);
                    //生成订单操作记录
                    if ($order) {
                        $prescriptionData = $request->only(['clinic_id', 'recipe_head', 'recipe_remark', 'disease','disease_zh','disease_en']);
                        $prescriptionData['recipe'] = $recipe;
                        //计算药方重量
                        $total = collect($prescriptionData['recipe'])->pluck('dosage')->sum();
                        $prescriptionData['recipe_head']['sumWeight'] = $total;

                        $prescriptionData['user_id'] = $clinic_find->user_id;

                        $prescriptionData['medicine_price'] = $this->price($prescriptionData['recipe']);

                        //创建处方
                        $prescription = $this->prescription->creates(array_merge($prescriptionData, ['order_id' => 0,'doctor_id'=>Auth::id()]));

                        $prescription['is_price'] = 0;

                        //修改父订单extend字段
                        $order->extend = json_encode(['recipe' => $prescription->id, 'clinic' => $request->clinic_id]);

                        //修改诊疗表的诊断记录
                        $clinic_find->disease = $request->disease;
                        if ($order->save() && $clinic_find->save()) {
                                (new TemplateRepository())->remind_user_recipe_is_send($prescription); //通知患者药方已发送
                                return $this->success($prescription, "开处方成功");
                        }
                        return $this->error("101", "开处方失败,预约订单字段修改失败");
                    }

                    return $this->error("101", "开处方失败,预约订单找不到了呢");

                }
                return $this->error("101", "开处方失败,预约找不到了呢");
            }

            return $this->error("101", "开处方失败,当前诊疗不存在");
        }
        return $this->error("101", "开处方失败,当前诊疗已经开过药方");
    }



    /**
     * 修改处方
     * @param $data
     * @return string
     */
    public function edit(Request $request,$id)
    {
        $requestData = $request->all();
        //验证
        $validator = $this->prescription->verification_edit($requestData);

        if ($validator->fails()) {
            return $this->error(101, $validator->errors()->first());
        }

        $recipe = [];
        if(isset($request->recipe)){
            $recipe = $request->recipe;
            foreach($recipe as $key=>$val){
                $recipe[$key]['unit'] = '';
            }
        }
        if(!count($recipe))
            return $this->error(101,'没有获取到药方');


        $prescription = $this->prescription->getFind($id);

        if (!$prescription)
            return $this->error(404, '处方不存在');
        if ($prescription->order_id != 0)
            return $this->error(404, '处方已生成订单，不可编辑');

        //判断诊疗是否是最后一条
        $clinic_max_id = Clinic::selectRaw('max(id) as id')
            ->where(['doctor_id'=>$prescription->doctor_id,'user_id'=>$prescription->user_id])->get();
        if($clinic_max_id[0]['id'] != $prescription->clinic_id){
            return $this->error(404, '该诊疗不能修改');
        }

        //判断诊疗是否结束12312
        $clinic = Clinic::where('id', $prescription->clinic_id)->first();
        if (!$clinic || $clinic->status == 2) {
            return $this->error(404, '诊疗已经结束不可修改处方!');
        }

        $initArr = ['tisane_price' => 0, 'dispensing_price' => 0, 'recipe_self_price' => 0];
        $prescriptionData = $request->only(['clinic_id', 'recipe_head', 'recipe_remark', 'disease','disease_zh','disease_en']);
        $prescriptionData['recipe'] = $recipe;
        //计算药方重量
        $total = collect($prescriptionData['recipe'])->pluck('dosage')->sum();
        $prescriptionData['recipe_head']['sumWeight'] = $total;


        $prescriptionData['medicine_price'] = $this->price($prescriptionData['recipe']);

        $data = $this->prescription->up($id, array_merge($prescriptionData, $initArr));

        if ($data) {
            //修改message消息记录
            $str = '';
            foreach ($requestData['recipe'] as $k=>$v) {
                //$str .= $v['name'] . '(' . $v['dosage'] . $v['unit'] . ')';
                if(isset($requestData['recipe'][$k+1]['name'])){
                    $str .= $v['name'].'、';
                }else{
                    $str .= $v['name'].'。';
                }

            }
            $messages_id = MessageList::where('clinic_id',$prescription->clinic_id)->value('id');
            $messages = Message::where(['list_id' => $messages_id, 'msg_type' => 'card', 'type' => 2])->get();
            foreach ($messages as $k => $v) {
                if (isset($v->content['extra']['cType']) && $v->content['extra']['cType'] == 3) {
                    $arr = $v->content;
                    $arr['extra']['recipe'] = $str;
                    $v->content = $arr;
                    $v->save();
                }
            }
            $ret = $this->prescription->getFind($id);
            (new TemplateRepository())->remind_user_recipe_is_send($prescription); //通知患者药方已发送
            return $this->success($ret,'修改成功');
        }
        return $this->error(100, '修改失败');
    }

    /**
     * 详情
     * @param $id
     * @return mixed
     */
    public function details($id)
    {
        $data = $this->prescription->getFind($id);
        if($data){
            return $this->success($data);
        }

        return $this->error(404,'药方不存在');
    }

    /**
     * 获取药材名称
     * @return mixed
     */
    public function get_all_medicine()
    {
        return Medicine::get();
    }

    /**
     * 获取疾病名称
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function search_disease(Request $request)
    {

        if ($request->has('name') && !empty($request->name)) {
            $data = Disease::where('name', 'like', '%' . $request->name . '%')->select('name')->get();
            return $this->success($data);
        }
        return $this->response->noContent();
    }



    //计算价格
    public function price($data)
    {

        $name_list = '';

         array_walk($data, function($value) use (&$name_list ){
            $name_list  += $value['dosage'] * $value['price'];
         });

         return $name_list;
    }





}
