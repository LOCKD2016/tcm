<template lang='jade'>
.fixbody
    header
        router-link.left(to="/my" tag="a")
            i.icon-arrow-left
        .center 我的订单
    #wrap
        .swiper-container.swiper-container-list
            .orderBOX.swiper-wrapper
                .swiper-slide
                    .order_list(v-for="list in lists")
                        .mark(v-if="list.order_type==1" v-bind:type=1)
                        .mark(v-if="list.order_type==2" v-bind:type=2)
                        .mark(v-if="list.order_type==3" v-bind:type=3)
                        .head(v-if="list.order_type==1") 门诊预约订单{{list.status | returnStatus}}h_nav1
                        .head(v-if="list.order_type==2") 在线咨询预约订单{{list.status | returnStatus}}
                        .head(v-if="list.order_type==3") 药方订单{{list.status | returnStatus}}
                        //1：门诊预约
                        template(v-if="list.order_type==1")
                            .main(@click="detSub(list.id,list.order_type)")
                                .doctors(v-if="list.bespeak")
                                    .avatar(v-if="list.bespeak.data.doctor.data.photoSUrl" v-bind:style="bg(list.bespeak.data.doctor.data.photoSUrl)")
                                    .avatar(v-else v-bind:style="bg('/img/doctor_default.png')")
                                    h3 {{list.bespeak.data.doctor.data.name}}
                                    p {{list.bespeak.data.doctor.data.titleName}}
                                .hospital(v-if="list.bespeak.data.clinique")
                                       i.addr_a
                                       h3 {{list.bespeak.data.clinique.data.name}}
                                       p {{list.bespeak.data.clinique.data.address}}
                            .foot
                                .price
                                    span 医师服务费：￥
                                    b {{list.amount}}
                                  //.btn.btn-o.btn-jv(v-if="list.recipe != 0" @click="recipe(list.recipe,list.comment)") 查看药方
                                .btn.btn-o.btn-jv(v-if="list.express == 1" @click="logistics(list.id)") 查看物流
                                .btn.btn-o.btn-jv(v-if="list.status == 0 || list.status == 2" @click="repay(list.id)") 立即支付
                                //.btn.btn-o.btn-jv(v-if="list.comment == 1 && list" @click="comment(list.id)") 评价

                        //2:网诊预约
                        template(v-if="list.order_type==2" )
                            .main(@click="detSub(list.id,list.order_type)")
                                .doctors(v-if="list.bespeak")
                                    .avatar(v-if="list.bespeak.data.doctor.data.photoSUrl" v-bind:style="bg(list.bespeak.data.doctor.data.photoSUrl)")
                                    .avatar(v-else v-bind:style="bg('/img/doctor_default.png')")
                                    h3 {{list.bespeak.data.doctor.data.name}}
                                    p {{list.bespeak.data.doctor.data.titleName}}
                                    .price_a 诊费：¥ {{list.amount}}
                                //.hospital
                                    i.addr_a
                                    h3(v-if="list.subcribe.scheduling_clique") {{list.subcribe.scheduling_clique.clinque.name[1]}}
                                    //p ttttttttttt
                            .foot
                                .price
                                    span 总计：￥
                                    b {{list.amount}}
                                .btn.btn-o.btn-jv(v-if="list.status == 0 || list.status == 2" @click="repay(list.id)") 立即支付
                                //.btn.btn-o.btn-jv(v-if="list.recipe != 0" @click="comment(list.id)") 评价

                        //3:药方订单
                        template(v-if="list.order_type==3" )
                            .main(@click="detSub(list.id,list.order_type)")
                                .medicine(v-if="list.prescription")
                                  //- h3 {{list.prescription.data.disease}}
                                  p 处方
                                    //span ({{list.prescription.data.recipe_head.data.sum}}} 副)
                                    .price_a ￥{{list.prescription.data.medicine_price}}
                                  p(v-if="list.prescription.data.tisane") 代煎
                                    .price_a(v-if="list.prescription.data.tisane == 1") ￥ {{list.prescription.data.tisane_price}}
                                  p(v-if="list.prescription.data.express") 快递地址 :
                                    span {{list.order_user.country}} {{list.order_user.province}} {{list.order_user.city}} {{list.order_user.district}} {{list.order_user.address}}
                                  p(v-if="list.prescription.data.express") 收货人 :
                                    span {{list.order_user.name}} {{list.order_user.mobile}}


                            .foot
                                .price
                                    span 总计：￥
                                    b {{list.amount}}
                                .btn.btn-o.btn-jv(v-if="list.status == 0 || list.status == 2" @click="repay(list.id,'recipe')") 立即支付
                                //.btn.btn-o.btn-jv(v-if="list.recipe != 0" @click="recipe(list.recipe,list.comment)") 查看药方
                                //.btn.btn-o.btn-jv(v-if="list.recipe != 0" @click="comment(list.id)") 评价


                    .visible(@click='loadMore')
    .pop
        .box
            .head
                i.icon-warning
                span 物流状态：已快递
            .main
                ul
                    li 快递公司：顺丰快递
                    li 快递单号：{{logistic.express_number}}

            .foot(onclick="$('.pop').fadeOut()") 我知道了
    .layer_pop.none
        .content
            .txt 您确定将该订单取消？
            .pop_btn.clearfix
                .p_btn.l(@click="dodel()") 确定
                .p_btn(@click="canceldel()") 取消

