
<!--覆盖-->
<template>
  <div id="information" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">修改门店</h4>
        </div>
        <div class="modal-body">
          <form role="form" v-if="val.type ==0" class="form-horizontal">
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">痊愈：</label>
              <div class="col-sm-8">
                <input type="text" v-model="val.info.N1" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">明显好转：</label>
              <div class="col-sm-8">
                <input type="text" v-model="val.info.N2" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">好转：</label>
              <div class="col-sm-8">
                <input type="text" v-model="val.info.N3" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">没变化：</label>
              <div class="col-sm-8">
                <input type="text" v-model="val.info.N4" class="form-control"/>
              </div>
            </div>
          </form>
          <form role="form" v-if="val.type ==1" class="form-horizontal">
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">免费取消次数：</label>
              <div class="col-sm-8">
                <input type="text" v-model="val.info.M" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">免费取消预约时间N：</label>
              <div class="col-sm-8">
                <input type="text" v-model="val.info.N" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">24h < 实际时间 < N：</label>
              <div class="col-sm-8">
                <input type="text" v-model="val.info.N1" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">12h < 实际时间 < 24：</label>
              <div class="col-sm-8">
                <input type="text" v-model="val.info.N2" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">12h > 实际时间：</label>
              <div class="col-sm-8">
                <input type="text" v-model="val.info.N3" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">实际时间 > 预约时间：</label>
              <div class="col-sm-8">
                <input type="text" v-model="val.info.N4" class="form-control"/>
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
             data:{}
          };
      },
      methods:{
          save(){
              this.$http.post('config',this.val).then(function(res){
                  if(res.data.status){
                      this.$dispatch('refreshList');
                      $('#information').modal('hide');
                  }
              })
          },
      }
  }
</script>