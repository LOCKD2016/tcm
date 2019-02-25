
<!--修改密码-->
<template>
  <div id="password" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">修改密码</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">原密码：</label>
              <div class="col-sm-10">
                <input type="password" v-model="password.user_password" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">新密码：</label>
              <div class="col-sm-10">
                <input type="password" v-model="password.user_newpassword" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">确认新密码：</label>
              <div class="col-sm-10">
                <input type="password" v-model="password.user_newpassword_confirmation" class="form-control"/>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
          <button type="button" v-on:click="getPassword(password)" class="btn btn-primary">确认修改</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  import {errorMsg} from '../../common.js';
  export default{
      created(){
  
      },
      data(){
          return {
              password:{}
          }
      },
      methods:{
          getPassword(password){
              this.$http.post('user/resetpwd/1',password).then(function(res){
                      layer.msg(res.data.msg);
                      this.$dispatch("refreshList");
                      $("#password").modal("hide");
              },function(res){
                  var data = res.data;
                  errorMsg(data.errors);
              })
          }
      }
  }
</script>