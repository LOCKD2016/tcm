
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">用户组管理</div>
      <div class="pull-right"><a onclick="itemPop(undefined,'usergroup')" class="btn btn-primary btn-sm"><i class="icon-plus"></i><span>新增用户组</span></a></div>
    </div>
  </div>
  <div class="container main_warp">
    <!--.bg_cor-->
    <div class="find_table_box table-responsive">
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
          <tr v-for="role in roles">
            <td>{{role.id}}</td>
            <td>{{role.name}}</td>
            <td>{{role.description}}</td>
            <td>
              <!--i.icon-exit--><span>修改</span><span>删除</span>
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
  </div>
</template>
<script type="text/javascript">
  export default {
      created(){
          this.getRole(1);
      },
      data(){
          return {
              roles:{},
              cur:0,
              all:0,
          }
      },
      //ssssssss
      methods: {
          getRole(page){
              this.$http({url: 'role', method: 'get', params: {page: page}}).then(function (res) {
                  this.$set('roles', res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
              })
          },
          listen(data){
              this.getRole(data);
          },
      }
  }
</script>