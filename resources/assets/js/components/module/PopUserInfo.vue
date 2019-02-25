
<!--覆盖-->
<template>
  <div id="userinfo" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">修改用户信息</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">用户名：</label>
              <div class="col-sm-8">
                <input type="text" v-model="user.user_name" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">真实姓名：</label>
              <div class="col-sm-8">
                <input type="text" v-model="user.user_realname" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">邮箱：</label>
              <div class="col-sm-8">
                <input type="text" v-model="user.user_email" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">联系电话：</label>
              <div class="col-sm-8">
                <input type="text" v-model="user.user_phone" class="form-control"/>
              </div>
            </div>
            <div class="form-group team">
              <label for="" class="col-sm-3 control-label">用户地址：</label>
              <div class="col-sm-8">
                <input type="text" v-model="user.user_address" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">所属权限组：</label>
              <div class="col-sm-8">
                <select v-model="user.role_id" class="form-control">
                  <option value="0">暂不加入用户组</option>
                  <option v-for="userGroup in userGroups" v-bind:value="userGroup.id">{{userGroup.display_name}}</option>
                </select>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
          <button type="button" v-on:click="updateUser(user)" class="btn btn-primary">保存</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  import {errorMsg} from '../../common.js';
  export default {
      props:['userid'],
      created(){
          this.getUserGroup();
      },
      data(){
          return {
              uid: 0,
              user:{},
              userGroups:{}
          };
      },
      methods:{
          getUser(id){
              if(id>0){
                  this.$http.get('user/' + id).then(function (res) {
                      this.$set('user',res.data.data);
                  });
              }
          },
          getUserGroup(){
              this.$http.get('user/group').then(function (res) {
                  this.userGroups = res.data.roles;
              });
          },
          updateUser(user){
              this.$http.put('user/' + this.uid, user).then(function (res) {
                  var data = res.data;
                  if (data.status == 1) {
                      layer.msg(data.msg);
                      this.$dispatch("userupdate");
                      $("#userinfo").modal("hide");
                  } else {
                      layer.msg(data.msg);
                  }
              },function(res){
                  var data = res.data;
                  errorMsg(data.errors);
              });
          }
      },
      watch:{
          userid(newValue,oldValue){
              this.uid = newValue;
              this.getUser(newValue);
          }
      }
  }
</script>