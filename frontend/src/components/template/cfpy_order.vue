<template lang='jade'>
.fixbody
  #wrap.p_my_order_det.cfpy_order
      header
          .left(onclick="back()")
            i.icon-arrow-left
          .center 确认订单
      ul.list-group.search_his
          li
              span 付款方式
              .val 会员卡123****1234支付
              i.icon-arrow-right

      .panel.user
          i.icon-location
          .phone 13023243432
          h3 权志龙
          p 需要奇才奇葩魂牵梦萦城枯需要工载震荡魂牵梦萦夺
      ul.list-group.search_his.list_mar
          li
              span 快递费：
              .val ¥ 300

      ul.list-group.search_his
          li
              span 代煎（ ¥ 5/剂 ）
              .val X 7
          li
              span 煎药费用
              .val ¥ 300
                 span ¥ 30

          li
              span 药费
              .val ¥ 300
                span ¥ 30
          li
              span 自备
              .val 生姜，大枣

      .btn.btn-fix
          .left
              span 总计：
              b ￥365
          .right.buy(v-on:click="order()") 立即支付
</template>

<script>
    export default {
        data() {
            return{

            }
        },
        created:function () {

        },
        methods:{
            order:function(){
              this.$router.push({path:'/cfpy_myorder'});
            },

        }
    };
</script>
