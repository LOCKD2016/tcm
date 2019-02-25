
<!--物流状态-->
<template>
  <div id="logisticsupdate" class="modal fade">
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
                <input type="text" v-model="send.express_company" readonly="readonly" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><span>物流单号：</span></label>
              <div class="col-sm-10">
                <input type="text" v-model="send.express_number" class="form-control"/>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
          <button type="button" @click="addSend(send)" class="btn btn-primary">修改</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      props: ['id'],
      created(){
          this.id = this.$route.params.id;//
      },
      data(){
          return {
              send: {},
          };
      },
      methods: {
          addSend(send){
              this.$http.put('deal/updatesend/'+ this.id, send).then(function (res) {
                  if (res.data.status == 1) {
                      layer.msg(res.data.msg);
                      this.$set('send', '');
                      $("#logisticsupdate").modal("hide");
                      this.$dispatch("update");
                  } else {
                      layer.msg(res.data.msg);
                  }
              });
          },
          getDealDetail(id){
              if (id > 0) {
                  this.$http.get('deal/sendetail/' + id).then(function (res) {
                      this.$set('send', res.data.data);
                  });
              }
          },
          goback(){
              this.$router.go("/send_list");
          },
      },
      watch: {
          id(newValue){
              this.getDealDetail(newValue);
          }
      }
  
  }
</script>