<template lang='jade'>
#wrap.cfpy_noprice
    header
        .left(onclick="back()")
            i.icon-arrow-left
        .center 传方抓药
    ul.list-group.search_his
        li
            span 就诊人
            .val {{family.name}}
        li
            span 手机号
            .val {{family.mobile}}
    .panel.list.cf.cfsc
        .head  处方
        .img(v-bind:style="bg(recipe.recipe_photo)")
        ul.list-group.search_his
            li
                span 中药剂型
                .val
                  input(type="text" placeholder="请选择中药剂型" v-model="recipe.medicinal_type_name" readonly)
            li
                span 付数
                .val
                  input(type="text" placeholder="请填写中药付数" v-model="recipe_head.sum" readonly)
                span.fu 付
    ul.list-group.search_his
        li
            span 煎药方式
            .val(v-if="recipe.is_tisane") 代煎
            .val(v-else) 自煎
        li(v-if="recipe.is_tisane")
      li.zb(v-if="recipe_self > 0")
            span 煎药费用
            .val ¥ {{recipe_head.sum * 5}}.00

            span 自备
            .val {{recipe.recipe_self[0]}} {{recipe.recipe_self[1]}}

        li.zb(v-if="recipe_self > 0")
            span 自备费用
            .val {{recipe.recipe_self_price}}
        li
            span 调剂费
            .val ¥ {{recipe.dispensing_price}}
        li
            span 药费
            .val ¥ {{recipe.medicine_price}}
        //如果生成订单
        template(v-if="order.order_sn")
            li
                span 取药方式
                .val(v-if="recipe.is_express==0") 自取
                .val(v-else) 快递

            li(v-if="recipe.is_express==1")
                span 快递费用
                .val ¥ {{order.goods[3].goods_price}}

            li.addrsBox(v-if="recipe.is_express==1")
                span.addrTit 收货地址
                .val
                    span {{order.consignee}}  {{order.mobile}}
                    p {{order.province}}{{order.city}}{{order.district}}{{order.address}}


    .panel.list.jyfs.none
        .head 取药方式
        ul.main
            li(v-on:click="jysf($event,0)")
                i.icon-check
                span 自取
                .mendian.none
                  p 请选择取药门店：
                  .md
                    span(v-for="(c,ind) in clinique" v-bind:class="clinique_set == ind ? 'active':''" @click="setClinique(ind,c.city,c.store)") {{c.city}}{{c.store}}
            li.zj(v-on:click="jysf($event,1)")
                i.icon-check
                span 快递（￥{{initiate_price}}）
                .val.addr(v-if="province" v-on:click="addressPop()") {{consignee}}{{province}}{{city}}{{district}}{{address}}
                .val.tjdz(v-else v-on:click="addressPop()") 添加地址 ＞

    .panel.list.jyfs
        .head   支付方式
        ul.main
            li(v-for="(p,ind) in pay" v-bind:class="pay_method == ind ? 'active':''" @click="payMethod(ind,p.id)")
                i.icon-check
                span {{p.name}}

    .btn.btn-fix
        .left
            span 总计：
            b(v-if="order.order_sn") ￥{{order.money_paid}}
            b(v-else) ￥{{total}}
        .right.buy(v-if="!order.order_sn" @click="buy()") 立即支付
        .right.buy(v-else @click="payOrder()") 立即支付

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
                           a(v-if="long==0" @click="add()") 现在添加
                   li(v-else v-for="(a,ind) in addresses" v-bind:class="address_set == ind ? 'active':''" @click="setAddr(ind,a.id)")
                       h4 {{a.consignee}}  {{a.mobile}}
                       p {{a.province}}{{a.city}}{{a.district}}{{a.address}}
                       i.icon-check-c

           .foot(onclick="$('#addressSet').fadeOut()") 确定

