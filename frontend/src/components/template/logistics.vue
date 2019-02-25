<template lang='jade'>
  .fixbody
    header
      .left(onclick="back()")
        i.icon-arrow-left
      .center 物流信息

    #wrap
      .logistics.clearfix
          .left
              img(src="/img/banner_gst.jpg")
          .left.del
              dl.stauts
                  dt 物流状态：
                  dd {{order | orderInfo(order)}}
              dl
                  dt 承运来源：
                  dd 顺丰快递
              dl
                  dt 运单编号：
                  dd {{order.express_number}}
              dl
                  dt 官方电话：
                  dd 15456
      .logistics_del(v-if="resultcode == 200")
            //dl.qianshou
                dt 已签收，签收人是本人
                dd 2017-07-28 15:26
            dl(v-for="lt in list")
                dt {{lt.remark}}
                    //span 18800000000
                dd {{lt.datetime}}
      .logistics(v-else)
            span.notfind 查不到物流信息

</template>
<script>
    export default{
        data(){
            return{
                id:0,
                resultcode:0,
                exp: [],
                list: [],
                order: []
            }
        },
        created(){
            this.id = this.$route.query.id;
            this.getExp();
        },
        filters:{
            //商品配送情况;0未发货,1已发货,2已收货,4退货
            orderInfo(value){
                switch(value.shipping_status){
                    case 0:
                        return '未发货';
                        break;
                    case 1:
                        return '运输中';
                        break;
                    case 2:
                        return '已签收';
                        break;
                    case 4:
                        return '退货';
                        break;
                }

            }
        },
        methods:{
            getExp(){
                this.$http.get(this.$store.state.apiUrl+'orders/exp/'+this.id).then(function (res) {
                    if(res.data.status){
                        this.order = res.data.data.order;
                        if(res.data.data.exp.result){
                            this.resultcode = res.data.data.exp.resultcode;
                            this.exp = res.data.data.exp.result;
                            this.list = res.data.data.exp.result.list.reverse();
                        }
                    }
                });
            },
        }
    };
</script>
