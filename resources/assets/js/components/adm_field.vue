
<template>
  <div class="tit_nav">
    <div class="container">
      <div class="pull-left">接口失败日志</div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="user_table_box table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>接口的描述</th>
            <th>接口编码</th>
            <th style="width:50%">返回数据</th>
            <th>备注</th>
            <th>请求时间</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="log in data">
            <td>{{log.title}}</td>
            <td>{{log.code}}</td>
            <td>{{log.return}}</td>
            <td>{{log.remarks}}</td>
            <td>{{log.created_at}}</td>
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
              data: [],
              cur: 0,
              all: 0,
              total: 0,
              id: 0
          };
      },
      created(){
          this.page = this.$route.params.id;
          this.getData();
      },
      ready(){
          headNav(5);
      },
      methods:{
          getData(page=''){
              if (page) {
                  this.page = page;
              }
              this.$http({url: 'job_field', method: 'GET', params: {page: this.page}}).then(function (res) {
                  this.$set('data', res.data.data);
                  this.$set('cur', res.data.current_page);
                  this.$set('all', res.data.last_page);
                  this.$set('total', res.data.total);
              });
          },
          listen(data){
              this.getLogs(data);
              this.$router.go({name: 'adm_log', params: {id: data}});
          }
      }
  }
</script>