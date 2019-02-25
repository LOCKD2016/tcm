
<template>
  <div class="container main_warp">
    <div class="tit_nav">
      <div class="container clearfix">
        <div class="pull-left">充值订单管理</div>
        <div class="pull-right"><a @click="exportData()" class="btn btn-sm btn-primary">导出管理</a></div>
      </div>
    </div>
    <div class="search_box">
      <dl>
        <dd class="row">
          <div class="col-sm-1"> 订单编号</div>
          <div class="col-sm-1">
            <div class="input-group">
              <input type="text" v-model="search.order_sn" placeholder="" class="form-control auto_inp"/>
            </div>
          </div>
          <div class="col-sm-1"> 卡类型</div>
          <div class="col-sm-1">
            <select v-model="search.card_type" class="form-control">
              <option value="0">不限</option>
              <option value="2">家庭卡</option>
              <option value="1">VIP卡</option>
            </select>
          </div>
          <div class="col-sm-1"> 卡号</div>
          <div class="col-sm-1">
            <div class="input-group">
              <input type="text" v-model="search.number" placeholder="" class="form-control auto_inp"/>
            </div>
          </div>
          <div class="col-sm-1"> 持卡人</div>
          <div class="col-sm-1">
            <div class="input-group">
              <input type="text" v-model="search.cardholder" placeholder="" class="form-control auto_inp"/>
            </div>
          </div>
          <div class="col-sm-2">
            <div class="input-group">
              <select v-model="search.cur_total" class="form-control">
                <option value="10">10条每页</option>
                <option value="20">20条每页</option>
                <option value="50">50条每页</option>
                <option value="100">100条每页</option>
              </select>
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
            <th style="width:15%;" class="col-sm-1">订单编号</th>
            <th class="col-sm-1">卡号</th>
            <th class="col-sm-1">订单类型</th>
            <th class="col-sm-1">所属人</th>
            <th class="col-sm-1">充值金额</th>
            <th class="col-sm-1">实付金额</th>
            <th class="col-sm-1">充值方式</th>
            <th style="width:12%;" class="col-sm-1">充值时间</th>
            <th class="col-sm-1">退款金额</th>
            <th class="col-sm-1">状态</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(index,val) in data">
            <td> {{val.order_sn}}</td>
            <td>{{val.cardNo}}</td>
            <td>{{val.opration}}</td>
            <td>{{val.realname}}</td>
            <td> {{val.goods_amount}}</td>
            <td> {{val.amount}}</td>
            <td>{{val.pay_type}}</td>
            <td>{{val.pay_time}}</td>
            <td>{{val.refund_amount}}</td>
            <td> {{val.pay_status}}</td>
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
          headNav(1);
      },
      data(){
          return {
              data: {},
              cur: 0,
              all: 0,
              total: 0,
              search: {
                  card_type: 0,
                  cur_total: 10,
              },
          }
      },
      events: {
          update(){
              this.getDate();
          }
      },
      methods: {
          getDate(page=''){
              if (page) {
                  this.page = page;
              }
              this.$http({
                  url: 'drugs_pay',
                  method: 'GET',
                  params: {page: this.page, search: this.search}
              }).then(function (res) {
                  this.$set('data', res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
                  this.$set('total', pagination.total);
              })
          },
          exportData(){
              var title = '充值订单';
              var head = [
                  '订单编号',
                  '卡号',
                  '订单类型',
                  '所属人',
                  '充值金额',
                  '实付金额',
                  '充值方式',
                  '充值时间',
                  '退款金额',
                  '状态',
              ];
              var width = {
                  'A': 15,
                  'B': 15,
                  'C': 10,
                  'D': 10,
                  'E': 10,
                  'F': 20,
                  'G': 20,
                  'I': 20,
              };
              this.$http({
                  url: 'count/exports',
                  method: 'GET',
                  params: {title: title, head: head, data: this.data, width: width}
              }).then(function (res) {
                  if (res.data.errcode == 200) {
                      location.href = "/api/upload/download/" + res.data.data;
                  } else {
                      layer.msg(res.data.msg);
                  }
              });
          },
          listen(data){
              this.getDate(data);
              this.$router.go({name: 'drug_pay', params: {id: data}});
          }
  
      }
  }
</script>