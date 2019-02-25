<template lang='jade'>
.fixbody
  #wrap
      header
          .left(onclick="back()")
            i.icon-arrow-left
          .center 确认订单
      ul.list-group
          li
              a 预约医生
              .val {{doctor}}
          li
              a 就诊医院
              .val {{clinque}}
          li
            a 医事服务费
            .val ¥ 500.00
          //li
              a 挂号费
              .val ¥ 50
          //li
              a 诊费
              .val ¥ {{parseInt(clinic_money)}}
          li
              a 就诊时间
              .val {{date}} {{time}}
          //li
              a 就诊人
              .val {{family}}
          //li(@click="payment()")
              a 支付方式
              .val 微信
              i.icon-arrow-right
          //li
              a 总计：
              .val ¥ {{total}}
      //.btn.btn-fix(@click="Order(info.id)") 确认订单
      .btn.btn-fix
          .left
              span 总计：
              b ￥ 500.00
          .right.buy(@click="Order(info.id)") 立即预约

  //支付方式
  .payment.none
       header
          .left(@click="backTo()")
              i.icon-arrow-left
          .center 支付方式
          .right 确定
       .head 请选择支付方式
       ul.payX
           li.ca.active(@click="bind()")
              span.icon-bank-card
              a 会员卡
                span 免费挂号
              i.icon-check-c
           li
              span.icon-weixin
              a 微信
              i.icon-check-c
           li
              span.icon-alipay
              a 支付宝
              i.icon-check-c
  //- .layer_pop.none
  //-     .content
  //-         .txt 请确认您的预约信息，付款后将不能退款！
  //-         .pop_btn.clearfix
  //-             .p_btn(@click="canceldel()") 取消
  //-             .p_btn.l(@click="Order(info.id)") 确定
</template>
<script>
    import {errorMsg} from '../../vuex/store';
    export default {
        data() {
            return{
                subscribe_id:0,
                bespeak_id:0,
                total:0,
                tag:0,
                store:'',
                info:[],
                doctor:[],
                family:[],
            }
        },
        created(){
            this.clinique_id = this.$route.query.clinique_id;
            this.doctor_id = this.$route.query.doctor_id;
            this.date = this.$route.query.date;
            this.time = this.$route.query.time;
            this.doctor = this.$route.query.doctor;
            this.clinque = this.$route.query.clinque;
            this.bespeak_id = this.$route.query.bespeak_id;
            //this.total = parseInt(50);
        },
        ready(){

        },
        methods:{
            Order(){
                var self = this;
                this.$http.get(this.$store.state.apiUrl+'order/bespeak/'+this.bespeak_id).then(function (res) {
                    $api.pop(res.data.msg);
                    if(res.data.status == 1){
                        if(this.$store.state.tcmuser && true){
                            this.$router.push({path:'/payment/',query: {bespeak_id: self.bespeak_id}});
                        }else{
                            window.location.href='/wechat/payment/?bespeak_id='+self.bespeak_id;
                        }
                    }
                },function (response) {
                    errorMsg(response.data.data.errors);
                });
            },
            payment:function () {
                $('.payment').removeClass("none");
            },
            backTo:function () {
                $('.payment').addClass("none");
            }
            // bind:function(){
            //      $('.layer_pop').removeClass("none");
            // },
            // canceldel(){
            //     $('.layer_pop').addClass('none');
            // },
            // dodel(){
            //     $('.layer_pop').addClass('none');
            // }

        }
    };

</script>
