<template lang='jade'>
.v
    .top
        header.tm
            router-link.left(to="/" tag="a")
                i.icon-arrow-left
            .center
        #addressSet.pop
            .box
                .head.dz
                    span 选择地址
                    a(v-if="long>0" @click="add()") 地址管理
                .main
                    ul
                        li(v-if="long<1")
                            h3
                                span 尚未添加收货地址，
                                a(@click="add()") 现在添加
                        li(v-else v-for="(a,ind) in address" v-bind:class="address_set == ind ? 'active':''" @click="setAddr(ind,a.id)")
                            h4 {{a.consignee}}  {{a.mobile}}
                            p {{a.province}}{{a.city}}{{a.district}}{{a.address}}
                            i.icon-check-c
                .foot(onclick="$('#addressSet').fadeOut()") 确定

        .btn.btn-fix(@click="submit()")
            .left
                span.he ￥
                b(v-html="priceAll()")
            //.right(v-bind:class="{buy:$store.state.wxready}" @click="refresh()") 系统维护中...
            //.right(v-else @click="buy()",v-bind:class="{buy:$store.state.wxready}") 预售
            .right(v-if='hours <= 0 && minutes <= 0 && seconds <= 0' v-bind:class="{buy:$store.state.wxready}") 预售已结束
            .right(v-else @click="buy()",v-bind:class="{buy:$store.state.wxready}") 预售
    #wrap
        .banner(v-bind:style="bg(img)")
            //h3 {{title}}
            .miaosha.clearfix
              .left
                P.price {{parseInt(price)}}
                p 今日秒杀
                  span.ms ￥500.00
              .right
                p.ss 距结束还剩：
                p.time
                  span.hours
                  |:
                  span.minutes
                  |:
                  span.seconds
        .abstract
            <!--pre {{abstract}}-->
            h3 {{title}}
            pre.zfwc 6月26日——6月29日 420元
            pre.zfwc 6月30日——7月2日 460元
            pre.zfwc 7月3日——7月5日 500元
            pre 支付成功后，请到个人中心——我的订单——填写问诊单（请您尽快完整提交问诊单，加微信18810607960进三伏贴群，帮您解答疑问。）
            pre 如之前已购买2017至阳三伏贴，无需购买本次个性化传统三伏贴。
        .abstract
            pre.zfwc 第1次贴   7月12日    头伏第1贴
            pre.zfwc 第2次贴   7月16日    头伏加强贴
            pre.zfwc 第3次贴   7月22日    中伏第1贴
            pre.zfwc 第4次贴   7月26日    中伏加强贴
            pre.zfwc 第5次贴   8月1日     中伏第3贴
            pre.zfwc 第6次贴   8月11日    末伏第1贴
        ul.list-group
            li
                span 就诊人 身份
                //-var vn = ['成人男','成人女','儿童']
                //ul.switch(v-bind:show="type")
                    //each vc,i in vn
                        //li(v-bind:id='i' @click="changeTyp(#{i})")=vc
                ul.switch
                    li(id='aaa' @click="changeTyp(1)") 成人男
                    li(id='bbb' @click="changeTyp(2)") 成人女
                    li(id='ccc' @click="changeTyp(3)") 儿童
        .panel
            .head 贴敷类别说明
                ul.sx_tip(style="font-size: .2rem;")
                    li(style="padding: 0;") 2-12周岁人士贴敷，请选择——儿童。
                    li(style="padding: 0;") 12周岁以上男士贴敷，请选择——成人男。
                    li(style="padding: 0;") 12周岁以上女士或者12周岁以下但已来月经女士贴敷，请选择——成人女。
        .panel
            .head 禁忌人群说明
                ul.sx_tip(style="font-size: .2rem;")
                    li(style="padding: 0;") 心脏病、肾病、肝脏疾病者
                    li(style="padding: 0;") 肺结核、支气管扩张患者
                    li(style="padding: 0;") 孕妇
                    li(style="padding: 0;") 有严重药敏史
                    li(style="padding: 0;") 瘢痕体质或穴位处有皮肤溃损
                    li(style="padding: 0;") 对皮肤暂时留下痕迹、起泡很介意者
        //.pop
            .box
                .head
                    i.icon-warning
                    span 敬请期待
                .main
                    ul
                        li(style="text-align:center;") 个性化传统三伏贴6月26日开始预售
                .foot(onclick="$('.pop_over').fadeOut()") 我知道了

        ul.list-group
            //li
                //span 优惠码
                //.val
                    //input#yhm(v-model="code" type="text" maxlength="20" placeholder="输入优惠码" v-bind:readonly="discount==1")

            li(onclick="$('#addressSet').fadeIn()")
                span 送至
                .val(v-html="vAddr()")

                i.icon-arrow-right
            li
                span 顺丰包邮
        //.panel
            //.head 订单备注
              //ul.sx_tip
                  //li 支付成功后，请到个人中心——我的订单——填写问诊单。（请您务必于下单后三日内,完整填写问诊单。以确保您于6月26日前收到三伏贴产品套装。）
