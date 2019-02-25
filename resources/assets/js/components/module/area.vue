
<!--覆盖-->
<template>
  <div id="area" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">修改门店</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">地区：</label>
              <div class="col-sm-8">
                <input type="text" v-model="area_val.name" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">首价：</label>
              <div class="col-sm-8">
                <input type="text" v-model="area_val.initiate_price" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">首重：</label>
              <div class="col-sm-8">
                <input type="text" v-model="area_val.initiate_weight" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">续重：</label>
              <div class="col-sm-8">
                <input type="text" v-model="area_val.continue_price" class="form-control"/>
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
      props:['area_val'],
      data(){
          return {
             data:{}
          };
      },
      methods:{
          save(){
              this.$http.post('area',this.area_val).then(function(res){
                  if(res.data.status){
                      layer.msg('操作成功');
                      $('#area').modal('hide');
                  }
              })
          },
      }
  }
</script>