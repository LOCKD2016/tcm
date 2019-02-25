
<template>
  <div class="container main_warp">
    <div class="tit_nav">
      <div class="container clearfix">
        <div class="pull-left">划价收费</div>
      </div>
    </div>
    <div class="search_box">
      <dl>
        <dd class="row">
          <div style="margin-bottom:0;" class="form-group clearfix">
            <div style="float:left"><span></span>患者：</div>
            <div style="float:left;width:20%;margin:0 20px;" class="div">
              <input type="text" v-model="userName" class="form-control"/>
            </div>
            <div style="float:left"><span></span>医师：</div>
            <div style="float:left;width:20%;margin:0 20px;" class="div">
              <input type="text" v-model="doctorName" class="form-control"/>
            </div>
            <div style="float:left;">
              <div class="input-group">
                <div @click="getDate(1)" class="input-group-btn">
                  <div class="btn btn-default"><i class="icon-search"></i></div>
                </div>
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
            <th style="width:5%" class="col-sm-1">序号</th>
            <th style="width:5%" class="col-sm-1">患者</th>
            <th style="width:5%" class="col-sm-1">医师</th>
            <th style="width:5%" class="col-sm-1">药材费</th>
            <th style="width:5%" class="col-sm-1">调剂费</th>
            <th class="col-sm-1">订单状态</th>
            <!--th.col-sm-1 药方状态-->
            <!--th.col-sm-1(style="width:10%") 状态操作时间-->
            <!--th.col-sm-1(style="width:5%") 划价人-->
            <!--th.col-sm-1(style="width:5%") 退款人-->
            <!--th.col-sm-1 退款备注-->
            <th style="width:10%" class="col-sm-1">开方时间</th>
            <th style="width:10%" class="col-sm-1">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(index,val) in data">
            <td>{{page*10 +index-9}}</td>
            <td>{{val.user.realname}}</td>
            <td>{{val.doctor.name}}</td>
            <td>{{val.medicine_price}}</td>
            <td>{{val.dispensing_price}}</td>
            <td>{{val.order.status}}</td>
            <!--td {{val.priceStatus}}-->
            <!--td {{val.is_price == 0 ? '' : val.price_time}}-->
            <!--td {{val.price_time}}-->
            <!--td {{val.admin.user_name}}-->
            <!--td {{val.refund_admin_name}}-->
            <!--td {{val.refund_remark}}-->
            <td>{{val.created_at}}</td>
            <td><span @click="detail(val)">查看</span>
              <!--span(v-if="val.send == 0",@click="_send(val.id,val.send,val.is_price)") 未发送-->
              <!--span(v-else) 已发送-->
              <!--span(@click="refund(val.order)") 退款-->
              <!--span(@click="layOpen(val.id)") 退款备注-->
            </td>
          </tr>
        </tbody>
      </table>
      <nav>
        <ul class="pagination">
          <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur"></paginate>
        </ul>
      </nav>
    </div>
    <price_detail :val.sync="val"></price_detail>
    <refund :order.sync="order"></refund>
  </div>
</template>
<script type="text/javascript">
  import price_detail from  './module/price_detail.vue'
  import refund from  './module/refund.vue'
  export default{
      components: {
          price_detail,
          refund
      },
      created(){
          this.page = this.$route.params.id;
          this.getDate();
      },
      ready(){
          headNav(0);
      },
      data(){
          return {
              id: 0,
              data: {},
              order: {},
              can_price: 0,
              cur: 0,
              all: 0,
              total: 0,
              userName: '',
              doctorName: '',
              registration_no: '',
              val: {
                  user:'',
                  doctor:'',
                  admin:'',
                  recipe_head:'',
                  recipe:''
  
              },
              date_time: ''
          }
      },
      events: {
          update(){
              this.getDate();
          }
      },
      methods: {
          remark(id, content){
              this.$http({
                  url: 'recipe/refund_remark/' + id,
                  method: "PUT",
                  params: {remark: content}
              }).then(function (res) {
                  if (res.data.errcode == 200) {
                      this.getDate(this.cur);
                  }
              });
              layer.closeAll();
          },
          layOpen(id){
              var vue = this;
              layer.open({
                  title: '<b>添加备注</b>',
                  type: 1,
                  area: ['500px', '300px'],
                  fixed: false, //不固定
                  scrollbar: false,//禁止出现滚动条
                  btn: ['保存并关闭', '直接关闭'],
                  maxmin: true,
                  content: '<textarea id="remark" class="layer_open" ></textarea>',
                  yes: function () {
                      var content = $("#remark").val();
                      vue.remark(id, content);
                  },
                  btn2: function (index, layero) {
                      layer.close(index);
                  }
              });
          },
          family_detail(fid){
              window.open('/admin/family_detail/' + fid);
          },
          refund(order){
              this.order = order;
              $('#refund').modal('show');
          },
          _send(id, send, is_price){
              if(is_price == 0){
                  layer.msg('药方未划价');
              }
              var vue = this;
              if (send == 0) {
                  var msg = '是否确定发送？';
              } else {
                  var msg = '是否确定取消发送？';
              }
              var data = {};
              data.send = 1;
              layer.confirm(msg, {
                  btn: ['确定', '取消'] //按钮
              }, function () {
                  vue.$http({url: 'prescription/setprice/' + id, method: "PUT", params: {data}}).then(function (res) {
                      layer.msg(res.data.msg);
                      if (res.data.status) {
                          this.getDate();
                      }
                  });
              })
  
          },
          getDate(page=''){
              if (page) {
                  this.page = page;
              }
              this.$http({
                  url: 'prescription/pricelist',
                  method: 'GET',
                  params: {
                      can_price: this.can_price,
                      page: this.page,
                      userName: this.userName,
                      doctorName: this.doctorName,
                      registration_no: this.registration_no,
                      date_time: this.date_time
                  }
              }).then(function (res) {
                  this.$set('data', res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
                  this.$set('total', pagination.total);
              })
          },
          detail(val){
              this.val = val;
              $('#price_detail').modal('show');
          },
          listen(data){
              this.getDate(data);
              this.$router.go({name: 'charge_price', params: {id: data}});
          }
  
      }
  }
</script>