
<template>
  <div class="container main_warp">
    <div class="tit_nav">
      <div class="container clearfix">
        <div class="pull-left">网诊管理</div>
      </div>
    </div>
    <div class="search_box">
      <dl>
        <dt>筛选</dt>
        <dd class="row">
          <div class="col-sm-2">
            <select v-model="status" @change="getDate()" class="form-control">
              <option value="3" selected="selected">状态</option>
              <option value="1">在职</option>
              <option value="2">离职</option>
              <option value="0">待审核</option>
              <option value="4">通过</option>
            </select>
          </div>
          <div class="col-sm-2">
            <select v-model="source" @change="getDate()" class="form-control">
              <option value="0" selected="selected">来源</option>
              <option value="2">注册</option>
              <option value="1">云中医</option>
            </select>
          </div>
          <div class="col-sm-3">
            <div class="input-group">
              <input id="seaItem" type="search" v-model="name" placeholder="输入医生姓名、手机号搜索" class="form-control auto_inp"/>
              <div @click="getDate()" class="input-group-btn">
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
            <th class="col-sm-1">头像</th>
            <th class="col-sm-1">医生姓名</th>
            <th class="col-sm-1">手机号</th>
            <th class="col-sm-1">医师资格证</th>
            <th class="col-sm-1">网诊</th>
            <th class="col-sm-1">状态</th>
            <th class="col-sm-1">来源</th>
            <th class="col-sm-1">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(index,doc) in data">
            <td>{{page*10 +index-9}}</td>
            <td><img v-bind:src="doc.photoSUrl" class="doc_headimg"/></td>
            <td>{{doc.name}}</td>
            <td>{{doc.mobile}}</td>
            <td v-if="doc.source !=1 &amp;&amp; doc.qualification_auth ==1">未上传</td>
            <td v-else="v-else">上传</td>
            <td>{{doc.net_chat}}</td>
            <td>{{doc.status}}</td>
            <td v-if="doc.source ==1">云中医</td>
            <td v-else="v-else">注册</td>
            <td><span @click="doc_detail(doc.id)">详情</span><span v-if="doc.blacklist ==1" @click="blackList(doc.id,doc.blacklist)">未拉黑</span><span v-else="v-else" @click="blackList(doc.id,doc.blacklist)" class="doc_red">已拉黑</span></td>
          </tr>
        </tbody>
      </table>
      <nav>
        <ul class="pagination">
          <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur"></paginate>
        </ul>
      </nav>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default{
      created(){
          this.page = this.$route.params.id;
          this.getDate();
      },
      ready(){
          headNav(0);
      },
      data(){
          return{
              data:{},
              cur: 0,
              all: 0,
              total: 0,
              status: 3,
              source: 0,
              name: ''
          }
      },
      events: {
          update(){
              this.getDate();
          }
      },
      methods:{
          blackList(id,black){
              this.$http({
                  url: 'doctor/update/'+id,
                  method: 'PUT',
                  params: {saveType: 'blackList', params:{black:black}}
              }).then(function (res) {
                  layer.msg(res.data.msg);
                  if(res.data.errcode ==200){
                      this.getDate();
                  }
              })
          },
          getDate(page=''){
              if(page){
                  this.page = page;
              }
              this.$http({url:'doctor/list',method:'GET',params:{page:this.page,status:this.status,source:this.source,name:this.name}}).then(function (res) {
                  this.$set('data',res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
                  this.$set('total', pagination.total);
              })
          },
          exportData(){
              this.searchs.name = this.name;
              this.searchs.type = this.type;
              this.$http({
                  url: 'export/export',
                  method: 'GET',
                  params: {search: this.searchs}
              }).then(function (res) {
                  if (res.data.status == 1) {
                      location.href = "/api/upload/download/" + res.data.name;
                  }
              })
          },
          doc_detail(id){
              this.$router.go({name: 'doc_detail', params: {id: id}});
          },
          listen(data){
              this.getDate(data);
              this.$router.go({name: 'doctor', params: {id: data}});
          }
  
      }
  }
</script>