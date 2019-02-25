
<!--覆盖-->
<template>
  <div id="logs" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">项目日志</h4>
        </div>
        <div class="modal-body">
          <div class="find_table_box table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>操作者</th>
                  <th>操作描述</th>
                  <th>操作时间</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="l in logs">
                  <td>{{l.user_realname}}</td>
                  <td>{{l.operation_detail}}</td>
                  <td>{{l.created_at}}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      props: ['fid'],
      ready(){
          this.getLogs(this.fid);
      },
      data(){
          return {
              logs:{},
          };
      },
      methods:{
          getLogs(id){
              if(id>0){
                  this.$http.get('projectOperation/' + id).then(function (res) {
                      this.$set('logs',res.data.data);
                  });
              }
          }
      }, watch: {
          fid(newValue, oldValue){
              this.getLogs(newValue);
          }
      }
  }
</script>