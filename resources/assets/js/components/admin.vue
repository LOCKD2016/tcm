
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">管理员列表</div>
      <div class="pull-right"><a onclick="itemPop(undefined,'useradd')" class="btn btn-primary btn-sm"><i class="icon-plus"></i><span>添加用户</span></a></div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="item_list">
      <div class="list">
        <div class="user_table_box table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>序号</th>
                <th>账号</th>
                <th>真实姓名</th>
                <th>上次登录时间</th>
                <th>管理组</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users">
                <td>{{user.user_id}}</td>
                <td>{{user.user_name}}</td>
                <td>{{user.user_realname}}</td>
                <td>{{user.user_last_login_time}}</td>
                <td>{{user.group_name}}</td>
                <td><span @click="userSave(user.user_id)">修改</span><span @click="userDel(user.user_id)">删除</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <nav>
        <ul class="pagination">
          <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur"></paginate>
        </ul>
      </nav>
      <pop-useradd></pop-useradd>
      <pop-userinfo :id.sync="id"></pop-userinfo>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      data(){ //页面用到的数据
          return {
              cur:0,
              all:0,
              users:{},
              id:0
          };
      },
      created(){//实例创建后调用
          headNav(5);
          this.getUser(this.cur);
      },
      events: {
          userupdate(){
              this.getUser(1);
          }
      },
      methods:{
          getUser(page){
              this.$http({url:'user/index',method:'GET',params:{page:page}}).then(function(res){
                  this.$set('users', res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
              });
          },
          listen(data){
              this.getUser(data);
          },
          userSave(id){
              this.$set('id', id);
              $("#userinfo").modal("show");
          },
          userDel(id){
              var vue = this;
              layer.confirm('您确定删除？', {
                  btn: ['确定', '取消'] //按钮
              }, function () {
                  vue.$http.delete("user/" + id).then(function (res) {
                      if (res.data.status == 1) {
                          layer.msg(res.data.msg);
                          vue.$dispatch("userupdate");
                      } else {
                          layer.msg(res.data.msg);
                      }
                  });
              }, function () {
              });
          }
  
      }
  }
</script>