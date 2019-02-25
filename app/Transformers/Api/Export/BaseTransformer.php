<?php

namespace App\Transformers\Api\Export;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;

abstract class BaseTransformer extends TransformerAbstract
{
    /**
     * @var string
     */
    protected $avatar = 'http://wx.idanggui.com/static/img/avatar.png';

    public function transform(Model $model)
    {
        $data = $this->transformData($model);

        // 转换 null 字段为空字符串
        foreach (array_keys($data) as $key) {
            if (!isset($data[$key])) {
                $data[$key] = '';
                continue;
            }
            if (is_null($data[$key])) {
                $data[$key] = '';
            }
        }

        return $data;
    }

    /**
     * 获取支付的方式
     * @Auth: kingofzihua
     * @param $code
     * @return string 支付方式0未付款 1 会员卡 2 微信 3 支付宝
     */
    public function getPayType($code)
    {
        switch ($code) {

            case 0:
                return '未付款';
                break;
            case 1:
                return '会员卡';
                break;
            case 2:
                return '微信';
                break;
            case 3:
                return '支付宝';
                break;
            default:
                return '未知';

        }
    }

    /**
     * 获取预约状态
     * @Auth: kingofzihua
     * @param $model
     * @return string 1 取消预约 2等待接诊  3 接诊 4 转诊中 5 转诊接诊 6拒绝 7诊疗结束
     */
    public function status($code)
    {
        switch ($code) {
            case 1:
                return '取消预约';
                break;
            case 2:
                return '等待接诊';
                break;
            case 3:
                return '医生接诊等待支付';
                break;
            case 4:
                return '转诊中';
                break;
            case 5:
                return '转诊接诊';
                break;
            case 6:
                return '拒绝';
                break;
            case 7:
                return '诊疗结束';
                break;
            case 8:
                return '诊疗中';
                break;
            case 9:
                return '已过期';
                break;
            case 10:
                return '诊疗结束';
                break;
            case 11:
                return '复诊未支付';
                break;
            case 12:
                return '转诊中';
                break;
            case 13:
                return '转诊接诊';
                break;
            default:
                return '未知';
        }
    }

    //0:商城 1:网诊 2:门诊预约 3:药费(药材费用+代煎) 4:会员卡充值 5:会员卡办卡 6:排队预约 7:传方抓药的订单
    public function order_type($model)
    {
        if ($model == 0) {
            return '商城';
        } else if ($model == 1) {
            return "网诊";
        } else if ($model == 2) {
            return "门诊";
        } else if ($model == 3) {
            return "网诊药费";
        } else if ($model == 4) {
            return "会员卡充值";
        } else if ($model == 5) {
            return "会员卡办卡";
        } else if ($model == 6) {
            return "排队预约";
        } else if ($model == 7) {
            return "传方抓药";
        } else {
            return "未知";
        }
    }


    //支付状态;0:未付款 1:付款中 2:已付款 3:已退款 4:退款中
    public function pay_status($model)
    {
        if ($model == 0) {
            return "未付款";
        } elseif ($model == 1) {
            return "付款中";
        } elseif ($model == 2) {
            return "已付款";
        } elseif ($model == 3) {
            return "已退款";
        } elseif ($model == 4) {
            return "退款中";
        } else {
            return "未知";
        }
    }
    //0:未划价 1:已划价 2:拒绝开方 3:已支付 4:已发货 5:已经到货 6:药方过期
    public function  is_price($model){
        if ($model == 0) {
            return "未划价";
        } elseif ($model == 1) {
            return "已划价";
        } elseif ($model == 2) {
            return "拒绝开方";
        } elseif ($model == 3) {
            return "已支付";
        } elseif ($model == 4) {
            return "已发货";
        } elseif($model ==5) {
            return "已经到货";
        }else{
            return "药方过期";
        }
    }

    abstract public function transformData($model);
}