</template>
<script>
    export default {
        data() {
            return{
                recipe:[],
                family:[],
                addresses:[],
                clinique:[],
                recipe_head:[],
                order:[],
                recipe_self:0,
                long:0,
                weight:0,
                clinique_set:0,
                pay_method:3,
                total:0,
                mark:0,
                is_express:2,
                before:0,
                express_set:0,
                clinique_id:'北京望京店',
                address_set:100,
                address_id:0,
                initiate_price:0.00,
                pay_type:2,
                consignee:'',
                province:'',
                city:'',
                district:'',
                address:'',
                pay:[
                    {
                        id:2,
                        name:'微信'
                    },
                    {
                        id:1,
                        name:'会员卡'
                    }

                ]
            }
        },
        created:function () {
            this.id = this.$route.query.id;
            this.getRecipe();
            this.getAdd();
            this.getClinique();
        },
        methods:{
            getRecipe:function () {
                this.$http({url:this.$store.state.apiUrl+'prescription/detail/'+this.id, method:'GET'}).then(function(res){
                    if(res.data.status){
                        this.recipe = res.data.data;
                        this.recipe_self = res.data.data.recipe_self.length;
                        this.recipe_head = res.data.data.recipe_head;
                        this.family = res.data.data.family;
                        this.order = res.data.data.order;
                        if(res.data.data.is_tisane){
                            this.total = (parseInt(res.data.data.medicine_price) + parseInt(res.data.data.dispensing_price) + parseInt(res.data.data.recipe_head.sum * 5) - parseInt(res.data.data.recipe_self_price)).toFixed(2);
                        }else{
                            this.total = (parseInt(res.data.data.medicine_price) + parseInt(res.data.data.dispensing_price) - parseInt(res.data.data.recipe_self_price)).toFixed(2);
                        }
                        if(!this.order.order_sn){
                            $('.jyfs').removeClass('none');
                        }
                    }
                })
            },
            getAdd(){
                this.$http.get(this.$store.state.apiUrl+'address').then(function (res) {
                    this.addresses = res.data.data;
                    this.long = this.addresses.length;
                });
            },
            getClinique(){
                this.$http.get(this.$store.state.apiUrl+'clinique/lists').then(function (res) {
                    this.clinique = res.data.data;
                });
            },
            getArea(){
                this.$http.get(this.$store.state.apiUrl+'area/detail/'+this.province).then(function (res) {
                    let area = res.data.data;
                    let kweight = Math.ceil(this.weight/1000);
                    if(res.data.status){
                        if(area.initiate_weight >= kweight){
                            this.initiate_price = res.data.data.initiate_price;
                        }else{
                            this.initiate_price = parseInt(res.data.data.initiate_price) + parseInt((kweight-area.initiate_weight) * area.continue_price);
                        }
                    }else{
                        this.initiate_price = 0.00;
                    }
                    this.total = (parseInt(this.total) - parseInt(this.before)).toFixed(2);
                    this.total = (parseInt(this.total) + parseInt(this.initiate_price)).toFixed(2);
                    this.mark = 1;
                });
            },
            payOrder(){
                if(this.pay_method==3){
                    $api.pop('请选择支付方式');
                    return false;
                }
                if(window.localStorage.getItem('vip')==0 && this.pay_method==1){
                    $api.pop('请先绑定会员卡');
                    return false;
                }
                this.$router.push({path: '/payment/recipe',query:{id:this.id,type:this.pay_type}});
            },
            buy(){
                if(this.is_express==2){
                    $api.pop('请选择取药方式');
                    return false;
                }
                if(this.is_express==1 && !this.address_id){
                    $api.pop('请选择地址');
                    return false;
                }
                if(this.pay_method==3){
                    $api.pop('请选择支付方式');
                    return false;
                }
                if(window.localStorage.getItem('vip')==0 && this.pay_method==1){
                    $api.pop('请先绑定会员卡');
                    return false;
                }
                var data = {};
                data.recipe_id = this.id;
                data.is_express = this.is_express;
                data.express_id = this.address_id;
                data.express_money = this.initiate_price;
                data.clinique = this.clinique_id;
                data.num = this.recipe_head.sum;
                data.tisane = this.recipe.is_tisane;
                data.express_money = this.initiate_price;
                this.$http.post(this.$store.state.apiUrl+'orders/recipe', data).then(function (res) {
                    if(res.data.status){
                        this.$router.push({path: '/payment/recipe',query:{id:this.id,type:this.pay_type}});
                    }else{
                        $api.pop(res.data.msg);
                    }
                });

            },
            jysf:function(event,is_express){
                $(event.currentTarget).addClass("active").siblings().removeClass("active");
                if($('.zj').hasClass("active")){
                    $('.mendian').addClass('none');
                    $('.zb').removeClass('none');
                }else{
                    $('.mendian').removeClass('none');
                    $('.zb').addClass('none');
                }
                if(is_express == 1) {
                    if (this.mark == 0) {
                        this.total = (parseInt(this.total) + parseInt(this.initiate_price)).toFixed(2);
                        this.mark = 1;
                    }
                }
                if(is_express == 0){
                    if(this.mark == 1) {
                        console.log(this.initiate_price);
                        this.total = (parseInt(this.total) - parseInt(this.initiate_price)).toFixed(2);
                        this.mark = 0;
                    }
                }
                if(this.is_tisane == 0){
                    this.weight = this.recipe_head.sum * this.recipe_head.sumWeight;
                }else{
                    this.weight = this.recipe_head.sum * this.recipe_head.takingNum * 200 ;
                }
                this.is_express = is_express;
            },
            addressPop:function(){
                $("#addressSet").show();
                this.before = this.initiate_price;
                console.log(this.before)
            },
            bg: function (url) {
                if (url) return 'background-image:url(' + url + ')'
            },
            setClinique(clinique_set,city,store){
                this.clinique_set = clinique_set;
                this.clinique_id = city+store;
                console.log(this.clinique_id)
            },
            add(){
                this.$router.push({path:'/my_address/my'});
            },
            setAddr:function(id,address_id){
                this.before = this.initiate_price;
                this.address_set = id;
                this.address_id = address_id;
                this.consignee = this.addresses[id].consignee;
                this.province = this.addresses[id].province;
                this.city = this.addresses[id].city;
                this.district = this.addresses[id].district;
                this.address = this.addresses[id].address;
            },
            payMethod(ind, id){
                this.pay_method = ind;
                this.pay_type = id;
                console.log(this.pay_type)
            }
        },
        watch:{
            province(){
                this.getArea();
            }
        }
    };
</script>
