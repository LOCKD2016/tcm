
<!--物流状态-->
<template>
  <div id="logistics" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">物流状态</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-2 control-label"><span>物流公司：</span></label>
              <div class="col-sm-10">
                <select v-model="send.logistics" class="form-control">
                  <option>请输入物流公司</option>
                  <option value="sf">顺丰快递</option>
                  <option value="sto">申通快递</option>
                  <option value="yt">圆通快递</option>
                  <option value="yd">韵达快递</option>
                  <option value="tt">天天快递</option>
                  <option value="ems">EMS</option>
                  <option value="zto">中通快递</option>
                  <option value="ht">汇通快递</option>
                  <option value="qf">全峰快递</option>
                  <option value="db">德邦快递</option>
                  <option value="gt">国通快递</option>
                  <option value="rfd">如风达快递</option>
                  <option value="jd">京东快递</option>
                  <option value="zjs">宅急送</option>
                  <option value="emsg">EMS国际</option>
                  <option value="fedex">Fedex国际</option>
                  <option value="yzgn">邮政国内</option>
                  <option value="ups">UPS国际快递</option>
                  <option value="ztky">中铁快运</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><span>物流单号：</span></label>
              <div class="col-sm-10">
                <input type="text" placeholder="请输入物流单号" v-model="send.deal_number" class="form-control"/>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
          <button type="button" @click="addSend(send)" class="btn btn-primary">添加</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      props: ['id'],
      created(){
      },
      data(){
          return {
              send: {}
          };
      },
      methods: {
          getSendDetail(id){
              if (id > 0) {
                  this.$http.get('getSendDetail/' + id).then(function (res) {
                      this.$set('send', res.data.data);
                  });
              }
          },
          addSend(send){
              this.$http.post('addSend', send).then(function (res) {
                  if (res.data.status == 1) {
                      layer.msg(res.data.msg);
                      this.$set('send', '');
                      $("#logistics").modal("hide");
                      this.$dispatch("update");
                  }else{
                      layer.msg(res.data.msg);
                  }
              }, function (res) {
                  var data = res.data;
                  errorMsg(data.errors);
              });
          },
          goback(){
              this.$router.go("/else_deliver");
          },
      },
      watch: {
          id(newValue){
              this.getSendDetail(newValue);
          }
      }
  
  }
</script>