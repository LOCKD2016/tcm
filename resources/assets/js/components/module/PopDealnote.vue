
<!--物流状态-->
<template>
  <div id="dealnote" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">备注</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <div class="col-sm-10">
                <textarea v-model="info.note" class="form-control"></textarea>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
          <button type="button" @click="addNote(info)" class="btn btn-primary">添加</button>
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
              info: {},
          };
      },
  
      methods: {
          getDetail(id){
              if (id > 0) {
                  this.$http.get('deal/sendetail/' + id).then(function (res) {
                      this.$set('info', res.data.data);
                  });
              }
          },
          addNote(info){
              info.id = this.id;
              this.$http.post('deal/add', info).then(function (res) {
                  if (res.data.status == 1) {
                      layer.msg(res.data.msg);
                      $("#dealnote").modal("hide");
                      this.$dispatch("update");
                  }
              });
          }
  
      },
      watch: {
          id(newValue){
              this.getDetail(newValue);
          },
      }
  
  }
</script>