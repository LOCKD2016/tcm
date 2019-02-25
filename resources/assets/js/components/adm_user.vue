
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">管理员列表</div>
      <div class="pull-right"><a onclick="itemPop(undefined,'useradd')" class="btn btn-primary btn-sm"><i class="icon-plus"></i><span>添加用户</span></a></div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="user_table_box table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="col-sm-2">登录账号</th>
            <th class="col-sm-1">真实姓名</th>
            <th class="col-sm-4">权限组</th>
            <th class="col-sm-5">操作功能</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users">
            <td>{{user.user_name}}</td>
            <td>{{user.user_realname}}</td>
            <td>{{user.group_name}}</td>
            <td><span @click="edit(user.user_id)">修改</span><span @click="userGroup(user.user_id)">设定操作组</span>
              <!--span 权限分配--><span v-on:click="pwd(user.user_id)">密码初始化</span><span v-on:click="del(user.user_id)" style="color:red">删除</span><span v-on:click="forbidden(user.user_id)">冻结</span>
            </td>
          </tr>
        </tbody>
      </table>
      <nav>
        <ul class="pagination">
          <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur"></paginate>
        </ul>
      </nav>
      <pop-useradd></pop-useradd>
      <pop-userinfo :userid.sync="userid"></pop-userinfo>
      <pop-groupedit :groupid.sync="groupid"></pop-groupedit>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      created(){
          this.page = this.$route.params.id;
          this.getUsers(1);
      },
      ready(){
          headNav(5);
      },
      data(){
          return {
              users: [],
              cur: 0,
              all: 0,
              msg: '',
              userid:0,
              groupid:0,
          }
      },
      events:{
          admuser(){
              this.getUsers(this.cur);
          }
      },
      methods: {
          getUsers(page=''){
              if (page) {
                  this.page = page;
              }
              this.$http({url: 'user/index', method: 'GET', params: {page: this.page}}).then(function (res) {
                  this.$set('users', res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
              });
          },
          del(userId){
              var  vue = this;
              layer.confirm('您确定删除？', {
                  btn: ['确定', '取消'] //按钮
              }, function () {
                  vue.$http.delete("user/" + userId).then(function (res) {
                      if (res.data.status == 1) {
                          layer.msg(res.data.msg);
                          vue.$dispatch("admuser");
                      } else {
                          layer.msg(res.data.msg);
                      }
                  });
              }, function () {
                  layer.msg('取消成功!');
              });
          },
          pwd(id){
              this.$http.get('user/pwd/' + id).then(function (res) {
                  if (res.data.status == 1) {
                      layer.msg("密码初始化为 123456");
                  } else {
                      layer.msg("更新失败");
                  }
              });
          },
          edit(userid){
              this.$set('userid',userid);
              $("#userinfo").modal("show");
          },
          userGroup(id){
                  this.$set('groupid',id);
                  $("#groupedit").modal("show");
          },
          forbidden(id){
              if(id == 1){
                  layer.msg("不能冻结超级管理员!");return;
              }
              this.$http.get('user/forbidden/'+id).then(function(res){
                  if (res.data.status == 1) {
                      layer.msg(res.data.msg);
                      this.$dispatch("admuser");
                  } else {
                      layer.msg(res.data.msg);
                  }
              });
          },
          listen(data){
              this.getUsers(data);
              this.$router.go({name: 'adm_user', params: {id: data}});
          }
      }
  }
</script>