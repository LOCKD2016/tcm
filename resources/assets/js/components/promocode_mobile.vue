
<template>
  <div class="container main_warp">
    <div class="tit_nav">
      <div class="container clearfix">
        <div class="pull-left">活动管理</div>
        <div class="pull-right"><a @click="exportData()" class="btn btn-sm btn-primary">导出管理</a></div>
      </div>
    </div>
    <div id="searchList" class="search_box">
      <dl>
        <dt>筛选</dt>
        <dd class="row">
          <div class="col-sm-3">
            <div class="input-group">
              <input type="search" v-model="mobile" placeholder="输入手机号搜索" class="form-control auto_inp"/>
              <div v-on:click="this.getCode();" class="input-group-btn">
                <div class="btn btn-default"><i class="icon-search"></i></div>
              </div>
            </div>
          </div>
        </dd>
      </dl>
    </div>
    <div class="user_table_box table-responsive">
      <table class="table table-bordered check_list">
        <thead>
          <tr>
            <th class="col-sm-1">序</th>
            <th class="col-sm-2">手机号</th>
            <th class="col-sm-2">优惠码</th>
            <th class="col-sm-2">状态</th>
            <th class="col-sm-2">发放时间</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(index,code) in codes">
            <td>{{index+1}}</td>
            <td>{{code.mobile}}</td>
            <td>{{code.code}}</td>
            <td v-if="code.status == 0">未使用</td>
            <td v-if="code.status == 1">已使用</td>
            <td>{{code.created_at}}</td>
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
          this.getCode();
      },
  ready(){
          headNav(4);
      },
      data(){
          return{
              codes:{},
              search: {},
              cur:0,
              all:0,
              mobile: '',
          }
      },
      watch:{
      },
      events: {
          update(){
              this.getCode(this.cur);
          }
      },
      methods:{
          getCode(page=''){
              if (page) {
                  this.page = page;
              }
              this.search.mobile = this.mobile;
              this.$http({url:'promo/record',method:'GET',params:{page:this.page,search:this.search}}).then(function (res) {
                  this.$set('codes',res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
              })
          },
          exportData(){
              this.search.mobile = this.mobile;
              this.$http({
                  url: 'export/code',
                  method: 'GET',
                  params: {search: this.search}
              }).then(function (res) {
                  if (res.data.status == 1) {
                      location.href = "/api/upload/download/" + res.data.name;
                  }
              })
          },
          listen(data){
              this.getCode(data);
              this.$router.go({name: 'promocode_mobile', params: {id: data}});
          }
  
      }
  }
</script>