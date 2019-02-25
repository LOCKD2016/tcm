
<template>
  <div class="container main_warp">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#tab1" role="tab" data-toggle="tab">网诊预约管理</a></li>
      <li><a href="#tab2" role="tab" data-toggle="tab">门诊预约管理</a></li>
    </ul>
    <div class="tab-content">
      <div id="tab1" class="tab-pane fade in active">
        <div class="search_box">
          <dl>
            <dt>筛选</dt>
            <dd class="row">
              <div class="col-sm-2">
                <div class="input-group">
                  <input id="seaItem" type="search" v-model="name_web" placeholder="输入患者姓名搜索" class="form-control auto_inp"/>
                  <div @click="getWebData(1)" class="input-group-btn">
                    <div class="btn btn-default"><i class="icon-search"></i></div>
                  </div>
                </div>
              </div>
              <div class="col-sm-2">
                <div class="input-group">
                  <input type="search" v-model="doc_name_web" placeholder="输入医生姓名搜索" class="form-control auto_inp"/>
                  <div @click="getWebData(1)" class="input-group-btn">
                    <div class="btn btn-default"><i class="icon-search"></i></div>
                  </div>
                </div>
              </div>
              <div style="margin-bottom:0px;" class="form-group">
                <label for="" class="col-sm-1 control-label"><span class="notice-w">*</span>选择时间：</label>
                <div class="col-sm-2">
                  <input type="date" v-model="datetime_web" class="form-control"/>
                </div>
              </div>
              <div class="col-sm-2">
                <select v-model="status_web" @change="getWebData(1)" class="form-control">
                  <!--5待接诊 10待支付 15已支付 20诊疗中 25诊疗结束 30医生拒绝接诊 35诊疗已取消-->
                  <option value="" selected="selected">预约状态</option>
                  <option value="5">待接诊</option>
                  <option value="10">待支付</option>
                  <option value="15">已支付</option>
                  <option value="20">诊疗中</option>
                  <option value="25">诊疗结束</option>
                  <option value="30">拒绝</option>
                  <option value="35">已取消</option>
                </select>
              </div>
              <div class="col-sm-2">
                <select v-model="pay_status_web" @change="getWebData(1)" class="form-control">
                  <option value="" selected="selected">订单状态</option>
                  <!--0 未支付 2正在支付 5已支付 9退款中 10已退款-->
                  <option value="0">未付款</option>
                  <option value="5">已付款</option>
                  <!--option(value=9) 退款中-->
                  <!--option(value=10) 已退款-->
                </select>
              </div>
            </dd>
          </dl>
        </div>
        <div class="user_table_box table-responsive">
          <table class="table table-bordered check_list">
            <thead>
              <tr>
                <th class="col-sm-1">序号</th>
                <th class="col-sm-1">患者</th>
                <th class="col-sm-1">预约医师</th>
                <th style="width:10%" class="col-sm-1">预约时间</th>
                <th class="col-sm-1">等待时间(分钟)</th>
                <th style="width:10%" class="col-sm-1">接诊时间</th>
                <th class="col-sm-1">预约状态</th>
                <th class="col-sm-1">备注</th>
                <th class="col-sm-1">操作人</th>
                <th class="col-sm-1">操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(index,val) in dataWeb" v-bind:class="{ check_background_red  : val.is_warning}">
                <td>{{cur_clinic*10 +index-9}}</td>
                <td>{{val.user.realname}}</td>
                <td>{{val.doctor.name}}</td>
                <td>{{val.created_at}}</td>
                <td>{{val.wait_time}}</td>
                <td>{{val.take_time}}</td>
                <td>{{val.status}}</td>
                <td>{{val.remark}}</td>
                <td>{{val.admin.user_name}}</td>
                <td><span v-if="val.status==&quot;待接诊&quot;" @click="cancle(val.id)" class="ccc">拒绝接诊</span><span v-if="val.status==&quot;待接诊&quot;" @click="referral(val.id)" class="ccc">转诊</span>
                  <!--span.ccc(@click="refund(val.order)") 退款-->
                  <!--span(@click="layOpen(val.id,2,val.remark)") 备注-->
                </td>
              </tr>
            </tbody>
          </table>
          <nav>
            <ul class="pagination">
              <paginate :cur.sync="cur_web" :all.sync="all_web" v-on:btn-click="listen_web" v-if="cur_web" v-on:gopage="listen_web"></paginate>
            </ul>
          </nav>
        </div>
      </div>
      <div id="tab2" class="tab-pane fade">
        <div class="search_box">
          <dl>
            <dt>筛选</dt>
            <dd class="row">
              <div class="col-sm-2">
                <div class="input-group">
                  <input type="search" v-model="name_clinic" placeholder="输入患者姓名搜索" class="form-control auto_inp"/>
                  <div @click="getClinicData(1)" class="input-group-btn">
                    <div class="btn btn-default"><i class="icon-search"></i></div>
                  </div>
                </div>
              </div>
              <div class="col-sm-2">
                <div class="input-group">
                  <input type="search" v-model="doc_name_clinic" placeholder="输入医生姓名搜索" class="form-control auto_inp"/>
                  <div @click="getClinicData(1)" class="input-group-btn">
                    <div class="btn btn-default"><i class="icon-search"></i></div>
                  </div>
                </div>
              </div>
              <div class="col-sm-2">
                <select v-model="status_clinic" @change="getClinicData(1)" class="form-control">
                  <option value="" selected="selected">预约状态</option>
                  <!--5待接诊 10待支付 15已支付 20诊疗中 25诊疗结束 30医生拒绝接诊 35诊疗已取消-->
                  <option value="10">待支付</option>
                  <option value="15">已支付</option>
                  <option value="20">诊疗中</option>
                  <option value="25">诊疗结束</option>
                  <option value="35">取消预约</option>
                  <!--option(value=9) 已过期-->
                </select>
              </div>
              <div class="col-sm-2">
                <select v-model="pay_status_clinic" @change="getClinicData(1)" class="form-control">
                  <option value="" selected="selected">订单状态</option>
                  <!--0 未支付 2正在支付 5已支付 9退款中 10已退款-->
                  <option value="0">未付款</option>
                  <option value="5">已付款</option>
                  <option value="9">退款中</option>
                  <option value="10">已退款</option>
                </select>
              </div>
              <div class="form-group">
                <label for="" class="col-sm-1 control-label"><span class="notice-w">*</span>选择时间：</label>
                <div class="col-sm-3">
                  <input type="date" v-model="datetime_clinic" class="form-control"/>
                </div>
              </div>
            </dd>
          </dl>
        </div>
        <div class="user_table_box table-responsive">
          <table class="table table-bordered check_list">
            <thead>
              <tr>
                <th class="col-sm-1">序号</th>
                <th class="col-sm-1">患者</th>
                <th class="col-sm-1">预约医师</th>
                <th class="col-sm-1">预约开始时间</th>
                <!--th.col-sm-1 预约结束时间-->
                <th class="col-sm-1">预约状态</th>
                <th class="col-sm-1">订单状态</th>
                <th class="col-sm-1">备注</th>
                <!--th.col-sm-1 操作人-->
                <!--th.col-sm-1 操作-->
              </tr>
            </thead>
            <tbody>
              <tr v-for="(index,val) in dataClinic">
                <td>{{cur_web*10 +index-9}}</td>
                <td>{{val.user.realname}}</td>
                <td>{{val.doctor.name}}</td>
                <td>{{val.start_time}}</td>
                <!--td {{val.end_time}}-->
                <td>{{val.status}}</td>
                <td>{{val.order.status}}</td>
                <td>{{val.remark}}</td>
                <!--td {{val.admin.user_name}}-->
                <!--td
                span(@click="refund(val.order)") 退款
                span(@click="layOpen(val.id,1,val.remark)") 备注
                
                
                -->
              </tr>
            </tbody>
          </table>
          <nav>
            <ul class="pagination">
              <paginate :cur.sync="cur_clinic" :all.sync="all_clinic" v-on:btn-click="listen_clinic" v-if="cur_clinic" v-on:gopage="listen_clinic"></paginate>
            </ul>
          </nav>
        </div>
      </div>
    </div>
    <referral :id.sync="id"></referral>
    <refund :order.sync="order"></refund>
  </div>
