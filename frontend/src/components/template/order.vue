<template lang='jade'>
.fixbody
    header
        router-link.left(to="/" tag="a" v-if="!$store.state.tcmuser")
            i.icon-arrow-left
        .center 我的预约
    #wrap
        .swiper-container-order.swiper-container-list
            .orderBOX.swiper-wrapper
                .shuju 下拉刷新
                .swiper-slide.swiper-slide-order
                    .order_list(v-for="(list,index) in lists")
                        .mark(v-if="list.type!=1" v-bind:type=2)
                        .mark(v-if="list.type==1" v-bind:type=1)
                        template
                            .head {{list.status | status_value}}
                        template(v-if="list.type==1")
                            template(v-if="list.status_code != 1")
                                .head 预约时间：{{list.start_time | time_format}}
                            //template(v-if="list.status_code == 1 && list.order.pay_status==4")
                                .head.return 退款中
                            //template(v-if="list.status_code == 1 && list.order.pay_status!=4")
                                .head 取消预约

                        //.head(v-if="list.type==1 && list.order.order_status==3") 订单关闭

                        .main(@click="det(list.id)")
                            .doctors
                                .avatar(v-if='list.doctor.data.photoSUrl' v-bind:style="bg(list.doctor.data.photoSUrl)")
                                .avatar(v-else v-bind:style="bg('/img/doctor_default.png')")
                                h3 {{list.doctor.data.name}}
                                p {{list.doctor.data.titleName}}
                                    //.price_b(v-if="list.type == 1") 押金：¥ 50
                                    //.price_a(v-if="list.is_first == 0") 诊费：¥ {{parseInt(list.doctor.clinic_money)}}
                                    //.price_a(v-else) 复诊费：¥ {{parseInt(list.doctor.return_money)}}
                            //.hospital(v-if="list.type==1")
                                i.addr_a
                                h3 {{list.store.name[1]}}
                                p {{list.store.place}}
                        .orderTips(v-if='list.type!=1 && list.status==10') 温馨提示：您还未支付咨询费，医生将等待您三分钟，还请您及时支付咨询费，三分钟内未支付咨询费，需要重新选择医生咨询。
                        .orderTips(v-if='list.type!=1 && list.status==5') 温馨提示：请耐心等待医生接诊，医生接诊后泰和国医公众号将发送服务信息给您（请确保您已关注泰和国医公众号，并确保文章推送已开启）。
                        .foot
                            .price(v-if="list.order && list.order.data.pay_amount > 0")
                                span 总价：￥
                                b {{list | price(list)}}
                            template(v-if="list.type!=1")
                                .btn.btn-o.btn-jv(v-if="list.status == 5" @click="delDeal(index)") 取消预约
                                //- .btn.btn-o.btn-jv(v-if="list.status == 10" @click="confirm_order(list.id)") 立即支付
                                .btn.btn-o.btn-jv(v-if="list.status < 15" @click="confirm_order(list.id)") 立即支付
                                //.btn.btn-o.btn-jv(v-if="(list.status_code == 7 || list.status_code == 10) && list.clinic.can_visit==1" @click="ask(list.clinic.id,list.doctor.name)") 追问
                                .btn.btn-o.btn-jv(v-if="list.type !=1 && (list.status == 15 || list.status == 20 || list.status == 25)" @click="chat(list.id)") 开始咨询
                                //.btn.btn-o.btn-jv(v-if="(list.status_code == 7 || list.status_code == 10) && list.clinic.can_visit==1" @click="again(list.id,list.type,list.doctor.return_money)") 复诊

                            template(v-else)
                                .btn.btn-o.disabled(v-if="list.order && list.order.data.status == 2") 退款中
                                //.btn.btn-o.btn-jv(v-if="list.order && list.order.data.status == 0" @click="payment(list.id)") 立即支付
                                .btn.btn-o.btn-jv(v-if="list.status == 10" @click="confirm_order(list.id)") 立即支付
                    .visible(@click='loadMore')
    .layer_pop.none
        .content
            .txt {{reminds}}
            .pop_btn.clearfix
                .p_btn.l(@click="dodel()") 确定
                .p_btn(@click="canceldel()") 取消
</template>

