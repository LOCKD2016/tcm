
<template>
  <div class="container main_warp">
    <div class="tit_nav">
      <div class="container clearfix">
        <div class="pull-left">问诊单列表</div>
        <div class="pull-right"><a v-link="{ path: '/lnquiry_add' }" class="btn btn-primary btn-sm"><i class="icon-plus"></i><span>添加问诊单</span></a></div>
      </div>
    </div>
    <div class="user_table_box table-responsive">
      <table class="table table-bordered check_list">
        <thead>
          <tr>
            <th class="col-sm-1">序号</th>
            <th class="col-sm-2">类型</th>
            <th class="col-sm-2">创建时间</th>
            <th class="col-sm-1">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="ln in lnquiries">
            <td>{{ln.i}}</td>
            <td>{{ln.type}}</td>
            <td>{{ln.created_at}}</td>
            <td><span @click="checkDetail(ln.id)">查看问诊单</span><span @click="del(ln.id)" style="color:red">删除</span></td>
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
      ready(){
          headNav(3);
      },
      created(){
          this.page = this.$route.params.id;
          this.getLnquiries();
      },
      data(){
          return{
              lnquiries:{},
              cur:0,
              all:0,
              id:0
          }
      },
      events: {
          refreshln(){
              this.getLnquiries(this.cur);
          }
      },
      methods:{
          getLnquiries(page=''){
              if (page) {
                  this.page = page;
              }
              this.$http({url:'lnquiry/index',method:'GET',params:{page:this.page}}).then(function (res) {
                  this.$set('lnquiries',res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
              })
          },
          checkDetail(id){
              this.$router.go({name: 'lnquiry_detail',params: {id: id}});
          },
          del(id){
              var vue = this;
              layer.confirm('您确定删除？', {
                  btn: ['确定', '取消']
              }, function () {
                  vue.$http.delete('lnquiry/' + id).then(function (res) {
                      if (res.data.status) {
                          layer.msg(res.data.msg);
                          vue.$dispatch('refreshln');
                      }
                  })
              }, function () {
  
              });
          },
          listen(data){
              this.getLnquiries(data);
              this.$router.go({name: 'lnquiry_list', params: {id: data}});
          }
      }
  }
</script>