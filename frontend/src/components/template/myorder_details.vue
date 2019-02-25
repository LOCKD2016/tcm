<template lang='jade'>
.fixbody
    header
        .left(onclick="back()")
            i.icon-arrow-left
        .center 我的订单
    //.btn.btn-fix(v-if="list.order_type==1 && list.pay_status==2")
        .left(@click="commentClinic(recipe.clinic_id)")
            span 评价
        .right.buy 复诊

    #wrap.order_de
        // 门诊
        .panel.fer(v-if="list.order_type == 1")
            //ul.list-group.search_his
                //- li
                //-     span 预约时间
                //-     .val {{bespeak.start_time}}
            .doctors.commodity
                .avatar(v-bind:style="bg(doctor.photoSUrl)")
                h3 {{doctor.name}}
                p {{doctor.titleName}}
                //.price_c(v-if="list.order_type==2") 诊费：¥ {{doctor.web_amount}}
                //.price_a 诊费：¥ {{doctor.clinic_money}}
            .contact.clearfix
                .left 就诊日期：
                .right {{bespeak.start_time}}
            .contact.clearfix
                .left 患者姓名：
                .right {{ userInfo.realname }}
            .contact.clearfix
                .left 身份证：
                .right {{ userInfo.idNo }}
            .contact.clearfix
                .left 医事服务费：
                .right(v-if="list.status >= 5") ¥ {{list.amount}}
            .contact.clearfix
                .left 取号地点：
                .right {{ clinique.content.address }}
            .contact.clearfix
                .left 联系方式：
                .right {{ clinique.telephone }}
            .clinicTips 您的预约时间为：{{ bespeak.start_time }}，请您携带有效身份证原件准时就诊，过时预约号作废。
            //- .contact.clearfix
            //-     .left 取号时间：
            //-     .right 
            .addr
                i.icon-location
                h3 {{clinique.name}}
                    a(href='//m.amap.com/navi/?start=&dest=116.451507,39.937215&destName=%E6%B3%B0%E5%92%8C%E5%9B%BD%E5%8C%BB&key=f429bc8bfc2f54d86dc399ea513ca3d5') 进入导航
                p {{clinique.content.address}}
            .mapBox
                img(src='../../../static/img/map1.png')
                //- iframe(frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='//m.amap.com/navi/?dest=116.451122,39.93728&destName=北京市朝阳区新东路12号院 首开铂郡南区&hideRouteIcon=1&key=1d9644db347ce2e1d157e19ed878932e')
                //- iframe(frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='http://j.map.baidu.com/WMAuP')
                //- iframe(frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='//f.amap.com/3x1vK_03849LM')
        .panel.order_sta(v-if="list.order_type == 2")
            dl
                dt 订单编号：
                dd {{list.order_sn}}
            dl
                dt 支付状态：
                dd {{list.status | returnStatus}}
            dl(v-if="list.status >= 5")
                dt 支付方式：
                dd 微信
            dl(v-if="list.status >= 5")
                dt 支付时间：
                dd {{list.pay_time}}
            .foot.clearfix
              .left 总计：
              .right ¥ {{list.amount}}
        //药方
        .panel.fer.user(v-if="list.order_type == 3")
             h5.hea(v-if='list.prescription' @click="toRecipe(list.prescription.id)")
                //- span {{list.prescription.disease}}
                span 处方信息
                span.icon-arrow-right
             .foot.clearfix(v-if="list.amount > 0")
                  .left 总计：
                  .right ¥ {{list.amount}}
        .panel.order_sta(v-if="list.order_type ==3")

            dl
                dt 订单编号：
                dd {{list.order_sn}}
            dl(v-if="list.pay_time")
                dt 支付时间：
                dd {{list.pay_time}}
            dl(v-else)
                dt 订单状态：
                dd 未付款
            dl(v-if="list.pay_time")
                dt 支付方式：
                dd 微信支付
            .getmedicine(v-if="list.pay_time && list.prescription.express==0")
                dl
                    dt 取药时间：
                    dd 24h-48h之内到门诊取药
                dl
                    dt 门诊电话：
                    dd {{ clinique.telephone }}
                dl
                    dt 取药地点：
                    dd
                    .addr
                        i.icon-location
                        h3 {{clinique.name}}
                          a(@click="toMap") 进入导航
                        p {{clinique.content.address}}        


            //- dl
            //-     dt 订单编号：
            //-     dd {{list.order_sn}}
            //- dl(v-if="list.pay_time")
            //-     dt 支付时间：
            //-     dd {{list.pay_time}}
            //- dl(v-else)
            //-     dt 订单状态：
            //-     dd 未付款
            //- dl(v-if="list.pay_time")
            //-     dt 支付方式：
            //-     dd 微信支付
            //- dl(v-if="list.pay_time && list.prescription.express==0")
            //-     dd 取药时间：24h-48h之内到门诊取药
            //-     dd 门诊电话：{{ clinique.telephone }}
            //-     dd 取药地点：
            //-     .addr
            //-         i.icon-location
            //-         h3 {{clinique.name}}
            //-           a(href='//m.amap.com/navi/?start=&dest=116.451507,39.937215&destName=%E6%B3%B0%E5%92%8C%E5%9B%BD%E5%8C%BB&key=f429bc8bfc2f54d86dc399ea513ca3d5') 进入导航
            //-         p {{clinique.content.address}}

            .expressinfo(v-if="list.pay_time && list.prescription.express==1")
                dl
                    dt 门诊电话：
                    dd {{ clinique.telephone }}
                dl(v-if="!list.express.express_number")
                    dt 发货时间：
                    dd 24h-48h之内发货
                dl(v-if="list.order_user")
                    dt 收货地址：
                    dd {{list.order_user.country}} {{list.order_user.province}} {{list.order_user.city}} {{list.order_user.district}} {{list.order_user.address}} 
                dl(v-if="list.express.express_number")
                    dt 快递单号：
                    dd {{list.express.express_number}}

