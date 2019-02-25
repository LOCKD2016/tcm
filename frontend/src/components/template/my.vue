<template lang='jade'>
.fixbody
    header
        .left(v-if="!$store.state.tcmuser" onclick="back()")
            i.icon-arrow-left
        .center 个人中心
    #wrap
        .banner
            .avatar(v-bind:style="{backgroundImage:'url(' +info.headimgurl+')' }" @click="addInfo()")
            .nickname {{info.nickname}}
            //.vip
                i.icon-vip
                span(v-if="info.vip == 1" @click="balance()") 家庭卡
                span(v-if="info.vip == 2" @click="balance()") VIP卡
                span(v-if="info.vip == 0" @click="card()") 去绑定
                i.icon-arrow-right
        //ul.nav.nav-tabs
            li
                h4 {{info.com_num}}
                p 待评价
            li(v-if="info.vip" @click="balance()")
                h4 {{info.card.amount}}
                p 卡余额
            li
                h4 {{info.score}}
                p 积分
        //.tips
          .icon-tit
            i
          span {{msg}}

        //-var mnv=['我的订单','就诊人','收货地址管理']
        //-var mlk=['order','myfml','address']
        -var mnv=['我的订单','待评价','收货地址管理','门诊联系方式']
        -var mlk=['order','clinic_list','address','contact']
        ul.list-group
            each vd , i in mnv
                router-link.changemy(to="/my_#{mlk[i]}/my" tag="li")
                    a=vd
                    //span.num 3
                    i.icon-arrow-right
            li.changemy(v-if="$store.state.tcmuser" @click="logout")  退出

</template>
<script>
    export default{
        data(){
            return{
                msg: '请您务必于下单后三日内，完整填写问诊单',
                id: 1,
                vip:0,
                info: {}
            }
        },
        created(){
            this.$store.commit("toggleHeaderStatus", false);
            this.getMe();
        },
        methods:{
            getMe(){
                this.$http.get(this.$store.state.apiUrl+'user/detail').then(function (res) {
                    this.info = res.data.data;
                });
            },
            card:function(){
                this.$router.push({path:'/my_card'});
            },
            balance(){
                this.$router.push({path:'/my_card_balance'});
            },
            addInfo(){
                this.$router.push({path:'/my_fmld/my'});
            },
            logout(){
                this.$http.get(this.$store.state.apiUrl+'logout').then(function (res){
                    //api.sendEvent({name:'background',extra: {
                    //    status:'logout'
                    //}});
                    this.$router.replace('/index');
                })
            }
        }
    };
</script>
