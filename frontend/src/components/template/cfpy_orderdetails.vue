<template lang='jade'>
.fixbody
  #wrap.cfpy_noprice.p_myorder_details
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
      .panel.list.cf.cfsc.fer.user
          .head  处方
          .img(v-bind:style="bg(recipe_photo)")
          ul.list-group.search_his(v-for="g in goods")
              li(v-if="g.goods_id==5")
                  span 处方
                  .val(v-if="list.pay_type==1") {{g.member_amount}}
                  .val(v-else) {{g.goods_price}}

              li(v-else)
                  span {{g.goods_name}}
                  .val(v-if="list.pay_type==1") {{g.member_amount}}
                  .val(v-else) {{g.goods_price}}
          ul.list-group.search_his(v-if="recipe.is_express==0")
              li
                  span 自取
                  .val {{list.address}}

          .dz(v-if="recipe.is_express==1")
              i.icon-location
              .phone {{list.mobile}}
              h3 {{list.consignee}}
              p {{list.province}}{{list.city}}{{list.district}}{{list.address}}


          .foot.clearfix
              .left 总计：
              template(v-if="list.pay_status==0")
                  .right ¥ {{list.money_paid}}
              template(v-if="list.pay_status>=2")
                  .right(v-if="list.pay_type==1") ¥ {{list.member_amount}}
                  .right(v-else) ¥ {{list.goods_amount}}
      .panel.order_sta
          dl
              dt 订单编号：
              dd {{list.order_sn}}
          dl(v-if="list.pay_time")
              dt 支付时间：
              dd {{list.pay_time}}
          dl
              dt 支付方式：
              dd(v-if="list.pay_type==0") 未支付
              dd(v-if="list.pay_type==1") 会员卡支付
              dd(v-if="list.pay_type==2") 微信支付

</template>
<script>
    export default{
        data(){
            return {
                list:[],
                goods:[],
                family:[],
                recipe:[],
                recipe_photo:''
            }
        },
        created(){
            this.id = this.$route.query.id;
            this.detail();
        },
        ready(){
        },
        methods:{
            detail(){
                this.$http.get(this.$store.state.apiUrl+'orders/detail/'+ this.id ).then(function (res) {
                    this.list = res.data.data;
                    this.goods = res.data.data.goods;
                    this.family = res.data.data.family;
                    this.recipe = res.data.data.recipe;
                    this.recipe_photo = JSON.parse(res.data.data.goods[0].goods_attr).recipe_photo;
                });
            },
            bg:function(url){
                if(url) return 'background-image:url('+url+')'
            },
        }
    };
</script>

