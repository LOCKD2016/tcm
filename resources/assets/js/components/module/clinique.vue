
<!--覆盖-->
<template>
  <div id="clinique" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">修改门店</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">地址：</label>
              <div class="col-sm-8">
                <input type="text" v-model="val.content.address" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">电话：</label>
              <div class="col-sm-8">
                <input type="text" v-model="val.telephone" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">经度：</label>
              <div class="col-sm-8">
                <input type="text" v-model="val.content.longitude" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">纬度：</label>
              <div class="col-sm-8">
                <input type="text" v-model="val.content.latitude" class="form-control"/>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
          <button type="button" v-on:click="save()" class="btn btn-primary">保存</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      props:['val'],
      data(){
          return {
              data:{},
          };
      },
      methods:{
          save(){
              this.$http.post('clinique/update',this.val).then(function(res){
                  if(res.data.status){
                      this.$dispatch('userupdate');
                      $('#clinique').modal('hide');
                  }
              })
          },
      }
  }
</script>