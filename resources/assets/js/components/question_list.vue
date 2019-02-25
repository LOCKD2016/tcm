
<template>
  <div class="container main_warp">
    <div class="tit_nav">
      <div class="container clearfix">
        <div class="pull-left">问题列表</div>
        <div class="pull-right"><a v-link="{ path: '/question_add' }" class="btn btn-primary btn-sm"><i class="icon-plus"></i><span>添加问题</span></a></div>
      </div>
    </div>
    <div class="user_table_box table-responsive">
      <table class="table table-bordered check_list">
        <thead>
          <tr>
            <th class="col-sm-1">序号</th>
            <th class="col-sm-3">名称</th>
            <th class="col-sm-1">类型</th>
            <th class="col-sm-1">是否必填</th>
            <th class="col-sm-1">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(i,ln) in questions">
            <td>{{i+1}}</td>
            <td>{{ln.question}}</td>
            <td>{{ln.type}}</td>
            <td>{{ln.necessary}}</td>
            <td><span @click="checkDetail(ln.id)">修改</span><span @click="del(ln.id)" style="color:red">删除</span></td>
          </tr>
        </tbody>
      </table>
      <nav>
        <ul class="pagination">
          <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur" v-on:gopage="listen"></paginate>
        </ul>
      </nav>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default{
      created(){
          this.page = this.$route.params.id;
          this.getQuestions();
      },
      ready(){
          headNav(3);
      },
      data(){
          return{
              questions:{},
              cur:0,
              all:0
          }
      },
      events: {
          questionlist(){
              this.getQuestions(this.cur);
          }
      },
      methods:{
          getQuestions(page=''){
              if (page) {
                  this.page = page;
              }
              this.$http({url:'question/index',method:'GET',params:{page:this.page}}).then(function (res) {
                  this.$set('questions',res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
              })
          },
          listen(data){
              this.getQuestions(data);
              this.$router.go({name: 'question_list', params: {id: data}});
          },
          checkDetail(id){
              this.$router.go({name: 'question_answer',params: {id: id}});
          },
          del(id){
              var vue = this;
              layer.confirm('您确定删除？', {
                  btn: ['确定', '取消']
              }, function () {
                  vue.$http.delete('question/' + id).then(function (res) {
                      if (res.data.status) {
                          layer.msg(res.data.msg);
                          vue.$dispatch('questionlist');
                      }
                  })
              }, function () {
  
              });
          }
      }
  }
</script>