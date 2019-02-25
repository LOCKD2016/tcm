<template lang='jade'>
#wrap
    header
        router-link.left(to="/order" tag="a")
            i.icon-arrow-left
        .center 我的预约

    template(v-if="info.type == 0")
        .btn.btn-fix(v-if="info.status < 15")
            .left
                span(@click="delDeal(info.id)") 取消预约
            .right.buy(v-if='info.status == 10' @click="confirm_order(info.id)") 立即支付
            .right.buy(v-else) 等待接诊

        .btn.btn-fix(v-if="info.status == 15", @click="chat(message.id,doctor.name)") 进入聊天

    template(v-else)
        .btn.btn-fix(v-if="info.status < 15")
            .left
                span(@click="delDeal(info.id)") 取消预约
            .right.buy(@click="confirm_order(info.id)") 立即支付

        //.btn.btn-fix(v-if="info.status == 15", @click="delDeal(info.id)") 取消预约

    .order_doctor
          img(v-bind:src="doctor.photoSUrl")
          p {{doctor.name}}
            span {{doctor.titleName}}

    ul.list-group(v-if="info.type == 1")
       li
          span 预约时间
          .val {{info.start_time}}
       li
         span 就诊医院
         .val 泰和国医

       li(v-if="order.payable_amount")
           span 支付
           .val 总计：
                span.x_price {{total}}
       li(v-if="info.status < 10")
           span 待支付
       li(v-if="info.status == 35")
           span 预约已取消

       //li.hos
          i.addr_a
          h3 {{clinique.name}}
          p {{clinique.address}}

    ul.list-group(v-else)
        li
            span 就诊人
            .val {{user.realname}}
        li(v-if='info.redundant_first==1')
            span 病名
            .val {{inquiry.disease}}
        li
            span 病情描述
            .des {{inquiry.desc}}
        li(v-if='info.redundant_first==0')
        li(v-if='info.redundant_first==0')
            span 症例
            .zhengli
                .zhenglipic(v-for='img in info.disease')
                    .img(v-bind:style="'background-image:url('+img+');background-size:100% 100%;'")

        li(v-if="info.status == 10 && order.payable_amount")
            span 需支付
            .val 总计：
                span.x_price {{order.payable_amount}}
        li(v-if="info.status < 10")
            span 待支付
        li(v-if="info.status == 35")
            span 预约已取消
    .tips(v-if="info.type == 1 && info.status < 15")
        .icon-tit
            i
        span 请在
        span.cor 15分钟
        span 内完成支付，超时则自动取消预约。
    .layer_pop.none
        .content
            .txt 您确定将该预约取消？
            .pop_btn.clearfix
                .p_btn.l(@click="dodel()") 确定
                .p_btn(@click="canceldel()") 取消

</template>

<script>
    export default {
        data(){
            return {
                info:{},
                doctor:{},
                clinique:{},
                order:{},
                id:2,
                again_type:'subscribe',
                total:0,
                inquiry: {},
                message: {},
                user: {},
            }
        },

        created(){
            this.id = this.$route.query.id;
            this.getDetail();
        },

        filters:{

        },

        methods:{
            chat(id,name) {
                this.$router.push({path:'/chat',query: { listId: id, doctorName:name }});
            },
            bg: function (url) {
                if (url) return 'background-image:url(' + url + ')'
            },
            confirm_order(bespeak_id){
                var self = this;
                this.$http.get(this.$store.state.apiUrl+'order/bespeak/'+bespeak_id).then(function (res) {
                    $api.pop(res.data.msg);
                    if(res.data.status == 1){
                        if(self.$store.state.tcmuser && true){
                            self.$router.push({path: '/payment',query: { bespeak_id: bespeak_id }});
                        }else{
                            window.location.href='/wechat/payment/?bespeak_id='+bespeak_id;
                        }
                    }
                });
            },
            getDetail() {
                this.$http.get(this.$store.state.apiUrl+'bespeak/detail/'+ this.id + '?include=doctor,clinique,order,inquiry,user,message').then(function (res) {
                    this.info = res.data.data;
                    if(res.data.data.doctor){
                      this.doctor = res.data.data.doctor.data;
                      if(!this.doctor.photoSUrl){
                          this.doctor.photoSUrl = '/img/doctor_default.png';
                      }
                    }
                    if(res.data.data.clinique){
                      this.clinique = res.data.data.clinique.data;
                    }
                    if(res.data.data.order){
                      this.order = res.data.data.order.data;
                      this.total = res.data.data.order.data.payable_amount;
                    }
                    if(res.data.data.inquiry){
                      this.inquiry = res.data.data.inquiry.data;
                    }
                    if(res.data.data.user){
                      this.user = res.data.data.user.data;
                    }
                    if(res.data.data.message){
                      this.message = res.data.data.message.data;
                    }
                });
            },
            totalPrice(value){
                this.total = value.order.data.payable_amount;
                // if(value.type == 1){
                //     this.total = !isEmpty(value.order) && !isEmpty(value.order.data.order_sn) && value.order.data.status >= 5 ? value.order.data.pay_amount : value.order.data.amount;
                // }else{
                //     if(!isEmpty(value.data.order) && !isEmpty(value.order.data.order_sn)){
                //         this.total = value.order.data.status >= 5 ? value.order.data.amount : value.data.order.pay_amount;
                //     }else{
                //         this.total = value.doctor.data.web_amount;
                //     }
                // }
            },
            paymethodClinic(id,type,clinic_money){
                this.$router.push({path:'/payment',query: { id: id, type:type,clinic_money:clinic_money }});
            },
            paymethod(status,id,type){
                if(status != 3){
                    $api.pop('请等待医生接诊后支付');
                    return false;
                }
                this.$router.push({path:'/payment',query: { id: id, type:type, clinic_money:clinic_money }});
            },
            ask(id,name){
                this.$router.push({path:'/chat',query: { clinicId: id, doctorName:name }});
            },
            again:function(id,order_sn,type,return_money){
                this.$http({url:this.$store.state.apiUrl+'clinic/again',method:'GET',params:{id:id,type:this.again_type}}).then(function (res) {
                    if(res.data.status){
                        this.$router.push({path:'/payment',query: { id: order_sn,type:type,clinic_money:return_money}});
                    }else{
                        $api.pop(res.data.msg);
                    }
                });
            },
            delDeal(id){
                $('.layer_pop').removeClass('none');
                this.delid = id;
            },
            canceldel(){
                $('.layer_pop').addClass('none');
            },
            dodel(){
                var self = this;
                var id = this.delid;
                if(id){
                    self.$http.get(self.$store.state.apiUrl+'bespeak/close/' + id).then(function (res) {
                        if (res.data.status == 1) {
                            self.getDetail();
                        } else {
                            $api.pop(res.data.msg);
                        }
                    })
                }
                $('.layer_pop').addClass('none');
                this.getDetail();
            },
            online:function(){
                this.$router.push({path:'/doctor/preOnline',query: { id: this.id, type:this.type }});
            },
            getComment:function(){
                this.$router.push({path:'/doctor/allComment'});
            }
        }
    };
</script>
