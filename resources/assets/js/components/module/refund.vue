
<!--覆盖-->
<template>
  <div id="refund" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">退款</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">实付金额：</label>
              <div class="col-sm-8">
                <input type="text" v-model="order.pay_amount" readonly="readonly" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">退款金额：</label>
              <div class="col-sm-8">
                <input type="text" v-model="price" class="form-control"/>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
          <button type="button" v-on:click="enter()" class="btn btn-primary">自定义退款</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      props: ['order'],
      data(){
          return {
              price: ''
          }
      },
      methods: {
          enter(){
              var params = {};
              var preg = /(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/;
              if (!preg.test(this.price)) {
                  layer.msg('请输入正确的金额格式');
                  return;
              }
              if(this.price < 0){
                  layer.msg('金额不能少于0元');
                  return;
              }
              params.refund_amount = this.price;
              params.order_id = this.order.id;
              this.$http({url: 'order/refund', method: 'get', params:params}).then(function (res) {
                  layer.msg(res.data.msg);
                  if (res.data.status) {
                      this.$dispatch('update');
                      this.price = '';
                      $('#refund').modal('hide');
                  }
              });
          },
      }
  }
</script>