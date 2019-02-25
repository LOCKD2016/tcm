
<template>
  <div class="container main_warp">
    <div class="tit_nav">
      <div class="container clearfix">
        <div class="pull-left">诊疗管理</div>
      </div>
    </div>
    <div class="search_box">
      <dl>
        <dd class="row">
          <!--.form-group(style="margin-bottom:0;")
          div(style="float:left;line-height:30px;")
              span
              | 订单编号：
          .col-sm-2
              input.form-control(type="text",v-model="order_code")
          -->
          <div style="margin-bottom:0;" class="form-group">
            <div style="float:left;line-height:30px;"><span></span>医生：</div>
            <div class="col-sm-2">
              <input type="text" v-model="doctor" class="form-control"/>
            </div>
          </div>
          <div style="margin-bottom:0;" class="form-group">
            <div style="float:left;line-height:30px;"><span></span>患者：</div>
            <div class="col-sm-2">
              <input type="text" v-model="user" class="form-control"/>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="input-group">
              <div @click="getDate(1)" class="input-group-btn">
                <div class="btn btn-default"><i class="icon-search"></i></div>
              </div>
            </div>
          </div>
        </dd>
      </dl>
      <dl>
        <dd class="row">
          <div style="margin-bottom:0;" class="form-group">
            <div style="float:left;line-height:30px;"><span></span>类型：</div>
            <div class="col-sm-1">
              <select v-model="search.first" class="form-control">
                <option value="" selected="selected">不限</option>
                <option value="0">初诊</option>
                <option value="1">复诊</option>
              </select>
            </div>
          </div>
          <div style="margin-bottom:0;" class="form-group">
            <div style="float:left;line-height:30px;"><span></span>诊断方式：</div>
            <div class="col-sm-1">
              <select v-model="search.type" class="form-control">
                <option value="" selected="selected">不限</option>
                <option value="0">网诊</option>
                <option value="1">门诊</option>
              </select>
            </div>
          </div>
          <div style="margin-bottom:0;" class="form-group">
            <div style="float:left;line-height:30px;"><span></span>状态：</div>
            <div class="col-sm-1">
              <select v-model="search.status" class="form-control">
                <!--0:诊疗未开始[比如门诊预约未到时间] 5:诊疗中 9:追问中 10:诊疗结束-->
                <option value="" selected="selected">不限</option>
                <option value="0">诊疗未开始</option>
                <option value="5">诊疗中</option>
                <option value="9">追问中</option>
                <option value="10">诊疗结束</option>
              </select>
            </div>
          </div>
        </dd>
      </dl>
      <dl>
        <dd class="row">
          <div style="margin-bottom:0;" class="form-group">
            <div style="float:left;line-height:30px;"><span></span>诊疗日期：</div>
            <div class="col-sm-2">
              <input type="date" v-model="search.created_at" class="form-control"/>
            </div>
          </div>
          <!--.form-group(style="margin-bottom:0;")-->
          <!--    div(style="float:left;line-height:30px;")-->
          <!--        span-->
          <!--        | 是否抓药：-->
          <!--    .col-sm-1-->
          <!--        select.form-control(v-model="search.is_prescription")-->
          <!--            option(value=3 selected) 不限-->
          <!--            option(value=1) 是-->
          <!--            option(value=0) 否-->
          <div class="col-sm-2"><span>共 {{total}} 条记录</span>
            <!--sss-->
          </div>
        </dd>
      </dl>
    </div>
    <div class="user_table_box table-responsive">
      <table class="table table-bordered check_list">
        <thead>
          <tr>
            <th style="width:14%" class="col-sm-1">订单编号</th>
            <th class="col-sm-1">患者</th>
            <th class="col-sm-1">就诊医师</th>
            <th class="col-sm-1">类型</th>
            <th class="col-sm-1">诊断方式</th>
            <th style="width:12%" class="col-sm-1">就诊时间</th>
            <th class="col-sm-1">状态</th>
            <!--th.col-sm-1 是否抓药-->
            <th class="col-sm-1">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(index,cli) in data">
            <td>{{cli.bespeak.order.order_sn}}</td>
            <td>{{cli.user.realname}}</td>
            <td>{{cli.doctor.name}}</td>
            <td>{{cli.type}}</td>
            <td>{{cli.first}}</td>
            <td>{{cli.created_at}}</td>
            <td>{{cli.status}}</td>
            <!--td {{cli.is_prescription}}-->
            <td><span @click="detail(cli.id)">查看详情</span></td>
          </tr>
        </tbody>
      </table>
      <nav>
        <ul class="pagination">
          <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur"></paginate>
        </ul>
      </nav>
    </div>
    <chat_detail :id.sync="id"></chat_detail>
  </div>
</template>
<script type="text/javascript">
  import chat_detail from  './module/chat_detail.vue'
  export default{
      components: {
          chat_detail
      },
      created(){
          this.page = this.$route.params.id;
  
      },
      ready(){
          headNav(3);
      },
      data(){
          return{
              id:0,
              data:{},
              cur: 0,
              all: 0,
              total: 0,
              search:{},
              order_code:'',
              doctor:'',
              user:'',
              nextCheck:[
  
              ]
          }
      },
      events: {
          update(){
              this.getDate();
          }
      },
      watch:{
          search:{
              handler: function (val, oldVal) {
                  this.getDate(1);
              },
              deep: true
          }
      },
      methods:{
          getDate(page=''){
              if(page){
                  this.page = page;
              }
              this.search.user = this.user;
              this.search.order_code = this.order_code;
              this.search.doctor = this.doctor;
              this.$http({
                  url:'clinic/list',
                  method:'GET',
                  params:{page:this.page,search:this.search}
              }).then(function (res) {
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
          detail(id){
              this.id = id;
              $('#chat_detail').modal('show');
          },
          listen(data){
              this.getDate(data);
              this.$router.go({name: 'chat_admin', params: {id: data}});
          }
  
      }
  }
</script>