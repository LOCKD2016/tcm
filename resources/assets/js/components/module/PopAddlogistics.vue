
<!--物流状态-->
<template>
  <div id="addlogistics" class="modal fade">
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
                <select v-model="express_company" class="form-control">
                  <option value="sf" selected="selected">顺丰快递</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><span>物流单号：</span></label>
              <div class="col-sm-10">
                <input type="text" placeholder="请输入物流单号" v-model="express_number" class="form-control"/>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
          <button type="button" @click="addSend(data)" class="btn btn-primary">添加</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      props: ['id'],
      created(){
          this.id = this.$route.params.id;
      },
      data(){
          return {
              send: {},
              express_number:'',
              express_company:0,
              send_status:0,
              data:{},
          };
      },
      methods: {
          addSend(data){
              this.data.id = this.id;
              this.data.express_number = this.express_number;
              this.data.express_company = this.express_company;
              this.$http.post('deal/addsend', data).then(function (res) {
                  if (res.data.status == 1) {
                      layer.msg(res.data.msg);
                      this.$set('send', '');
                      $("#addlogistics").modal("hide");
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
              this.$router.go("/send_list");
          },
      },
      watch: {
  
      }
  
  }
</script>