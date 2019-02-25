<template lang='jade'>
.fixbody
    #wrap
        header
            .left(onclick="back()")
                i.icon-arrow-left
            .center 确认支付
        //.tips(v-if="error_status == 0")
            i.icon-sound
            span {{msg}}
            span.cor(@click="card()") 请点击查看 >
        .payment.pay_fs
            ul.payX
                li(v-for="(m,ind) in method" v-bind:class="method_set == ind ? 'active':''" @click="setMethod(ind,m.id)")
                    span(v-bind:class="m.id == 1 ? 'icon-bank-card':'icon-weixin'" )
                    a {{m.name}}
                    i.icon-check-c

    .btn.btn-fix
        .left
            span 总计：
            b ￥{{order.payable_amount}}
        .right.buy.topay(v-bind:class="{btn_cor:(!$store.state.wxready&&!$store.state.tcmuser) }" @click="repay()") 支付


</template>
<script>
    import {errorMsg} from '../../vuex/store';
    export default {
        data() {
            return{
                method:[
                    {
                        id:2,
                        name:'微信'
                    }
                ],
                tag:0,
                order: {},
                bespeak: {},
                bespeak_id: 0,

                subscirbe_type:0, //预约类型 0网诊 1门诊
                mid:1,
                method_set:0,
                total:0,
                order_id: 0,
                error_status:0,
                password: '',
                status:0 // 判断是否有会员卡
            }
        },
        created(){
            this.bespeak_id = this.$route.query.bespeak_id;
            this.order_id = this.$route.query.order_id;
            //            this.subscirbe_type = this.$route.query.type;
            //            this.clinic_money = this.$route.query.clinic_money;
            //            if(this.subscirbe_type==1){
            //                this.total = parseInt(this.clinic_money) - parseInt(50);
            //            }else{
            //                this.total = this.clinic_money;
            //            }
            //            shareConfig('/order');
        },
        watch:{
            bespeak_id(val){
                if(val>0){
                    this.get_bespeak();
                }
            },
            order_id(val){
              if(val>0){
                this.getOrder(val);
              }
            }
        },
        methods:{
            get_bespeak(){
               this.$http.get(this.$store.state.apiUrl + 'bespeak/detail/'+this.bespeak_id+'?include=order').then(function (res) {
                   this.bespeak = res.data.data;
                   if(!res.data.data.order){
                       $api.pop('订单信息缺失'); return false;
                   }else{
                       this.order = res.data.data.order.data;
                   }
               },function (response) {
                   errorMsg(response.data.data.errors);
               })
            },
            getOrder(order_id){
              this.$http.get(this.$store.state.apiUrl + 'order/detail/'+order_id).then(function (res) {
                if(!res.data.data){
                  $api.pop('订单信息缺失'); return false;
                }else{
                  this.order = res.data.data;
                }
              },function (response) {
                errorMsg(response.data.data.errors);
              })
            },
            repay(){
                if(!this.$store.state.wxready && !this.$store.state.tcmuser){
                    $api.pop('微信API准备未就绪，请稍后再试');
                    return false;
                }
                var _this = this;
                if(this.tag==0) {
                    $('.topay').html('订单处理中...');
                    _this.tag = 1;
                    _this.$http.get(_this.$store.state.apiUrl + 'pay/wechat/'+_this.order.id).then(function (res) {

                        if(res.data.status){
                            //免费订单处理
                            if(res.data.data.free_order && res.data.data.free_order == 1){

                                $api.pop('支付成功');
                                _this.$http.get(_this.$store.state.apiUrl + 'order/detail/'+ _this.order.id+'?include=bespeak.message.doctor').then(function (result) {
                                    if(result.data.data.status == 5 && result.data.data.order_type == 2){
                                        _this.$router.push({path:'/chat',query: { listId: result.data.data.bespeak.data.message.data.id, doctorName: result.data.data.bespeak.data.message.data.doctor.data.name }});
                                    }else if(result.data.data.order_type == 1){
                                        _this.$router.push({path:'/pay_sucess', query:{bespeak_id: result.data.data.bespeak.data.id}}); //门诊支付成功跳支付成功页面
                                    } else{
                                        if(_this.$store.state.tcmuser && true){
                                            _this.$router.push({path: '/order'});
                                        }else{
                                            window.location.href = '/wechat/order';
                                        }
                                    }
                                });
                            }else{
                                if(_this.$store.state.tcmuser && true){
                                    app.chooseWXPay(res.data.data,function (ret,err) {
                                        if(err){
                                            if(err.code == -2){
                                                $api.pop('支付取消');
                                            }else{
                                                $api.pop('支付失败');
                                            }
                                        }else if(ret&&ret.status){
                                            $api.pop('支付成功');
                                            _this.$http.get(_this.$store.state.apiUrl + 'order/detail/'+ _this.order.id+'?include=bespeak.message.doctor').then(function (result) {
                                                if(result.data.data.status == 5 && result.data.data.order_type == 2){
                                                    _this.$router.push({path:'/chat',query: {bespeakId:result.data.data.bespeak.data.id, listId: result.data.data.bespeak.data.message.data.id, doctorName: result.data.data.bespeak.data.message.data.doctor.data.name }});
                                                }else if(result.data.data.order_type == 1){
                                                    _this.$router.push({path:'/pay_sucess', query:{bespeak_id: result.data.data.bespeak.data.id}}); //门诊支付成功跳支付成功页面
                                                } else{
                                                    _this.$router.push({path:'/order'});//查询失败跳转到我的预约
                                                }
                                            });
                                        }else{
                                            $api.pop('支付失败');
                                        }
                                    })
                                }else{
                                    wx.chooseWXPay({
                                        timestamp: res.data.data.timestamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
                                        nonceStr: res.data.data.nonceStr, // 支付签名随机串，不长于 32 位
                                        package: res.data.data.package, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
                                        signType: res.data.data.signType, // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
                                        paySign: res.data.data.paySign, // 支付签名
                                        success: function (res) {
                                            if(res.errMsg == 'chooseWXPay:ok'){
                                                $api.pop('支付成功');
                                                _this.$http.get(_this.$store.state.apiUrl + 'order/detail/'+ _this.order.id+'?include=bespeak.message.doctor').then(function (result) {
                                                    if(result.data.data.status == 5 && result.data.data.order_type == 2){
                                                        _this.$router.push({path:'/chat',query: { listId: result.data.data.bespeak.data.message.data.id, doctorName: result.data.data.bespeak.data.message.data.doctor.data.name }});
                                                    }else if(result.data.data.order_type == 1){
                                                        _this.$router.push({path:'/pay_sucess', query:{bespeak_id: result.data.data.bespeak.data.id}}); //门诊支付成功跳支付成功页面
                                                    } else{
                                                        //_this.$router.push({path:'/order'});//查询失败跳转到我的预约
                                                        window.location.href = '/wechat/order';
                                                    }
                                                });
                                            }

                                        },
                                        fail:function(res){
                                            $api.pop('无法调起微信支付'+res.errMsg);
                                            setTimeout(function(){
                                                location.reload();
                                            },400);
                                        },
                                        cancel:function(res){
                                            $api.pop('支付取消');
                                        }
                                    });
                                }

                            }

                        }else{
                            $api.pop(res.data.msg);
                        }
                        setTimeout(function(){ _this.tag = 0;},200);
                        $('.topay').html('支付');
                    });

                }
            },
            payment:function () {
                $('.payment').removeClass("none");
            },
            backTo:function () {
                $('.payment').addClass("none");
            },
            bind:function(){
                $('.layer_pop').removeClass("none");
            },
            canceldel(){
                $('.layer_pop').addClass('none');
                this.tag = 0;
            },
            dodel(){
                $('.layer_pop').addClass('none');
                this.$router.push({path:'/my_card'});
            }
        }
    };

</script>
