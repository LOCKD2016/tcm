<?php
namespace App\Repository;

use App\Models\Clinique;
use App\Models\Orders;
use App\Services\TemplateServices;
use App\Util\Tools;

class TemplateRepository extends Repository
{

    /**
     *  发送接诊模板消息
     * @desc
     * @author Eric Chow
     * @DateTime 2018/2/1 13:23
     * @param $bespeak_id
     * @return array
     */
    public function send_accept_template_to_user($bespeak_id)
    {
        $bespeak = (new BespeakRepository)->get_detail($bespeak_id); // 预约

        $doctor = (new DoctorRepository)->get_doctor_by_id($bespeak->doctor_id);//医生

        $appUser = (new UserRepository)->get_detail_by_id($bespeak->user_id);//用户

        $inquiry = (new InquiryRepository)->get_detail_by_bespeak_id($bespeak_id);//标准问诊单

        $openid = (new UserRepository)->get_user_wechat_openid($bespeak->user_id);


        $data = [
            'first' => ['value' => '尊敬的用户您好，' . $doctor->name . '医师已接诊！', 'color' => '#173177'],
            'keyword1' => ['value' => '', 'color' => '#173177'],
            'keyword2' => ['value' => $doctor->name, 'color' => '#173177'],
            'keyword3' => ['value' => '在线咨询', 'color' => '#173177'],
            'keyword4' => ['value' => $appUser->realname, 'color' => '#173177'],
            'keyword5' => ['value' => $inquiry->desc, 'color' => '#173177'],
            'remark' => ['value' => '医生最多等待您三分钟，超时需重新挂号。', 'color' => '#173177']
        ];

        (new TemplateServices)->pushMsgTemplate($openid, config('app.url') . '/wechat/order', $data, 'OmX3Dg8bxTalJ1bAWyMny4WcfarUY4fp6xF93KczJxc');

        return [];

    }

    /**
     * 发送普通支付成功的模板消息
     * @desc
     * @author Eric Chow
     * @DateTime 2018/2/1 14:27
     */
    public function send_pay_common_message($order)
    {
        $openid = (new UserRepository)->get_user_wechat_openid($order->user_id);

        $data = [
            'first' => ['value' => '尊敬的用户您好，您已支付成功！', 'color' => '#173177'],
            'keyword1' => ['value' => $order->order_sn, 'color' => '#173177'],
            'keyword2' => ['value' => $order->body, 'color' => '#173177'],
            'keyword3' => ['value' => number_format($order->pay_amount / 100, 2, '.', ''), 'color' => '#173177'],
            'remark' => ['value' => '点击查看详情', 'color' => '#173177']
        ];

        (new TemplateServices)->pushMsgTemplate($openid, config('app.url').'/wechat/myorder_details?id='.$order->id, $data, '0p6DSXPj-4tazpDZOTEtVNrKfWH2frfOeeUfwyh6KR8');

        return [];
    }

    public function remind_user_recipe_is_send($prescription)
    {
        $openid = (new UserRepository)->get_user_wechat_openid($prescription->user_id);
        $doctor = (new DoctorRepository)->get_doctor_by_id($prescription->doctor_id);//医生

        $data = [
            'first' => ['value' => '尊敬的用户您好，医生已为您制定方案，点击查看', 'color' => '#173177'],
            'keyword1' => ['value' => $doctor->name, 'color' => '#173177'],
            'keyword2' => ['value' => $prescription->disease_en ?: '无', 'color' => '#173177'],
            'remark' => ['value' => '点击查看详情！', 'color' => '#173177'],
        ];

        (new TemplateServices)->pushMsgTemplate($openid, config('app.url').'/wechat/prescription/my/id?id='.$prescription->id, $data, '7okqq5VaBzmawOZHgtbd_x7NmedKMm0IVnzKXvhonM8');

        return [];
    }

