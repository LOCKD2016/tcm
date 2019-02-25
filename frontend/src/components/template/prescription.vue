<template lang='jade'>
#wrap.cfpy_noprice
    header
        .left(onclick="back()")
            i.icon-arrow-left
        .center 我的药方
        
    .panel
        //- .p
        //-     span ［中医诊断］{{list.disease_zh}}
        .p
            span ［西医诊断］{{list.disease_en}}
        .p
            span ［中医辨证］
            | {{list.disease}}
    .panel.prescription
        .item
            h3 处方
            p
              //span.yaofang(v-for="r in list.recipe") {{r.name}}({{r.dosage}}{{r.unit}})
              .yaofang(v-for="r in list.recipe")
                span {{r.name}}
                span(v-if="order.status==5") ({{r.dosage}}{{r.unit}})
                //span ({{r.dosage}}{{r.unit}})
            .name
              span {{doctor.name}}
        //.item
            h3 计量
            p {{list.recipe}}
        .item
            h3 剂数及用药方法
            p 共{{recipe_head.sum}}剂，一日{{recipe_head.dayNum}}剂，一剂{{recipe_head.takingNum}}次
        .item
            h3 医嘱
            p {{list.recipe_remark}}
        .foot.clearfix
            .left 药费：
            .right ¥ {{list.medicine_price}}
        .foot.clearfix(v-if="list.dispensing_price > 0")
            .left 调剂费：
            .right ¥ {{list.dispensing_price}}

    template
        .panel.list.jyfs#tisane
            .head 煎药方式
            ul.main(v-if="order.status==5")
                li(v-for="(m,ind) in method" v-bind:class="is_tisane == m.id ? 'active':''" )
                    i.icon-check
                    span {{m.name}}
                    .val(v-if="m.id == 1") X {{recipe_head.sum}}
            ul.main(v-else)
                li(v-for="(m,ind) in method" v-bind:class="is_tisane == m.id ? 'active':''"  @click="setMethod(m.id)")
                    i.icon-check
                    span {{m.name}}
                    .val(v-if="m.id == 1") X {{recipe_head.sum}}

            .foot.clearfix.jy_fy(v-if="is_tisane == 1")
                .left 煎药费用：
                .right ¥ {{recipe_head.sum*10}}
            ul.main
                li.jianyaotips(@click="showpdf()") 汤药煎法指南

            //foot.clearfix.jy_zb.none
                .left 自备
                .right
                    .items
                        span.zb(v-for="(m,index) in self" v-on:click="check($event,m,index)") {{m.name}}
        #addressSet.pop
                .box
                    .head.dz
                        span 选择地址
                        a(@click="add()") 添加地址
                    .main
                        ul(v-if="long>0")
                            li(v-for="(a,ind) in addresses" v-bind:class="{'active':address_id == a.id}" @click="setAddr(ind,a.id)")
                                h4 {{a.country}}  {{a.mobile}}
                                p {{a.province}}{{a.city}}{{a.district}}{{a.address}}
                                i.icon-check-c
                        ul(v-else)
                            li
                                h3
                                    span 尚未添加收货地址，
                                    a(@click="add()") 现在添加

                    .foot(onclick="$('#addressSet').fadeOut()") 确定
        .panel.list
            .head#express 取药方式
            ul.main(v-if="order.status==5")
                li(v-for="(e,ind) in express" v-bind:class="is_express == e.id ? 'active':''")
                    i.icon-check
                    span {{e.name}}
            ul.main(v-else)
                li(v-for="(e,ind) in express" v-bind:class="is_express == e.id ? 'active':''" @click="expressMethod(e.id)")
                    i.icon-check
                    span {{e.name}}
                        //.mendian.none
                            p 请选择取药门店：
                                .md
                                    span 泰和国医店
                    //span(v-if="e.id!=0") {{e.name}}(￥{{initiate_price}})

            .foot.clearfix(v-if="is_express == 1" @click="logisticChoose")
                .left 送至：
                .right(@click="addressPop")
                    span(v-if="country") {{country}}{{province}}{{city}}{{district}}{{address}}
                    span(v-else) 添加地址
                    i.icon-arrow-right

            .adresstips(v-if="is_express == 1") 请确认收货地址，支付后地址不能更改！

    .panel.list(v-if='list.is_send == 1 && (is_order == 0 || order.pay_status == 0) && list.is_price==1')

                //.val 余额 ￥0.00
    .panel.order_sta(v-if="order.pay_status>2")
        dl
            dt 订单编号：
            dd {{order.order_sn}}
        dl(v-if="order.pay_status>2")
            dt 支付时间：
            dd {{order.pay_time}}
        dl
            dt 订单状态：
            dd {{list | recipeStatus(list)}}
    .tips(v-if="list.is_price==6")
      .icon-tit
        i
      span  药方订单已过期

    //支付按钮

    .btn.btn-fix.nofixed
        .left(v-if="!order.pay_time")
            span 总计：
            b ￥{{total}}
            .comfirm_order( @click="buy") 确认订单
        .left(v-else)
            span 总计：￥
            .comfirm_order {{order.amount}}

    //- .layer_pop.none
    //-   .content
    //-       .txt 订单确认付款后，将不能进行退款
    //-       .pop_btn.clearfix
    //-           .p_btn.l(@click="dodel()") 确定
    //-           .p_btn(@click="canceldel()") 取消

