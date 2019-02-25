<template lang='jade'>
.fixbody
    header
        .left(onclick="back()")
            i.icon-arrow-left
        .center 订单详情

    #wrap
        //.panel.success_box
            .img
            h3 支付成功

        .panel.user(v-if="list.order_type == 0")
            i.icon-location
            .phone {{list.mobile}}
            h3 {{list.consignee}}
            p {{list.province}}{{list.city}}{{list.district}}{{list.address}}

        //商城
        .panel.fer(v-if="list.order_type == 0")
            .doctors.commodity(v-if="list.discount > 0")
                .avatar(v-bind:style="{backgroundImage:'url(' +list.goods[0].share_img+')' }")
                h3 {{list.goods[0].goods_name}}
                .price_a ¥ {{list.money_paid}}
                .price_b ¥ {{list.goods_amount}}
            .doctors.commodity(v-else)
                .avatar(v-bind:style="{backgroundImage:'url(' +list.goods[0].share_img+')' }")
                h3 {{list.goods[0].goods_name}}
                .price_a ¥ {{list.goods_amount}}
            .yhm.clearfix
                .left 优惠码
                .right(v-if="list.discount == 0") 未使用
                .right(v-else) 使用
            .foot.clearfix
                .left 总计：
                .right ¥ {{list.money_paid}}

        .panel.order_sta(v-if="list.order_type == 0")
            dl
                dt 订单编号：
                dd {{list.order_sn}}
            dl
                dt 订单状态：
                //dd(v-if="list.pay_status == '未付款'") 未付款
                //dd(v-if="list.order_status == '已取消'") 已取消
                //dd(v-if="list.pay_status == '已付款'") {{list.pay_status}}
                dd {{list.new_status}}
            dl
                dt 订单类型
                dd {{list.goods_type}}
            dl
                dt 创建时间：
                dd {{list.created_at}}
            dl(v-if="list.pay_status=='已付款'")
                dt 支付时间：
                dd {{list.pay_time}}
            dl(v-if="list.pay_status=='已付款'")
                dt 支付方式：
                dd 微信支付
            dl(v-if="list.shopping_status==1")
                dt 物流单号：
                dd {{list.express_number}}
                .btn_box
                    //.fuz(@click="fz()") 复制
                    .btn.btn-o.btn-jv(v-if="list.shopping_status == 1" @click="find()") 查询

</template>
<script>
    export default{
        data(){
            return {
                list:[],
                goods:[],
                price_a:'',
                price_b:'',
                price:'',
                photoSUrl:'',
                img:'',
                commodity:'三伏贴'
            }
        },
        created(){
            this.id = this.$route.query.id;
            this.detail();
        },
        methods:{
            find(){
                if(this.$store.state.tcmuser && true){
                    var updateLink = "http://www.kuaidi100.com/all/sf.shtml?from=openv";
                    api.openApp({
                        iosUrl : updateLink,
                        androidPkg : 'android.intent.action.VIEW',
                        mimeType : 'text/html',
                        uri : updateLink
                    }, function(ret, err) {

                    });
                }else{
                    window.location.href="http://www.kuaidi100.com/all/sf.shtml?from=openv";
                }
            },
            fz(){
                var Url2=document.getElementById("biao1");
                Url2.select(); // 选择对象
                document.execCommand("Copy"); // 执行浏览器复制命令
                alert("已复制好，可贴粘。");
            },
            detail(){
                this.$http.get(this.$store.state.apiUrl+'order/'+ this.id ).then(function (res) {
                    this.list = res.data.data;
                    this.goods = res.data.data.goods;
                });
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
            }
        }
    };
</script>