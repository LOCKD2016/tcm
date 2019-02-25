
<!--部门管理新建-->
<template>
  <div id="useradd" class="modal fade">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">新建用户</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-2 control-label">账号：</label>
              <div class="col-sm-10">
                <input type="text" v-model="user.user_name" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">真实姓名：</label>
              <div class="col-sm-10">
                <input type="text" v-model="user.user_realname" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">邮箱：</label>
              <div class="col-sm-10">
                <input type="text" v-model="user.user_email" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">手机号：</label>
              <div class="col-sm-10">
                <input type="text" v-model="user.user_phone" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">密码：</label>
              <div class="col-sm-10">
                <input type="password" v-model="user.user_password" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">确认密码：</label>
              <div class="col-sm-10">
                <input type="password" v-model="user.user_password_confirmation" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">所属权限组：</label>
              <div class="col-sm-10">
                <select v-model="user.rid" class="form-control">
                  <option selected="selected" value="0">暂不加入用户组</option>
                  <option v-for="userGroup in userGroups" v-bind:value="userGroup.id">{{userGroup.display_name}}</option>
                </select>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" v-on:click="reset()" class="btn btn-default">取消</button>
          <button type="button" v-on:click="addUser(user)" class="btn btn-primary">添加</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  import {errorMsg} from '../../common.js';
  export default {
      created(){
          this.getUserGroup();
      },
      data(){
          return {
              user: {},
              userGroups:{},
          };
      },
      methods: {
          getUserGroup(){
              this.$http.get('user/group').then(function (res) {
                  this.userGroups = res.data.roles;
              });
          },
          addUser(user){
              this.$http.post('user/adduser', user).then(function (res) {
                  if (res.data.status == 1) {
                      layer.msg(res.data.msg);
                      this.$set('user',{});
                      this.$dispatch("admuser");
                      this.$dispatch("userupdate");
                      $("#useradd").modal("hide");
                  } else {
                      layer.msg(res.data.msg);
                  }
              },function(res){
                  var data = res.data;
                  errorMsg(data.errors);
              });
          },
          reset(){
              $("#useradd").modal("hide");
          }
      }
  }
</script>