</template>
<script>

    import {errorMsg} from '../../vuex/store';
    export default {
        data() {
            return{
                vip:localStorage.getItem("vip"),
                is_tisane: 2,
                is_express: 2,
                weight:0,
                long:0,
                tag:0,
                total:0,
                markone:0,
                is_order:0,
                marktwo:0,
                medicine:0,
                country:'',
                province:'',
                city:'',
                district:'',
                address:'',
                initiate_price:0.00,
                before:0,
                address_set:'',
                address_id:0,
                recipe_self_price:0,
                recipe_self:[],
                addresses:[],
                currentaddress:{},     //最后一次添加的地址
                recipe_head:[],
                medicines:[],
                clinique:[],
                goods:[],
                clinique_set:0,
                clinique_id:'北京泰和国医店',
                list:{},
                order: {
                    status:0
                },
                self:[], //自备
                method:[
                    {
                        id:0,
                        name:'自煎',
                        val:''
                    },
                    {
                        id:1,
                        name:'代煎（ ¥ 10/剂 ）',
                        val:7
                    }
                ],
                express:[
                    {
                        id:0,
                        name:'自取'
                    },
                    {
                        id:1,
                        name:'快递(邮费到付)'
                    }
                ],
                user: {},
                doctor: {},
                decoction_fee: 0//代煎费
            }
        },
        created(){
            this.id = this.$route.query.id;
            this.getAdd();
            this.comment = this.$route.query.comment;
            this.getRecipe();

            if(this.$route.query.express){

                var self = this;
                this.$http.get(this.$store.state.apiUrl+'address/lists').then(function (res) {

                    this.currentaddress = res.data.data[res.data.data.length-1];

                        self.address_id = this.currentaddress.id;
                        self.country = this.currentaddress.country;
                        self.province = this.currentaddress.province;
                        self.city = this.currentaddress.city;
                        self.district = this.currentaddress.district;
                        self.address = this.currentaddress.address;

                });

            }

        },
        filters:{

            //0:未划价 1:已划价 2:拒绝开方 3:已支付 4:已发货 5:已经到货 6:药方过期 7退款中 8已退款
            recipeStatus(value){
                switch(value.is_price){
                    case 0:
                        return '未支付';
                        break;
                    case 1:
                        return '未支付';
                        break;
                    case 2:
                        return '无效';
                        break;
                    case 3:
                        return '已支付';
                        break;
                    case 4:
                        return '已发货';
                        break;
                    case 5:
                        return '已经到货';
                        break;
                    case 6:
                        return '药方过期';
                        break;
                    case 7:
                        return '退款中';
                        break;
                    case 8:
                        return '已退款';
                        break;
                }
            }
        },
        methods:{
            getRecipe(){
                this.$http.get(this.$store.state.apiUrl+'prescription/detail/'+ this.id+'?include=user,doctor,order').then( (res)=> {
                  this.getValue(res);

                });
            },
            getValue(res){

              this.list = res.data.data;
              if(res.data.data.recipe_head){
                this.recipe_head = res.data.data.recipe_head;
                if(res.data.data.tisane == 1){
                  this.decoction_fee = this.recipe_head.sum*10;
                }

              }
              if(res.data.data.user){
                this.user = res.data.data.user.data;
                this.doctor = res.data.data.doctor.data;
              }
              if(res.data.data.order){

                this.order = res.data.data.order.data;

                //显示地址

                if(this.order){

                    if(this.list.express == 0){

                      this.is_express = 0;

                    }else{

                      this.is_express = 1;

                    }

                    if(this.list.tisane == 0){

                      this.is_tisane = 0;

                    }else{

                      this.is_tisane = 1;
                    }

                    //判断地址是否存在

                    if(this.order.order_user){

                        this.address_id = this.order.address_id

                        this.country = this.order.order_user.country
                        this.province = this.order.order_user.province
                        this.city = this.order.order_user.city
                        this.district = this.order.order_user.district
                        this.address = this.order.order_user.address

                    }

                }

              }

              if(this.$route.query.express){

                this.is_express = 1;

              }

              if(this.$route.query.tisane){

                this.is_tisane=this.$route.query.tisane

              }

              // this.self = res.data.data.self;
              // this.medicine = res.data.data.recipe_head.sum * 5;

              if(res.data.data.medicine_price){
                if(this.order.pay_time){
                  this.total = (res.data.data.medicine_price + res.data.data.dispensing_price + res.data.data.tisane_price).toFixed(2); //药费+调剂费+代煎费
                }else{
                  this.total = (res.data.data.medicine_price + res.data.data.dispensing_price + this.decoction_fee).toFixed(2); //药费+调剂费+代煎费
                }

              }
            },

            getClinique(){
                this.$http.get(this.$store.state.apiUrl+'clinique/lists').then(function (res) {
                    this.clinique = res.data.data;
                });
            },

            buy(){

                if(this.is_tisane == 2){
                    $api.pop('请选择煎药方式');
                    return false;
                }
                if(this.is_express == 2){
                    $api.pop('请选择快递方式');
                    return false;
                }
                if(this.is_express == 1 && !this.address_id){

                    $api.pop('请选择地址');

                    return false;

                }

                if(this.is_express == 0){

                    this.address_id = 0

                }

                var self = this;
                if(this.tag==0){
                    this.tag = 1;
                    this.$http({url:this.$store.state.apiUrl+'prescription/order/'+this.id, method:'GET',
                      params:{tisane: self.is_tisane,express: self.is_express, address_id: self.address_id}
                    }).then(function (res) {
                        self.tag = 0;
                        if(res.data.status){
                            if(self.$store.state.tcmuser && true){
                                self.$router.push({path: '/payment/recipe',query:{order_id:res.data.data.order_id}});
                            }else{
                                window.location.href = '/wechat/payment/recipe?order_id=' + res.data.data.order_id;
                            }
                        }else{
                            $api.pop(res.data.msg);
                        }
                    },function (response) {
                      errorMsg(response.data.data.errors);
                    });
                }
            },

            getAdd(){
                var self = this;
                this.$http.get(this.$store.state.apiUrl+'address/lists').then(function (res) {
                    this.addresses = res.data.data;
                    this.long = this.addresses.length;
                    this.addresses.forEach(e=>{
                      if(e.is_default == 1){
                        self.address_id = e.id;
                        self.country = e.country;
                        self.province = e.province;
                        self.city = e.city;
                        self.district = e.district;
                        self.address = e.address;
                      }
                    });
                });
            },
            addressPop(){

                if(!this.order.status){

                    this.is_express = 1;

                    this.before = this.initiate_price;

                    $("#addressSet").show();

                }

            },
            // getArea(){
            //     this.$http.get(this.$store.state.apiUrl+'area/detail/'+this.province).then(function (res) {
            //         let area = res.data.data;
            //         let kweight = Math.ceil(this.weight/1000);
            //         if(res.data.status){
            //             if(area.initiate_weight >= kweight){
            //                 this.initiate_price = res.data.data.initiate_price;
            //             }else{
            //                 this.initiate_price = parseInt(res.data.data.initiate_price) + parseInt((kweight-area.initiate_weight) * area.continue_price);
            //             }
            //         }else{
            //             this.initiate_price = 0.00;
            //         }
            //         this.total = (this.total - this.before).toFixed(2);
            //         this.total = (this.total + this.initiate_price).toFixed(2);
            //         this.marktwo = 1;
            //     });
            // },
            comment(id){
                this.$router.push({path: '/tickling',query: { id: id }});
            },
            setClinique(clinique_set,city,store){
                this.clinique_set = clinique_set;
                this.clinique_id = city+store;
                console.log(this.clinique_id)
            },
            add(){
                if(this.user_id==0){
                    this.$router.push({path:'/sign'});
                    return false;
                }else{

                    this.$router.push({path:'/my_address/my/medicine/id'+'?medicineid='+this.id+'&tisane='+this.is_tisane});

                }
            },
            setAddr:function(id,address_id){
                this.before = this.initiate_price;
                this.address_set = id;
                this.address_id = address_id;
                this.country = this.addresses[id].country;
                this.province = this.addresses[id].province;
                this.city = this.addresses[id].city;
                this.district = this.addresses[id].district;
                this.address = this.addresses[id].address;
            },
            setMethod(id){

                this.is_tisane = id;

                if(!this.is_tisane){
                    $('.jy_fy').addClass('none');
                    this.weight = this.list.recipe_head.sum * this.list.recipe_head.sumWeight;
                }else{
                    $('.jy_fy').removeClass('none');
                    this.weight = this.list.recipe_head.sum * this.list.recipe_head.takingNum * 200 ;
                }
                if(this.is_tisane){
                    // if(this.markone == 0){
                    //     this.total = (parseInt(this.total) + parseInt(this.medicine)).toFixed(2);
                    //     this.markone = 1;
                    // }
                    this.decoction_fee = this.recipe_head.sum*10;
                    //$('.jy_zb').addClass('none');
                }else if(!this.is_tisane){
                    // if(this.markone == 1){
                    //     this.total = (parseInt(this.total) - parseInt(this.medicine)).toFixed(2);
                    //     this.markone = 0;
                    // }
                    this.decoction_fee = 0;
                    // if(this.self){
                    //     $('.jy_zb').removeClass('none');
                    // }
                }
                this.total = (this.list.medicine_price + this.list.dispensing_price + this.decoction_fee).toFixed(2);
                console.log(this.weight);

            },
            logisticChoose(){

                if(this.is_tisane == 2){
                    $api.pop('请选择煎药方式');
                    return false;
                }

                if(!this.order.status){

                    $('#addressSet').fadeIn();

                }

            },
            expressMethod(id){
                if(this.is_tisane == 2){
                    $api.pop('请选择煎药方式');
                    return false;
                }
                this.is_express = id;
                if(this.is_express == 1){
                    $('.mendian').addClass('none');
                    if(this.marktwo == 0) {
                        //this.total = (this.total + this.initiate_price).toFixed(2);
                        this.marktwo = 1;
                    }
                }else if(this.is_express == 0){
                    $('.mendian').removeClass('none');
                    if(this.marktwo == 1) {
                        this.total = (this.total - this.initiate_price).toFixed(2);
                        this.marktwo = 0;
                    }
                }
            },
            bg:function(url){
                if(url) return 'background-image:url('+url+')'
            },
            check:function(event,value,index){
                $(event.currentTarget).toggleClass("active");
                if($('.zb').eq(index).hasClass("active")){
                    this.recipe_self.push(value.name);
                    this.total = (this.total - value.price * value.weight).toFixed(2);
                    this.recipe_self_price = this.recipe_self_price + value.price * value.weight;
                }else{
                    this.recipe_self.remove(value.name);
                    this.total = this.total + value.price * value.weight.toFixed(2);
                    this.recipe_self_price = this.recipe_self_price - value.price * value.weight;
                }
                console.log(this.recipe_self);
                console.log(this.recipe_self_price);
            },
            showpdf(){
              this.$router.push({ path:'/pdfone',query:{ total: this.recipe_head.sum,day: this.recipe_head.dayNum,time: this.recipe_head.takingNum}});
            }
        },
    };
    $('.list .main li').click(function(){
        $(this).addClass('active').siblings().removeClass()
    })
</script>
