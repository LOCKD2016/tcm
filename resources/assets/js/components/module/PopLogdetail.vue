
<!--部门管理新建-->
<template>
  <div id="logdetail" class="modal fade">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">日志详情</h4>
        </div>
        <div class="modal-body">
          <form role="form" v-for="log in logs" class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-2 control-label">登录账号：</label>
              <div class="col-sm-10">
                <input type="text" v-model="log.user_name" readonly="readonly" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">真实姓名：</label>
              <div class="col-sm-10">
                <input type="text" v-model="log.user_realname" readonly="readonly" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">ip：</label>
              <div class="col-sm-10">
                <input type="text" v-model="log.ip" readonly="readonly" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">登录地址：</label>
              <div class="col-sm-10">
                <input type="text" v-model="log.address" readonly="readonly" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">登录设备：</label>
              <div class="col-sm-10">
                <input type="text" v-model="log.useragent" readonly="readonly" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">登录时间：</label>
              <div class="col-sm-10">
                <input type="text" v-model="log.created_at" readonly="readonly" class="form-control"/>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  import {errorMsg} from '../../common.js';
  export default {
      props: ['id'],
      ready(){
          this.getLog(this.id);
      },
      data(){
          return {
              logs:{},
          };
      },
      methods: {
          //aaa
          getLog(id){
              if(id>0){
                  this.$http.get('logs/' + id).then(function (res) {
                      this.$set('logs',res.data);
                  })
              }
          }
      }, watch: {
          id(newValue, oldValue){
              this.getLog(newValue);
          }
      }
  }
</script>