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
            li(v-for="g in order_goods")
                span {{g.goods_name}}
                .val(v-if="order.pay_type==1") {{g.goods_price}}
                .val(v-else) {{g.member_amount}}

            li.addrsBox(v-if="recipe.is_express==1")
                span.addrTit 收货地址
                .val
                    span {{order.consignee}}  {{order.mobile}}
                    p {{order.province}}{{order.city}}{{order.district}}{{order.address}}
            li(v-if="recipe.is_express==0")
                span 自取
                .val {{order.address}}

    .tips(v-if="recipe.is_price==6")
        .icon-tit
            i
        span 药方已过期
</template>
<script>
    export default {
        data() {
            return{
                recipe:[],
                family:[],
                recipe_head:[],
                order:[],
                order_goods:[],
                recipe_self:[]
            }
        },
        created:function () {
            this.id = this.$route.query.id;
            this.getRecipe();
        },
        methods:{
            getRecipe:function () {
                this.$http({url:this.$store.state.apiUrl+'prescription/detail/'+this.id, method:'GET'}).then(function(res){
                    if(res.data.status){
                        this.recipe = res.data.data;
                        this.recipe_head = res.data.data.recipe_head;
                        this.recipe_self = res.data.data.recipe_self;
                        this.order_goods = res.data.data.order.goods;
                        this.order = res.data.data.order;
                        this.family = res.data.data.family;
                        console.log(this.order_goods);
                    }
                })
            },
            order:function(){
              this.$router.push({path:'/cfpy_order'});
            },
            bg: function (url) {
                if (url) return 'background-image:url(' + url + ')'
            },
        }
    };
</script>