</template>
<script type="text/javascript">
  import referral from "./module/referral.vue";
  import refund from  './module/refund.vue';
  export default{
      created(){
          this.page = this.$route.params.id;
          this.getWebData(this.page);
          this.getClinicData(1);
      },
      components: {
          referral,
          refund
      },
      ready(){
          headNav(0);
      },
      data(){
          return {
  
              status:'',
              name:'',
              time:'',
              type:'',
              id: 0,
              cur: 1,
              cur_web: 0,
              cur_clinic: 0,
              dataWeb: [],
              dataClinic: [],
              order: [],
              all_web: 0,
              all_clinic: 0,
              total_web: 0,
              total_clinic: 0,
              status_clinic: '',
              status_web: '',
              name_web: '',
              doc_name_web: '',
              doc_name_clinic: '',
              name_clinic: '',
              datetime_clinic: '',
              datetime_web: '',
              pay_status_web: '',
              pay_status_clinic: ''
          }
      },
      events: {
          update(){
              this.getWebData(this.cur);
          }
      },
      watch: {
          'datetime_web': function (value, oldValue) {
              if (oldValue != value) {
                  this.getWebData(1);
              }
          },
          'datetime_clinic': function (value, oldValue) {
              if (oldValue != value) {
                  this.getClinicData(1);
              }
          }
      },
      methods: {
          getWebData(page){
              this.$http({
                  url: 'bespeaks/index',
                  method: 'GET',
                  params: {
                      page: page,
                      search: {type: 0, status: this.status_web, name: this.name_web, time: this.datetime_web, pay_status:this.pay_status_web, doc_name:this.doc_name_web}
                  }
              }).then(function (res) {
                  this.$set('dataWeb', res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur_web', pagination.current_page);
                  this.$set('all_web', pagination.total_pages);
                  this.$set('total_web', pagination.total);
              });
              this.$router.go({name: 'service', params: {id: page}});
          },
          getClinicData(page){
              this.$http({
                  url: 'bespeaks/index',
                  method: 'GET',
                  params: {
                      page: page,
                      search: {type: 1, status: this.status_clinic, name: this.name_clinic, time: this.datetime_clinic, pay_status:this.pay_status_clinic,doc_name:this.doc_name_clinic}
                  }
              }).then(function (res) {
                  this.$set('dataClinic', res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur_clinic', pagination.current_page);
                  this.$set('all_clinic', pagination.total_pages);
                  this.$set('total_clinic', pagination.total);
              });
              this.$router.go({name: 'service', params: {id: page}});
          },
          refund(order){
              if(!order.id || order.status_code < 5) {
                  layer.msg('订单未支付');
                  return false;
              }else if(order.status_code == 9 || order.status_code == 10){
                  layer.msg('订单退款中或者已退款');
                  return false;
              }
              this.order = order;
              $('#refund').modal('show');
          },
          layOpen(id, type, remark){
              if(remark == undefined) {
                  remark = ' ';
              }
              var vue = this;
              layer.open({
                  title: '<b>添加备注</b>',
                  type: 1,
                  area: ['500px', '300px'],
                  fixed: false, //不固定
                  scrollbar: false,//禁止出现滚动条
                  btn: ['保存并关闭', '直接关闭'],
                  maxmin: true,
                  content: '<textarea id="remark" class="layer_open" >'+remark+'</textarea>',
                  yes: function () {
                      var content = $("#remark").val();
                      vue.remark(id, content, type);
                  },
                  btn2: function (index, layero) {
                      layer.close(index);
                  }
              });
          },
          referral(id){
              this.id = id;
              $("#referral").modal("show");
          },
          remark(id, content, type){
              this.$http({
                  url: 'bespeaks/update/' + id,
                  method: "PUT",
                  params: {'type': 'remark', remark: content}
              }).then(function (res) {
                  if (res.data.errcode == 200) {
                      if (type == 1) {
                          this.getClinicData(this.cur_clinic);
                      } else {
                          this.getWebData(this.cur_web);
                      }
  
                  }
              });
              layer.closeAll();
          },
          doc_detail(id){
              this.$router.go({name: 'doc_detail', params: {id: id}});
          },
          listen_clinic(data){
              this.getClinicData(data);
              this.$router.go({name: 'service', params: {id: data}});
          },
          listen_web(data){
              this.getWebData(data);
          },
          cancle(id) {
              var _this = this;
              var confirm = layer.confirm('您确定您的操作吗？', {
                  btn: ['取消', '确定'],
                  skin: 'layui-layer-molv'
              }, function () {
              }, function () {
                  _this.docancle(id);
                  layer.close(confirm);
              });
          },
          docancle(id){
              this.$http({
                  url: 'bespeaks/update/' + id,
                  method: "PUT",
                  params: {'type': 'cancle', 'status': 30}
              }).then(function (res) {
                  layer.msg(res.data.msg);
                  if (res.data.errcode == 200) {
                      this.getWebData(this.cur);
                  }
              });
          }
      }
  }
</script>