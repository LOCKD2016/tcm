
<template>
  <div class="container main_warp">
    <div class="tit_nav">
      <div class="container clearfix">
        <div class="pull-left">患者列表</div>
        <div class="pull-right"><a @click="exportData()" class="btn btn-sm btn-primary">导出管理</a></div>
      </div>
    </div>
    <div class="search_box">
      <dl>
        <dt>筛选</dt>
        <dd class="row">
          <div class="col-sm-2">
            <select v-model="sex" v-on:change="getAllUsers(1)" class="form-control">
              <option value="" selected="selected">请选择性别</option>
              <option value="0">未知</option>
              <option value="1">男</option>
              <option value="2">女</option>
            </select>
          </div>
          <div class="col-sm-3">
            <div class="input-group">
              <input type="search" v-model="name" placeholder="输入患者姓名搜索" class="form-control auto_inp"/>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="input-group">
              <input type="search" v-model="mobile" placeholder="输入手机号搜索" class="form-control auto_inp"/>
              <div v-on:click="getAllUsers(1)" class="input-group-btn">
                <div class="btn btn-default"><i class="icon-search"></i></div>
              </div>
            </div>
          </div>
          <div class="col-sm-2"><span>共 {{total}} 条记录</span></div>
        </dd>
      </dl>
    </div>
    <div class="user_table_box table-responsive">
      <table class="table table-bordered check_list">
        <thead>
          <tr>
            <th class="col-sm-1">序号</th>
            <th class="col-sm-1">患者昵称</th>
            <th class="col-sm-1">真实姓名</th>
            <th class="col-sm-1">性别</th>
            <th class="col-sm-1">年龄</th>
            <th class="col-sm-1">联系方式</th>
            <th class="col-sm-1">城市</th>
            <th class="col-sm-1">来源</th>
            <th class="col-sm-1">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(index,user) in users">
            <td>{{index+1}}</td>
            <td>{{user.nickname}}</td>
            <td>{{user.realname}}</td>
            <td>{{user.sex}}</td>
            <td>{{user.age}}</td>
            <td>{{user.mobile}}</td>
            <td>{{user.province}}{{user.city}}{{user.area}}</td>
            <td>{{user.source}}</td>
            <td><span @click="userDetail(user.id,0)">查看</span></td>
          </tr>
        </tbody>
      </table>
    </div>
    <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur" v-on:gopage="listen"></paginate>
  </div>
</template>
<script type="text/javascript">
  export default{
      created(){
          this.page = this.$route.params.id;
          this.getAllUsers();
      },
      ready(){
          headNav(0);
      },
      data(){
          return {
              users: {},
              cur: 0,
              all: 0,
              total: 0,
              mobile: '',
              sex: '',
              name: '',
              searchs: {},
          }
      },
      events: {
          update(){
              this.getAllUsers(this.cur);
          }
      },
      methods: {
          getAllUsers(page=''){
              if (page) {
                  this.page = page;
              }
              this.searchs.sex = this.sex;
              this.searchs.name = this.name;
              this.searchs.mobile = this.mobile;
              this.$http({
                  url: 'appuser/list',
                  method: 'GET',
                  params: {page: this.page, search: this.searchs}
              }).then(function (res) {
                  this.$set('users', res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
                  this.$set('total', pagination.total);
              })
              this.$router.go({name: 'app_users', params: {id: this.page}});
          },
          userDetail(id){
              this.$router.go({name: 'user_detail', params: {id: id}});
          },
          listen(data){
              this.getAllUsers(data);
              this.$router.go({name: 'app_users', params: {id: data}});
          },
          exportData() {
              var title = '用户管理';
              var head = [
                  '序号',
                  '患者昵称',
                  '真实姓名',
                  '性别',
                  '年龄',
                  '联系方式',
                  '城市',
                  '来源',
              ];
              var width = {
                  'A': 10,
                  'B': 10,
                  'C': 10,
                  'D': 10,
                  'E': 10,
                  'F': 20,
                  'G': 20,
                  'H': 10
              };
              this.searchs.sex = this.sex;
              this.searchs.name = this.name;
              this.searchs.mobile = this.mobile;
              this.$http.post('exports/exports',
                  {title: title, head: head, search: this.searchs, width: width, type: 'app_user', export: true}
              ).then(function (res) {
                  if (res.data.errcode == 200) {
                      location.href = "/api/upload/download/" + res.data.data;
                  } else {
                      layer.msg(res.data.msg);
                  }
              });
          },
      }
  }
</script>