    /**
     * 取消预约模板消息
     * @desc
     * @author Eric Chow
     * @DateTime 2018/2/1 18:50
     * @param $bespeak
     * @return array
     */
    public function cancel_bespeak_message($bespeak)
    {
        $openid = (new UserRepository)->get_user_wechat_openid($bespeak->user_id);
        $doctor = (new DoctorRepository)->get_doctor_by_id($bespeak->doctor_id);//医生
        $datetime = Tools::get_weekday($bespeak->start_time);

        //诊所信息
        $clinique = Clinique::where('code', 'GS_01')->first();
        $clinique_content = $clinique['content'];
//        $clinique_content = [
//            "address" => "北京市朝阳区新东路12号院 首开铂郡南区4号楼B1",
//            "longitude" => "116.458104",
//            "latitude" => "39.943186",
//        ];

        $data = [
            'first' => '尊敬的⽤户您好，您已取消了如下诊疗预约。',//尊敬的⽤户您好，您已取消了如下诊疗预约。
            'keyword1' => $datetime,//就诊时间:2017-06-20周二 12:46
            'keyword2' => $doctor->name,
            'keyword3' => $clinique['name'],//'泰和大国医馆',
            'keyword4' => $clinique_content['address'],//'北京市朝阳区新东路12号首开铂郡南区1号楼地下室-1层-1-001、-1-002',
            'keyword5' => $clinique['telephone'],//这个是医院的电话，虽然前端显示为客服电话，真正的客服电话有很多！
            'remark' => '如需再次预约请拨打客服电话。祝好！',//如需再次预约请拨打客服电话。祝好！
        ];

//        (new TemplateServices)->pushMsgTemplate($openid, config('app.url').'/wechat/order_details?id='.$bespeak->id, $data, '7okqq5VaBzmawOZHgtbd_x7NmedKMm0IVnzKXvhonM8');
        (new TemplateServices)->pushMsgTemplate($openid, config('app.url') . '/wechat/order', $data, 'YhAE4RiOgEVi7IpqvjRuayIR0DrwUtB4mwMOKi9NQ7A');

        return [];
    }

    /**订单发货通知
     * @desc
     * @author Eric Chow
     * @DateTime 2018/2/6 21:03
     * @param $order
     */
    public function send_goods_to_user_remind_message($order)
    {
        $openid = (new UserRepository)->get_user_wechat_openid($order->user_id);
        $data = [
            'first' => ['value' => '尊敬的用户您好，您购买的商品已发货！', 'color' => '#173177'],
            'keyword1' => ['value' => $order->order_sn, 'color' => '#173177'],
            'keyword2' => ['value' => $this->get_express_company($order->express_company), 'color' => '#173177'],
            'keyword3' => ['value' => $order->express_number ? $order->express_number : '', 'color' => '#173177'],
            'keyword4' => ['value' => date('Y年m月d日'), 'color' => '#173177'],
            'remark' => ['value' => '可点击查看订单详情及物流信息，感谢您的惠顾！', 'color' => '#173177'],
        ];
        (new TemplateServices)->pushMsgTemplate($openid, config('app.url') . '/wechat/express/'.$order->id, $data, 'slmMbybptF8bnAk_qWQHb3vp7c7SDYfU0DVa4TWUFNQ');

        return [];
    }

    /**
     *  获取快递公司名称
     * @param $express_company_tag
     */
    public function get_express_company($express_company_tag)
    {
        switch ($express_company_tag) {
            case 'sf':
                return '顺丰';
                break;
            case 'sto':
                return '申通';
                break;
            case 'yt':
                return '圆通';
                break;
            case 'yd':
                return '韵达';
                break;
            case 'tt':
                return '天天';
                break;
            case 'ems':
                return 'EMS';
                break;
            case 'zto':
                return '中通';
                break;
            case 'ht':
                return '汇通';
                break;
            case 'qf':
                return '全峰';
                break;
            case 'db':
                return '德邦';
                break;
            case 'gt':
                return '国通';
                break;
            case 'rfd':
                return '如风达';
                break;
            case 'jd':
                return '京东快递';
                break;
            case 'zjs':
                return '宅急送';
                break;
            case 'emsg':
                return 'EMS国际';
                break;
            case 'fedex':
                return 'Fedex国际';
                break;
            case 'yzgn':
                return '邮政国内（挂号信）';
                break;
            case 'ups':
                return 'UPS国际快递';
                break;
            case 'ztky':
                return '中铁快运';
                break;
            case 'jiaji':
                return '佳吉快运';
                break;
            case 'suer':
                return '速尔快递';
                break;
            case 'xfwl':
                return '信丰物流';
                break;
            case 'yousu':
                return '优速快递';
                break;
            case 'zhongyou':
                return '中邮物流';
                break;
            case 'tdhy':
                return '天地华宇';
                break;
            case 'axd':
                return '安信达快递';
                break;
            case 'kuaijie':
                return '快捷速递';
                break;
            default :
                return '顺丰';
                break;
        }
    }


    public function model()
    {
        return new Orders();
    }

}





























