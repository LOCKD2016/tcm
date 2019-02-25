
<template>
  <div class="tit_nav">
    <div class="container">
      <div class="pull-left">登录日志</div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="user_table_box table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>登录账号</th>
            <th>真实姓名</th>
            <th>权限组</th>
            <th>上次登录时间</th>
            <th>操作功能</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="log in logs">
            <td>{{log.user_name}}</td>
            <td>{{log.user_realname}}</td>
            <td>{{log.group_name}}</td>
            <td>{{log.created_at}}</td>
            <td><span @click="detail(log.id)">查看登录详情</span></td>
          </tr>
        </tbody>
      </table>
      <nav>
        <ul class="pagination">
          <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur"></paginate>
        </ul>
      </nav>
      <pop-logdetail :id.sync="id"></pop-logdetail>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default{
      data(){
          return {
              logs: [],
              cur: 0,
              all: 0,
              msg: '',
              id: 0
          };
      },
      created(){
          this.page = this.$route.params.id;
          this.getLogs();
  
      },
      ready(){
          headNav(4);
      },
      methods:{
          getLogs(page=''){
              if (page) {
                  this.page = page;
              }
              this.$http({url: 'logs', method: 'GET', params: {page: this.page}}).then(function (res) {
                  this.$set('logs', res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
              });
          },
          listen(data){
              this.getLogs(data);
              this.$router.go({name: 'adm_log', params: {id: data}});
          },
          detail(id){
              this.$set('id', id);
              $("#logdetail").modal("show");
          }
      }
  }
</script>