</template>
<script>
    export default {
        data(){
            return {
                whom:[],
                //code:'',
                address_id:'',
                consignee:'',
                mobile:'',
                province:'',
                city:'',
                district:'',
                postscript:'',
                long:0,
                goods_id: 0,
                title:'三伏贴',
                abstract:'“个性化三伏贴”药物和取穴所激发的人体自我调节能力，加上天地间充盛的阳气，可以使很多以“寒、湿、虚”为主要病因的疾病得到痊愈。',
                price: 0,
                price_all: 0,
                img:'',
                discount:'',
                type:0,
                address_set:0,
                address:[],
                is_default:0,
                tag:0,
                user_id: 0,
                hours:0,
                minutes:0,
                seconds:0
            };
        },
        created(){
            var self = this;
            this.getAdd();
            var id = this.$route.params.id;
            this.goods_id = id;
            this.getGood(id);
            var starttime = new Date("2017/7/6");
            setInterval(function () {
                var nowtime = new Date();
                var time = starttime - nowtime;
                var day = parseInt(time / 1000 / 60 / 60 / 24);
                var hours = day*24;
                var hour = parseInt(time / 1000 / 60 / 60 % 24) + hours;
                var minute = parseInt(time / 1000 / 60 % 60);
                var seconds = parseInt(time / 1000 % 60);
                if(hour<=0){
                    hour = 0;
                }
                if(minute<=0){
                    minute = 0;
                }
                if(seconds<=0){
                    seconds = 0;
                }
                $('.hours').html(hour);
                $('.minutes').html(minute);
                $('.seconds').html(seconds);
                self.hours = hour;
                self.minutes = minute;
                self.seconds = seconds;
                //$('.timespan').html(day + "天" + hour + "小时" + minute + "分钟" + seconds + "秒");
            }, 1000);
        },
        methods:{
            bg:function(url){
                if(url) return 'background-image:url('+url+')'
            },
            getGood(id){
                this.$http.get(this.$store.state.apiUrl+'good/'+4).then(function (res) {
                    if(res.data.status){
                        var good = res.data.data;
                        this.title = good.goods_name;
                        this.abstract = good.goods_des;
                        this.price = good.goods_price;
                        this.price_all = good.goods_price;
                        this.img = good.img;
                        this.user_id = good.user_id;
                    }
                });
            },
            buy(){
                if(this.user_id==0){
                    this.$router.push({path:'/sign'});
                    return false;
                }

                if(this.$store.state.tcmuser){
                    $api.pop('微信接口在app上待开发，稍等');
                    return;
                }
                if(!this.$store.state.wxready){
                    $api.pop('微信API准备未就绪，请稍后再试');
                    return false;
                }
                if(!this.type){
                    $api.pop('未选择贴敷人身份');
                    return false;
                }
                if(!this.address_id){
                    $api.pop('请选择地址');
                    return false;
                }
                var self = this;
                if(this.tag==0){
                    var data = {};
                    data.goods_id = this.goods_id;
                    data.goods_type = this.type;
                    data.address_id = this.address_id;
                    //data.pay_total = this.price - this.discount;
                    //data.discount_total = this.discount;
                    //data.promocode = this.code;
                    data.postscript = this.postscript;
                    this.tag = 1;
                    if(this.$store.state.tcmuser){
                        $api.pop('app支付待开发，稍等');
                        return;
                    }
                    $('.buy').html('订单处理中...');
                    this.$http.post(this.$store.state.apiUrl+'order/create', data).then(function (res) {
                        if(res.data.status>0){
                            wx.chooseWXPay({
                                timestamp: res.data.data.timestamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
                                nonceStr: res.data.data.nonceStr, // 支付签名随机串，不长于 32 位
                                package: res.data.data.package, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
                                signType: res.data.data.signType, // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
                                paySign: res.data.data.paySign, // 支付签名
                                success: function (res) {
                                    // 支付成功后的回调函数
                                    self.$router.push({path: '/my_order/my'});
                                }
                            });
                            setTimeout(function(){ self.tag = 0;},200);
                            $('.buy').html('预售');
                        }else{
                            $api.pop(res.data.msg);
                            self.tag = 0;
                            $('.buy').html('预售');
                        }
                    });
                }
            },
            getAdd(){
                this.$http.get(this.$store.state.apiUrl+'address').then(function (res) {
                    this.address = res.data.data;
                    this.long = this.address.length;
                });
            },
            vAddr(){
                if(this.address.length ==0){
                    return '请选择地址'
                }else{
                    for(var xx = 0; xx<this.address.length;xx++){
                        if(this.address[xx].type == 1 && this.address_set == 0){
                            this.address_id = this.address[xx].id;
                            return this.address[xx].consignee+' '+this.address[xx].province+this.address[xx].city+this.address[xx].district+this.address[xx].address
                        }else{
                            this.address_id = this.address[this.address_set].id;
                            return this.address[this.address_set].consignee+' '+this.address[this.address_set].province+this.address[this.address_set].city+this.address[this.address_set].district+this.address[this.address_set].address
                        }
                    }
                }
            },
            priceAll(){
                if(this.discount==''){
                    return this.price
                }else{
                    return (this.price - this.discount).toFixed(2)*1
                }
            },
            setAddr:function(id,address_id){
                this.address_set = id;
                this.address_id = address_id;
            },
            add(){
                if(this.user_id==0){
                    this.$router.push({path:'/sign'});
                    return false;
                }else{
                    this.$router.push({path:'/my_address/my'});
                }
            },
            submit:function(){
                //提交成功后跳转
                //location.href= 'my_order_det.html?id=1'
            },
            changeTyp(i){
                this.type=i;
                if(i==1){
                    $('#aaa').addClass('ptype');
                    $('#bbb').removeClass('ptype');
                    $('#ccc').removeClass('ptype');
                }else if(i==2){
                    $('#aaa').removeClass('ptype');
                    $('#bbb').addClass('ptype');
                    $('#ccc').removeClass('ptype');
                }else{
                    $('#aaa').removeClass('ptype');
                    $('#bbb').removeClass('ptype');
                    $('#ccc').addClass('ptype');
                }

            },
            refresh(){
                setTimeout(function(){
                    location.reload();
                },200);
            }
        }
    };



</script>
