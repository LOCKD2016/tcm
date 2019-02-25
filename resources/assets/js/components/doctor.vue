
<template>
  <div class="container main_warp">
    <div class="tit_nav">
      <div class="container clearfix">
        <div class="pull-left">医生列表</div>
        <!--.pull-righta.btn.btn-sm.btn-primary(@click="exportData()") 导出管理
        -->
      </div>
    </div>
    <div class="search_box">
      <dl>
        <dt>筛选</dt>
        <dd class="row">
          <div class="col-sm-2">
            <select v-model="status" @change="getDate(1)" class="form-control">
              <option value="3" selected="selected">审核状态</option>
              <option value="0">待审核</option>
              <option value="1">审核通过</option>
              <option value="2">审核不通过</option>
            </select>
          </div>
          <div class="col-sm-2">
            <select v-model="source" @change="getDate(1)" class="form-control">
              <option value="" selected="selected">来源</option>
              <option value="1">注册</option>
              <option value="0">API</option>
            </select>
          </div>
          <div class="col-sm-3">
            <div class="input-group">
              <input id="seaItem" type="search" v-model="name" placeholder="输入医生姓名搜索" class="form-control auto_inp"/>
              <div @click="getDate(1)" class="input-group-btn">
                <div class="btn btn-default"><i class="icon-search"></i></div>
              </div>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="input-group">
              <input type="search" v-model="mobile" placeholder="输入医生手机号搜索" class="form-control auto_inp"/>
              <div @click="getDate(1)" class="input-group-btn">
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
            <th class="col-sm-1">门诊</th>
            <th class="col-sm-1">来源</th>
            <th class="col-sm-1">审核</th>
            <th style="width:15%" class="col-sm-1">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(index,doc) in data">
            <td>{{page*10 +index-9}}</td>
            <td><img v-bind:src="doc.photoSUrl" class="doc_headimg"/></td>
            <td>{{doc.name}}</td>
            <td>{{doc.mobile}}</td>
            <td v-if="doc.source !=2 &amp;&amp; doc.qualification_auth ==1">未上传</td>
            <td v-else="v-else">上传</td>
            <td>{{doc.net_chat}}</td>
            <td>{{doc.is_clinic}}</td>
            <td v-if="doc.source ==1">注册</td>
            <td v-else="v-else">API</td>
            <td>
              <select v-model="doc.apply" v-on:change="save(doc.id,doc.apply,'status')" class="form-control">
                <option value="1">审核通过</option>
                <option value="0">待审核</option>
                <option value="2">审核不通过</option>
              </select>
            </td>
            <td><span @click="doc_detail(doc.id)">详情</span>
              <!--span(@click="doc_delete(doc.id)") 删除--><span v-if="doc.web == 1" @click="save(doc.id,0,'web')" class="doc_red">禁网诊</span><span v-else="v-else" @click="save(doc.id,1,'web')">开通网诊</span>
              <template v-if="doc.source == 0"><span v-if="doc.clinic == 1" @click="save(doc.id,0,'clinic')" class="doc_red">禁门诊</span><span v-else="v-else" @click="save(doc.id,1,'clinic')">开通门诊</span></template>
            </td>
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
              source: '',
              name: '',
              mobile: ''
          }
      },
      events: {
          update(){
              this.getDate();
          }
      },
      methods:{
          save(id,status,type){
              var _this = this;
              var index = layer.confirm('请确认您的操作？', {
                  btn: ['确定执行', '取消']
              }, function (index, layero) {
                  var params = {};
                  if(type == 'status'){
                      params.status = status;
                  }else if(type == 'web'){
                      params.web = status;
                  }else if(type == 'clinic'){
                      params.clinic = status;
                  }
                  _this.$http({url: 'doctor/update/' + id,
                      method: 'PUT',
                      params: params
                  }).then(function (res) {
                      layer.msg(res.data.msg);
                      if (res.data.errcode == 200) {
                          this.getDate();
                      }
                  });
                  layer.close(index);
              }, function (index) {
                  layer.close(index);
              });
  
          },
          getDate(page=''){
              if(page){
                  this.page = page;
              }
              this.$http({url:'doctor/list',method:'GET',params:{page:this.page,status:this.status,source:this.source,name:this.name,mobile:this.mobile}}).then(function (res) {
                  this.$set('data',res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
                  this.$set('total', pagination.total);
              })
              this.$router.go({name: 'doctor', params: {id: this.page}});
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
          doc_delete(id){
              var _this = this;
              var index = layer.confirm('您确定要删除吗？', {
                  btn: ['确定执行', '取消']
              }, function (index, layero) {
                  var params = {};
                  _this.$http({url: 'doctor/delete/',
                      method: 'post',
                      params: {id: id}
                  }).then(function (res) {
                      layer.msg(res.data.msg);
                      if (res.data.errcode == 200) {
                          this.getDate();
                      }
                  });
                  layer.close(index);
              }, function (index) {
                  layer.close(index);
              });
          },
          listen(data){
              this.getDate(data);
              this.$router.go({name: 'doctor', params: {id: data}});
          }
  
      }
  }
</script>