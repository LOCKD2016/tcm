<template lang='jade'>
.fixbody
  #wrap.cfpy_noprice.p_myorder_details
      header
        .left(onclick="back()")
          i.icon-arrow-left
        .center 确认订单
        .btn.btn-fix(v-if='order.status==0 || order.status==2')
            .left
              span 总计：
              b ￥{{order.amount}}
            .right.topay.buy(v-bind:class="{buy:(!$store.state.wxready&&!$store.state.tcmuser)}" @click="pay()") 立即支付

        ul.list-group.search_his(v-if='order.status==0 || order.status==2')
            li
                span 支付方式
                .val 微信支付

      .istisane(v-if='order.status==5&&order.prescription.express==1')
        .tipsbg
        .tipsword
          p 您的药品将在24小时完成配送！
          p.yesok(@click="goOrder") 我知道了

      .notisane(v-if='order.status==5&&order.prescription.express==0')
        .tipsbg
        .tipsword
          p.left 请您在24h~48h之内到泰和国医门诊取药
          p.left 泰和国医工作时间为 : 9:00-18:00
          p.left 联系方式 : 010-64176667 / 64178887
          p.yesok(@click="goOrder") 我知道了

</template>
<script>
    export default{
        data(){
            return{
               // info:[],
                recipe_head:[],
                order:{
                  prescription:{
                    tisane:0
                  },
                  status:0
                },
                goods:[],
                is_tisane:0,
                // type:0,
                is_express:0,
                password:'',
                tag:0,
                order_id: 0,
                ceshi:0
            }
        },
        created(){
            this.order_id = this.$route.query.order_id; //订单id
            // this.type = this.$route.query.type;
            this.getOrder(this.order_id);
        },
        watch:{
          order_id(val){
            if(val>0){
              this.getOrder(val);
            }
          }
        },
        mounted(){

        },
        methods:{
            // getRecipe(){
            //     this.$http.get(this.$store.state.apiUrl+'prescription/detail/'+this.id).then(function (res) {
            //       console.log(res)
            //         this.info = res.data.data;
            //         this.order = res.data.data.order;
            //         this.recipe_head = res.data.data.recipe_head;
            //         this.recipe_self = res.data.data.recipe_self;
            //         this.goods = res.data.data.order.goods;
            //         this.is_tisane = res.data.data.is_tisane;
            //         this.is_express = res.data.data.is_express;
            //     });
            // },
            goOrder(){
                this.$router.push({path:'/my_order/my'});
            },
            getOrder(order_id){
                var self = this;
              this.$http.get(this.$store.state.apiUrl + 'order/detail/'+order_id).then(function (res) {
                if(!res.data.data){
                  $api.pop('订单信息缺失'); return false;
                }else{
                  this.order = res.data.data;
                  /*if(this.order.status==5){
                    setTimeout(function(){
                        if(self.$store.state.tcmuser && true){
                            self.$router.push({path:'/my_order/my'});
                        }else{
                            window.location.href='//'+window.location.host + '/wechat/my_order/my'
                        }
                    },8000)
                  }*/
                }
              },function (response) {
                errorMsg(response.data.data.errors);
              })
            },
            pay(){
                if(!this.$store.state.wxready && !this.$store.state.tcmuser){
                    $api.pop('微信API准备未就绪，请稍后再试');
                    return false;
                }
                let self = this;
                // if(this.type == 1){
                //     $('.layer_pop').removeClass('none');
                //     return false;
                // }
                // var obj = {};
                // obj.pay_type = this.type;
                // obj.recipe_id = this.id;// 订单编号
                // var recipe_id = this.id;
                if(this.tag==0) {
                    this.tag = 1;
                    $('.topay').html('订单处理中...');
                    // if(this.type == 2){
                        this.$http.get(this.$store.state.apiUrl + 'pay/wechat/'+this.order_id).then(function (res) {
                            $api.pop(res.data.msg);
                            if (res.data.status) {
                                if(this.$store.state.tcmuser && true){
                                    app.chooseWXPay(res.data.data,function (ret,err) {
                                        if(err){
                                            if(err.code == -2){
                                                $api.pop('支付取消');
                                            }else{
                                                $api.pop('支付失败');
                                            }
                                        }else if(ret&&ret.status){
                                            self.order.status = 5;
                                            $api.pop('支付成功');
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
                                            if (res.errMsg == "chooseWXPay:ok") {
                                                self.order.status = 5
                                                //window.location.reload();
                                                $api.pop('支付成功');
                                            }
                                        },
                                        fail:function(res){
                                            $api.pop('无法调起微信支付'+res.errMsg);
                                            $('.topay').html(res.errMsg);
                                            setTimeout(function(){
                                                location.reload();
                                            },400);
                                        },
                                        cancel:function(res){
                                            $api.pop('支付取消');
                                        }
                                    });
                                }
                                //self.$router.push({path:'/my_order/my'});
                            }
                            setTimeout(function(){ self.tag = 0;},200);
                            $('.topay').html('支付');
                        });
                    // }else{
                    //     this.bind();
                    // }
                }
            },
            bind:function(){
                $('.layer_pop').removeClass("none");
                this.tag = 0;
            },
            card:function(){
                this.$router.push({path:'/my_card'});
            },
            balance(){
                this.$router.push({path:'/my_card_balance'});
            },
            canceldel(){
                $('.layer_pop').addClass('none');
                this.tag = 0;
            }
        }
    };
</script>

