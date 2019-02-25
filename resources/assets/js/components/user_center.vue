
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">登录日志</div>
      <div class="pull-right"><a onclick="itemPop(undefined,'password')" class="btn btn-primary btn-sm"><i class="icon-lock"></i><span>修改密码</span></a></div>
    </div>
  </div>
  <div class="container main_warp">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>IP地址</th>
          <th>登录地址</th>
          <th>访问时间</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="log in logs">
          <td>{{log.ip}}</td>
          <td>{{log.address?log.address:'未知'}}</td>
          <td>{{log.created_at}}</td>
        </tr>
      </tbody>
    </table>
    <nav>
      <ul class="pagination">
        <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur"></paginate>
      </ul>
    </nav>
    <pop-password></pop-password>
  </div>
</template>
<script type="text/javascript">
  export default {
      created(){
          this.getLogs(1);
      },
      events: {
          refreshList(){
              this.getLogs(this.cur);
          }
      },
      data(){
          return {
              logs: [],
              cur: 0,
              all: 0,
              msg: ''
          };
      },
      methods: {
          getLogs(page){
              this.$http({url: 'getLogs', method: 'GET', params: {page: page}}).then(function (res) {
                  this.$set('logs', res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
              });
          },
          listen(data) {
              this.getLogs(data);
          }
      }
  }
</script>