</template>
<script>
    export default{
        data(){
            return {
                list:[],
                bespeak:[],
                doctor:[],
                clinique:[],
                recipe_order:[],
                comment:[],
                subscribe:[],
                price_a:'',
                price_b:'',
                price:'',
                photoSUrl:'',
                img:'',
                userInfo:null    //患者信息
            }
        },
        created(){
            this.id = this.$route.query.id;
            this.detail();

            //获取患者信息

            this.$http.get(this.$store.state.apiUrl+'user/detail')

                .then(function(res){

                    if(res&&res.status){

                        this.userInfo = res.data.data

                    }

                })
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
            },
            payType(value){
                switch(value){
                    case(0):
                        return '未支付';
                        break;
                    case(1):
                        return '微信支付';
                        break;
                    case(5):
                        return '免费订单';
                        break;
                }
            }
        },
        ready(){

        },
        methods:{
            toMap(){
                var updateLink = 'https://m.amap.com/navi/?start=&dest=116.451507,39.937215&destName=%E6%B3%B0%E5%92%8C%E5%9B%BD%E5%8C%BB&key=f429bc8bfc2f54d86dc399ea513ca3d5';
                if(this.$store.state.tcmuser && true){

                    api.openApp({
                        iosUrl : updateLink,
                        androidPkg : 'android.intent.action.VIEW',
                        mimeType : 'text/html',
                        uri : updateLink
                    }, function(ret, err) {

                    });
                }else{
                    window.location.href=updateLink;
                }
            },
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
                this.$http.get(this.$store.state.apiUrl+'order/detail/'+ this.id + '?include=bespeak.doctor,bespeak.clinique,goods').then(function (res) {

                    this.list = res.data.data;

                    if(this.list.bespeak){

                        this.bespeak = this.list.bespeak.data;

                        this.doctor = this.list.bespeak.data.doctor.data;

                    }                    
                    
                    if(res.data.data.clinique){

                      this.clinique = res.data.data.clinique;

                    }

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
            },
            commentClinic(id){
                this.$router.push({path: '/tickling',query: { id: id }});
            },
            toRecipe(id){
                this.$router.push({path: '/prescription/my/id',query: { id: id }});
            }
        }
    };
</script>
