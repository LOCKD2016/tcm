
<!--覆盖-->
<template>
  <div id="medicinal_type" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">修改门店</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">名称：</label>
              <div class="col-sm-8">
                <input type="text" v-model="val.name" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">单位：</label>
              <div class="col-sm-8">
                <input type="text" v-model="val.unit" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">价格：</label>
              <div class="col-sm-8">
                <input type="text" v-model="val.amount" class="form-control"/>
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
      methods:{
          save(){
              this.$http.post('medicine/save',this.val).then(function(res){
                  layer.msg(res.data.msg);
                  if(res.data.status){
                      this.$dispatch('userupdate');
                      $('#medicinal_type').modal('hide');
                  }
              })
          },
      }
  }
</script>