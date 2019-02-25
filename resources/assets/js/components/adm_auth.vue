
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">权限管理</div>
      <div class="pull-right"><a onclick="itemPop(undefined,'auth')" class="btn btn-primary btn-sm"><i class="icon-plus"></i><span>新增权限</span></a></div>
    </div>
  </div>
  <div class="container main_warp">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>id</th>
          <th>展示名</th>
          <th>权限名</th>
          <th>描述</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="auth in auths">
          <td>{{auth.id}}</td>
          <td>{{auth.display_name}}</td>
          <td>{{auth.name}}</td>
          <td>{{auth.description}}</td>
          <td><span v-on:click="edit(auth.id)">修改</span><span v-on:click="del(auth.id)">删除</span></td>
        </tr>
      </tbody>
    </table>
    <nav>
      <ul class="pagination">
        <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur"></paginate>
      </ul>
    </nav>
    <pop-auth></pop-auth>
    <pop-authsave :id.sync="id"></pop-authsave>
  </div>
</template>
<script type="text/javascript">
  import PopAuthsave from "./module/PopAuthsave.vue"
  export default {
      components: {
          PopAuthsave
      },
      created(){
          this.getAuth(1);
      },
      ready(){
          headNav(5);
      },
      data(){
          return{
              auths:[],
              id:0,
              cur: 0,
              all: 0,
              msg: ''
          }
      },
      events: {
          refreshList(){
              this.getAuth(this.cur);
          }
      },
      methods: {
          getAuth(page){
              this.$http({url: 'auth' ,method:'get',params:{page:page}}).then(function(res){
                      this.$set('auths', res.data.data);
                      var pagination = res.data.meta.pagination;
                      this.$set('cur', pagination.current_page);
                      this.$set('all', pagination.total_pages);
              })
          },
          listen(data){
              this.getAuth(data);
          },
          del(id){
              var vue = this;
              layer.confirm('您确定删除它和他的子权限？', {
                  btn: ['确定', '取消']
              }, function () {
                  vue.$http.delete('auth/' + id).then(function (res) {
                      if (res.data.status == 1) {
                          layer.msg(res.data.msg);
                          vue.$dispatch("refreshList");
                          vue.$dispatch('count');
                      } else {
                          layer.msg(res.data.msg);
                      }
                  })
              },
              function () {
              });
          },
          edit(id){
              this.$set('id', id);
              $("#authsave").modal("show");
          }
      }
  }
</script>