</template>
<script>

    export default {
        data() {
            return{
                lists:[],
                tag:0,
                delid: 0,
                logistic:[],
                page: 1,
                swiper:"",
                statusOrder:true
            }
        },
        mounted(){
            this.getList();

            var self = this

            setTimeout(function(){

                self.swiper =  new Swiper('.swiper-container', {
                    direction: 'vertical',
                    slidesPerView: 'auto',
                    observer: true,
                    observeParents: true,
                    mousewheelControl: true,
                    freeMode: true,
                    resistanceRatio : 0.7,
                    preventLinksPropagation : true,
                    preventClicks : true,
                    onTouchEnd: function(s){
                      console.log(s)
                        var _viewHeight = s.height;
                        var _contentHeight = s.virtualSize;
                        if(s.translate <= _viewHeight - _contentHeight - 50 && s.translate < 0) {
                            if(self.statusOrder){
                                $(".visible").html('正在加载...');
                                self.getList();
                                // setTimeout(function(){
                                //     s.update();
                                // },400);
                            }
                        }
                    }
                });

            },400)


        },
        events: {
            update(){
                this.getList();
            }
        },
        filters:{
            money(value){
                return !isEmpty(value.is_first) ? "复诊费：¥ "+value.doctor.return_money : "诊费：¥ " + value.doctor.clinic_money;
            },
            returnStatus(value){
                switch(value){
                    case(0):
                        return '未支付';
                        break;
                    case(2):
                        return '正在支付';
                        break;
                    case(5):
                        return '已支付';
                        break;
                    case(7):
                        return '已过期';
                        break;
                    case(9):
                        return '退款中';
                        break;
                    case(10):
                        return '已退款';
                        break;
                }
            }
        },
        methods:{
            find(id){
                this.$router.push({path:'/point',query: { id: id }});
            },

            //加载更多

            loadMore(){

                if(this.statusOrder){

                    this.getList()

                }

            },

            getList(){
               // $('.visible').css('display','none')
                let self = this;
                let oLoadTip = $('.orderBOX').find('.visible');
                this.$http({url:this.$store.state.apiUrl+'order/lists?include=bespeak.doctor,bespeak.clinique,goods,prescription',method:'GET',params:{page:this.page}}).then(function (res) {

                    console.log(res)

                    // setTimeout(function(){

                    //     if($('.orderBOX').height()>$('.sub-page .swiper-slide').height()){

                    //         $('.visible').css('display','none')

                    //     }else{

                    //         $('.visible').css('display','block')

                    //     }

                    // },300)

                    res.data.data.list.forEach(e =>{
                        self.lists.push(e);
                    })
                    self.page ++;

                    oLoadTip.show()

                    if(self.page> res.data.data.totalPage){
                        oLoadTip.html('没有更多数据了');
                        self.statusOrder = false;
                    } else {
                        oLoadTip.html('点击加载');
                        self.statusOrder = true;
                    }

                    setTimeout(function(){
                      self.swiper.update();
                    },400);

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
                    self.$http.get(self.$store.state.apiUrl+'order/del/' + id).then(function (res) {
                        if (res.data.status == 1) {
                            self.getList();
                        } else {
                            $api.pop(res.data.msg);
                        }
                    })
                }
                $('.layer_pop').addClass('none');
                this.getList();
            },
            number(id){
                this.$http.get(this.$store.state.apiUrl+'order/'+ id ).then(function (res) {
                    if(res.data.status == 1){
                        this.logistic = res.data.data;
                        $('.pop').fadeIn();
                    }
                });
            },
            logistics(id){
                this.$router.push({path:'/logistics',query: { id: id }});
            },
            detShop:function(id,type){
                if(type == 1 || type == 2){
                    this.$router.push({path:'/myorder_details',query: { id: id }});
                }else if(type==0){
                    this.$router.push({path:'/my_order_det/my/id',query: { id: id }});
                }else if(type==7){
                    this.$router.push({path:'/cfpy_orderdetails',query: { id: id }});
                }
            },

            detSub:function(id){
                this.$router.push({path:'/myorder_details',query: { id: id}});
            },
            bgRcipe:function(url){
                if(url){
                    var img = JSON.parse(url).recipe_photo;
                    return 'background-image:url('+img+')'
                }
            },
            bg:function(url){
                if(url){
                    return 'background-image:url('+url+')'
                }
            },
            cT:function(i){
                switch (i){
                    case 1:
                        return '已完成';
                    break;
                    case 2:
                        return '不在线';
                    break;
                    case 3:
                        return '商城';
                    break;
                    case 4:
                        return '推荐';
                    break;
                }
            },
            /*repay(id){

                if(this.$store.state.tcmuser){
                    $api.pop('微信接口在app上待开发，稍等');
                    return;
                }
                if(!this.$store.state.wxready){
                    $api.pop('微信API准备未就绪，请稍后再试');
                    return false;
                }
                var self = this;
                if(this.tag==0) {
                    this.tag = 1;
                    //$('.'+id+'repay').html('订单处理中...');
                    this.$http.get(this.$store.state.apiUrl + 'repay/' + id).then(function (res) {
                        if (res.data.status) {
                            wx.chooseWXPay({
                                timestamp: res.data.data.timestamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
                                nonceStr: res.data.data.nonceStr, // 支付签名随机串，不长于 32 位
                                package: res.data.data.package, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
                                signType: res.data.data.signType, // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
                                paySign: res.data.data.paySign, // 支付签名
                                success: function (res) {
                                    // 支付成功后的回调函数
                                    self.getList();
                                    //alert("success"+JSON.stringify(res1));
                                },
                                fail:function(res){
                                    $api.pop('无法调起微信支付'+res.errMsg)
                                    setTimeout(function(){
                                        location.reload();
                                    },400);
                                }
                            });
                            setTimeout(function(){ self.tag = 0;},200);
                            //$('.'+id+'repay').html('立即支付');
                        }else{
                            $api.pop(res.data.msg);
                            self.tag = 0
                        }
                    });
                }
            },*/
            fill(id){
                var self = this;
                this.$http.get(this.$store.state.apiUrl+'order/answer/'+id).then(function(res){
                    var next_id = res.data.data.next_id;
                    var type = res.data.data.qtype;
                    var pre_id = res.data.data.pre_id;
                    if(type==0){
                        self.$router.push({path:'/wzd_radio' ,query: { id: next_id, pre_id: pre_id }});
                    }else if(type==1){
                        self.$router.push({path:'/wzd_check' ,query: { id: next_id, pre_id: pre_id }});
                    }else if(type==2){
                        self.$router.push({path:'/wzd_fill' ,query: { id: next_id, pre_id: pre_id }});
                    }else if(type==3){
                        self.$router.push({name:'填写问诊单' ,params: {id: id}});
                    }else{
                        self.$router.push({path:'/my_order/my/'});
                    }
                })
            },
            recipe(id,comment){
                this.$router.push({path:'/prescription/my/id',query: { id: id,comment:comment }});
            },
            comment(id){
                this.$router.push({path: '/tickling',query: { id: id }});
            },
            repay(order_id,type){
                if(typeof type != "undefined"){
                    this.$router.push({path: '/payment/recipe',query: { order_id: order_id }});
                }else{
                    if(this.$store.state.tcmuser && true){
                        this.$router.push({path: '/payment',query: { order_id: order_id }});
                    }else{
                        window.location.href = '/wechat/payment/?order_id=' + order_id;
                    }
                }
            }
        }
    };
</script>
