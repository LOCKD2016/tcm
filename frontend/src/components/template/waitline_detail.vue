<template lang='jade'>
.fixbody
    header
        .left(@click="waitLine()")
            i.icon-arrow-left
        .center 排队详情
    .btn.btn-fix(v-if="info.status == 1" @click="pay(order.order_sn)") 立即支付
    .btn.btn-fix(v-if="info.status == 0")
        .left(@click="delDeal(info.id)")
            span 取消排队
        .right.pai 排队中
    #wrap
      .order_doctor
            img(v-bind:src="doctor.avatar")
            p {{doctor.name}}
              span {{doctor.title}}

      ul.list-group
         li
            span 预约时间
            .val {{info.date}}
         li
           span 就诊人
           .val {{family.name}}
         li
           span 就诊医馆
           .val.jz_hos
              span {{clinique.store}}
              span 北京市朝阳区高碑店华膳园国际传媒文化产业园3号楼A3 拷贝 2
         li
           span 需支付
           .val 总计：
                span.x_price ¥ {{total}}

      .tips
         .icon-tit
          i
         span 请在15分钟之内完成支付，超时则自动取消预约

    .layer_pop.none
        .content
            .txt 您确定将该排队取消？
            .pop_btn.clearfix
                .p_btn.l(@click="dodel()") 确定
                .p_btn(@click="canceldel()") 取消



</template>
<script>
    export default {
        data(){
            return {
                info:[],
                doctor:[],
                family:[],
                scheduling:[],
                clinique:[],
                order:[],
                total:0
            }
        },
        created(){
            this.id = this.$route.query.id;
            this.getDetail();
        },
        methods:{
            getDetail(){
                this.$http.get(this.$store.state.apiUrl+'queue/detail/'+this.id).then(function (res) {
                    this.info = res.data.data;
                    this.doctor = res.data.data.doctor;
                    this.family = res.data.data.family;
                    this.clinique = res.data.data.clinique;
                    this.scheduling = res.data.data.scheduling;
                    this.order = res.data.data.order;
                    this.total = parseInt(this.scheduling.fees) + parseInt(50);
                });
            },
            waitLine:function(){
                this.$router.push({path:'/waitline_record'});
            },
            bg: function (url) {
                if (url) return 'background-image:url(' + url + ')'
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
                    self.$http.get(self.$store.state.apiUrl+'queue/cancelqueue/' + id).then(function (res) {
                        if (res.data.status == 1) {
                            this.$router.push({path:'/waitline_record'});
                        } else {
                            $api.pop(res.data.msg);
                        }
                    })
                }
                $('.layer_pop').addClass('none');
            },
            pay(id) {
                this.$router.push({path:'/payment',query: { id: id }});
            },
        }
    };
</script>