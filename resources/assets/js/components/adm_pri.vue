
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">权限管理</div>
      <div class="pull-right"><a v-link="{ path: 'auth' }" class="btn btn-primary btn-sm"><i class="icon-plus"></i><span>预览权限</span></a>
        <!--a.btn.btn-primary.btn-sm(onclick="itemPop(#{i},'auth')")
        i.icon-plus
        span 新增权限
        --><a onclick="itemPop(undefined,'usergroup')" class="btn btn-primary btn-sm"><i class="icon-plus"></i><span>新增用户组</span></a>
      </div>
    </div>
  </div>
  <div class="container main_warp">
    <!--.bg_cor-->
    <div class="user_table_box table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>用户组</th>
            <th>描述</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="auth in auths">
            <td>{{auth.id}}</td>
            <td>{{auth.display_name}}</td>
            <td>{{auth.description}}</td>
            <td>
              <!--i.icon-exit--><span v-on:click="edit(auth.id)">修改</span><span v-on:click="del(auth.id)">删除</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <nav>
      <ul class="pagination">
        <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur"></paginate>
      </ul>
    </nav>
    <pop-auth></pop-auth>
    <pop-usergroup></pop-usergroup>
    <pop-usergroupedit :id.sync="id"></pop-usergroupedit>
  </div>
</template>
<script type="text/javascript">
  window.datas = [];
  import PopUsergroupedit from "./module/PopUsergroupedit.vue"
  export default {
      components: {
          PopUsergroupedit
      },
      created(){
          this.getAuth(1);
          this.getAuthJson();
      },
      ready(){
          headNav(4);
      },
      data(){
          return{
              auths:[],
              cur:0,
              all:0,
              id:0,
              getAuths:[],
          }
      },
      events: {
          refreshList(){
              this.getAuth(this.cur);
          }
      },
      methods: {
          getAuthJson(){
              var self = this;
              this.$http.get('getJson').then(function (res) {
                  var data = res.data.permissions;
                  $('#priTree').jstree({
                      'plugins': ["wholerow", "checkbox"], 'core': {
                          'data': data
                      }
                  }).on("changed.jstree", function (e, data) {
                      window.datas = data.selected;
                  });
              });
          },
          getAuth(page){
              this.$http({url: 'role' ,method:'get',params:{page:page}}).then(function(res){
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
              layer.confirm('您确定删除？', {
                  btn: ['确定', '取消'] //按钮
              }, function () {
                  vue.$http.delete('role/' + id).then(function (res) {
                      if (res.data.status == 1) {
                          layer.msg(res.data.msg);
                          vue.$dispatch("refreshList");
                      } else {
                          layer.msg(res.data.msg);
                      }
                  })
              }, function () {
              });
          },
          edit(id){
              this.$set('id',id);
              $("#usergroupedit").modal("show");
          }
      }
  }
</script>