<script>
    import {errorMsg} from '../../vuex/store';
    export default {
        data() {
            return{
                lists:[],
                tag:0,
                delid: 0,
                pay_status: 0,
                indextmp: 0,
                type:0,
                subscribe_type:0,
                status:'',
                logistic:[],
                name:'',
                page: 1,
                again_type:'subscribe',
                method:[
                    {
                        id:2,
                        name:'微信'
                    }
                ],
                reminds:'',
                msg:"会员用户可免挂号费，更多福利",
                swiper:"",
                statusOrder:true
            }
        },
        created:function () {
            // this.$store.state.apiUrl = '/api'
            this.getMe();
        },
        mounted(){
            let self = this;
            this.getList();
            setTimeout(function(){
                self.swiper = new Swiper('.swiper-container-order', {
                    direction: 'vertical',
                    slidesPerView: 'auto',
                    //autoHeight:true,
                    observer: true,
                    observeParents: true,
                    mousewheelControl: true,
                    freeMode: true,
                    resistanceRatio : 0.7,
                    preventLinksPropagation : true,
                    preventClicks : true,

                    onTouchEnd: function(s){
                      var _viewHeight = s.height;
                      var _contentHeight = s.virtualSize;
                      if(s.translate <= _viewHeight - _contentHeight - 50 && s.translate < 0) {
                        if(self.statusOrder){
                          $(".visible").html('正在加载...');
                          self.getList();
                        }
                      }

                      //下拉刷新
                      if(s.translate>50){
                        window.location.reload();
                      }

                      //上拉加载
                      if (s.translate<0) {
                        setTimeout(function () {
                        },500)
                      }
                    },
                });
            },400);
            if(this.$store.state.tcmuser  && true){
                //$api.pop('微信接口在app上待开发，稍等');
                return;
            }
            wx.ready(function () {
                wx.onMenuShareTimeline({
                    title: '诚意为您推荐好中医【泰和国医】', // 分享标题
                    link: protocol+window.location.host+'/wechat/index', // 分享链接
                    imgUrl: protocol+window.location.host+'/static/img/wxlogo.png', // 分享图标
                    success: function () {
                        // 用户确认分享后执行的回调函数
                    },
                    cancel: function () {
                        // 用户取消分享后执行的回调函数
                    },fail:function(res){
                        alert(JSON.stringify(res))
                    }
                });
                wx.onMenuShareAppMessage({
                    title: '诚意为您推荐好中医【泰和国医】', // 分享标题
                    desc: '您可通过【泰和国医】微信公众账号关注医师，预约Ta的门诊或向Ta发起在线咨询。', // 分享描述
                    link: protocol+window.location.host+'/wechat/index', // 分享链接
                    imgUrl: protocol+window.location.host+'/static/img/wxlogo.png', // 分享图标
                    success: function () {
                        // 用户确认分享后执行的回调函数
                    },
                    cancel: function () {
                        // 用户取消分享后执行的回调函数
                    },fail:function(res){
                        alert(JSON.stringify(res))
                    }
                });
            })
        },
        events: {
            update(){
                this.getList();
                self.swiper.update();
            }
        },
        filters:{
            price:function(value){
                if(!isEmpty(value.order) && !isEmpty(value.order.data.order_sn)){
                    return value.order.data.pay_amount ? value.order.data.pay_amount : value.order.data.payable_amount;
                }else{
                    return 0;
                }
            },
            status_value:function(value){
                switch(value){
                    case(5):
                        return '待接诊';
                        break;
                    case(10):
                        return '待支付';
                        break;
                    case(15):
                        return '已支付';
                        break;
                    case(20):
                        return '诊疗中';
                        break;
                    case(25):
                        return '诊疗结束';
                        break;
                    case(30):
                        return '医生拒绝接诊';
                        break;
                    case(35):
                        return '诊疗已取消';
                        break;
                    case(38):
                        return '已过期';
                        break;
                }
            },
            time_format:function(value){
                return value.substring(0,16)
            }
        },
        methods:{
            confirm_order(bespeak_id){
                var self = this;
                this.$http.get(this.$store.state.apiUrl+'order/bespeak/'+bespeak_id).then(function (res) {
                    if(res.data.status){
                        if(self.$store.state.tcmuser && true){
                            self.$router.push({path: '/payment',query: { bespeak_id: bespeak_id }});
                        }else{
                            window.location.href='/wechat/payment/?bespeak_id='+bespeak_id;
                        }
                    }else{
                      $api.pop(res.data.msg);
                    }
                });
            },
            payment(bespeak_id) {
                if(this.$store.state.tcmuser && true){
                    this.$router.push({path: '/payment',query: { bespeak_id: bespeak_id }});
                }else{
                    window.location.href='/wechat/payment/?bespeak_id='+bespeak_id;
                }
            },
            find(id){
                this.$router.push({path:'/point',query: { id: id }});
            },
            ask(id,name){
                this.$router.push({path:'/chat',query: { clinicId: id, doctorName:name }});
            },

            loadMore(){

                if(this.statusOrder){

                    this.getList()

                }

            },

            getList(){
                //$('.visible').css('display','none')
                let self = this;
                let oLoadTip = $('.orderBOX').find('.visible');
                this.$http({url:this.$store.state.apiUrl+'bespeak/lists?include=doctor,clinique,order,clinic',method:'GET',params:{page:this.page}}).then(function (res) {
                    res.data.data.list.forEach(e =>{
                      self.lists.push(e);
                    })
                    // setTimeout(function(){

                    //     console.log($('.swiper-slide').height())

                    //     if($('.swiper-container-order').height()>$('.swiper-slide').height()){

                    //         $('.visible').css('display','none')

                    //     }else{

                    //         $('.visible').css('display','block')

                    //     }

                    // },200)

                    self.page ++;
                    if(self.page> res.data.data.totalPage){
                      oLoadTip.html('没有更多数据了');
                      self.statusOrder = false;
                    } else {
                      oLoadTip.show();
                      oLoadTip.html('点击加载');
                    }
                    setTimeout(function(){
                      self.swiper.update();
                    },400);
                });

            },
            list(){
                this.page = this.page-1;
                console.log(this.page);
                this.$http({url:this.$store.state.apiUrl+'bespeak/lists',method:'GET',params:{page:this.page}}).then(function (res) {});
            },
            clinicCancel(order_sn){
                this.$http.get(this.$store.state.apiUrl+'orders/getrefund/'+ order_sn).then(function (res) {
                    if(res.data.status){
                        this.reminds = res.data.msg;
                        $('.layer_pop').removeClass('none');

                    }else{
                        $api.pop(res.data.msg);
                    }
                    this.status = res.data.status;
                });
            },
            delDeal(index){
                var item = this.lists[index];
                $('.layer_pop').removeClass('none');
                this.reminds = '您确定将该预约取消？';
                this.delid = item.id;
                this.indextmp = index;
            },
            canceldel(){
                $('.layer_pop').addClass('none');
            },
            dodel(){
                $('.layer_pop').addClass('none');
                var self = this;
                if(this.delid){
                    self.$http.get(self.$store.state.apiUrl+'bespeak/close/' + this.delid).then(function (res) {
                        $api.pop(res.data.msg);
                        if(res.data.status){
                            this.getList();
                            window.location.reload();
                        }
                    })
                }
            },
            number(id){
                this.$http.get(this.$store.state.apiUrl+'order/'+ id ).then(function (res) {
                    if(res.data.status == 1){
                        this.logistic = res.data.data;
                        $('.pop').fadeIn();
                    }
                });
            },
            again:function(id,type,clinic_money){
                this.$http({url:this.$store.state.apiUrl+'clinic/again',method:'GET',params:{id:id,type:this.again_type}}).then(function (res) {
                    if(res.data.status){
                        this.$router.push({path:'/payment',query: { id: res.data.data.order_sn,type:type,clinic_money:clinic_money}});
                    }else{
                        $api.pop(res.data.msg);
                    }
                });
            },
            det:function(id){
                this.$router.push({path:'/order_details',query: { id: id }});
            },
            bg:function(url){
                if(url) return 'background-image:url('+url+')'
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
            chat(id) {
                this.$http.get(this.$store.state.apiUrl+'message/getMessageListData/bespeak/' + id +'?include=doctor').then(function (res) {
                    if(res.data.status == 1){
                        if(res.data.data.id){
                            this.$router.push({path:'/chat', query: { listId: res.data.data.id, doctorName: res.data.data.doctor.data.name,bespeakId:id}});
                        }
                        $('.pop').fadeIn();
                    }
                });
            },
            bind:function(){
                $('.layer_pop').removeClass("none");
            },
            setMethod:function(id,mid){
                this.type = id;
                this.mid = mid;
                console.log(this.type);
                console.log(this.mid);
            },
            wait:function(){
                this.$router.push({path:'/waitline_record'});
            },
            getMe() {
                this.$http({url:this.$store.state.apiUrl+'user/detail', method:'GET'}).then(function(res){
                    if(res.data.status){
                        window.localStorage.setItem("imToken",res.data.data.im_token);
                        window.localStorage.setItem("headimgurl",res.data.data.headimgurl);
                        window.localStorage.setItem("nickname",res.data.data.nickname);
                        window.localStorage.setItem("id",res.data.data.id);
                    }
                })
            },
        }
    };
</script>
