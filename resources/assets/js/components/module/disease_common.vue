
<template>
  <div id="diseasecommon" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label for="" class="col-sm-1 control-label"><span>名称：</span></label>
              <div class="col-sm-5"><span>{{data.disease.name}}</span></div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-1 control-label"><span>图标：</span></label>
              <div class="col-sm-5">
                <input id="shareimgs" type="file" name="file"/><img id="image" v-bind:src="data.icon"/>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
          <button type="button" data-dismiss="modal" @click="save()" class="btn btn-primary">保存</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      props:['id'],
      ready(){
          this.upload();
      },
      data(){
          return {
              data:{},
          };
      },
      methods:{
          save(){
              this.$http({url: 'disease_common/'+this.id,method:"put",params:{param:this.data}}).then(function (res) {
                  if (res.data.status == 1) {
                      $('#diseasecommon').modal('hide');
                      this.$dispatch('update');
                  }
              });
          },
          getDetail(id){
              this.$http({url:'disease_common/'+id}).then(function (res) {
                  this.data = res.data.data;
              });
          },
          upload(){
              var self = this;
              layui.use('upload', function () {
                  layui.upload({
                      url: '/api/upload/add'
                      , elem: '#shareimgs'
                      , method: 'post'
                      , success: function (res) {
                          layer.msg('图片准备就绪~~~');
                          //$('#image').attr('src',res.data);
                          self.data.icon = res.data;
                      }
                  });
              });
          },
      },
      watch:{
          'id':function(value){
              if(value>0){
                  this.getDetail(value);
              }
          }
      }
  }
</script>