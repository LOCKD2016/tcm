
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">商城订单</div>
      <div class="pull-right"><a @click="exportData()" class="btn btn-sm btn-primary">导出管理</a></div>
    </div>
  </div>
  <div class="container main_warp">
    <div id="searchList" class="search_box">
      <dl>
        <dt>查询：</dt>
        <dd class="row">
          <div class="col-sm-2">
            <div class="input-group">
              <input type="search" v-model="number" placeholder="请输入订单编号查询" class="form-control auto_inp"/>
              <div v-on:click="getAlldeal()" class="input-group-btn">
                <div class="btn btn-default"><i class="icon-search"></i></div>
              </div>
            </div>
          </div>
          <div class="col-sm-2">
            <div class="input-group">
              <input type="search" v-model="mobile" placeholder="请输入手机号查询" class="form-control auto_inp"/>
              <div v-on:click="getAlldeal()" class="input-group-btn">
                <div class="btn btn-default"><i class="icon-search"></i></div>
              </div>
            </div>
          </div>
          <div class="col-sm-2">
            <div class="input-group">
              <input type="search" v-model="code" placeholder="请输入优惠码查询" class="form-control auto_inp"/>
              <div v-on:click="getAlldeal()" class="input-group-btn">
                <div class="btn btn-default"><i class="icon-search"></i></div>
              </div>
            </div>
          </div>
          <div class="col-sm-2">
            <select v-model="status" class="form-control">
              <option value="1" selected="selected">请选择支付状态</option>
              <option value="0">未付款</option>
              <option value="2">已付款</option>
              <option value="3">已取消</option>
            </select>
          </div>
          <div class="col-sm-2"><span>共 {{total}} 条记录</span></div>
        </dd>
      </dl>
    </div>
    <div class="user_table_box table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>序号</th>
            <th>订单编号</th>
            <th>药名</th>
            <th>用户昵称</th>
            <th>手机号</th>
            <th>购买类型</th>
            <th>原价</th>
            <th>实付</th>
            <th>优惠码</th>
            <th>支付方式</th>
            <th>提交时间</th>
            <th>支付状态</th>
            <th class="col-sm-1">备注</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(index,deal) in deals">
            <td>{{index+1}}</td>
            <td>{{deal.order_sn}}</td>
            <td>{{deal.goods_name}}</td>
            <td>{{deal.nickname}}</td>
            <td>{{deal.mobile}}</td>
            <td>{{deal.goods_type}}</td>
            <td>{{deal.goods_price}}</td>
            <td>{{deal.money_paid}}</td>
            <td>{{deal.promocode}}</td>
            <td>微信</td>
            <td>{{deal.created_at}}</td>
            <td v-if="deal.order_status == '已取消'">已取消</td>
            <td v-else="v-else">{{deal.pay_status}}</td>
            <td>{{deal.note}}</td>
            <td><span @click="note(deal.id)">备注</span></td>
          </tr>
        </tbody>
      </table>
      <nav>
        <ul class="pagination">
          <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur" v-on:gopage="listen"></paginate>
        </ul>
      </nav>
    </div>
    <pop-Dealnote :id.sync="id"></pop-Dealnote>
  </div>
</template>
<script type="text/javascript">
  export default {
      data(){
          return {
              deals: {},
              number: '',
              mobile: '',
              code: '',
              all: '',
              cur: '',
              departments:{},
              total:0,
              status:1,
              titles: {},
              search:{},
              id:0,
          }
      },
      created(){
          this.page = this.$route.params.id;
          this.getAlldeal();
      },
      ready(){
          headNav(1);
      },
      events: {
          update(){
              this.getAlldeal(this.cur);
          }
      },
      methods:{
          note(id){
              this.$set('id', id);
              $("#dealnote").modal("show");
          },
          getAlldeal(page=''){
              if (page) {
                  this.page = page;
              }
              this.search.number = this.number;
              this.search.mobile = this.mobile;
              this.search.status = this.status;
              this.search.code = this.code;
              this.$http({url: 'deal/list', method: 'GET', params: {page: this.page,search:this.search}}).then(function (res) {
                  this.$set('deals', res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
                  this.$set('total', pagination.total);
              })
          },
          exportData(){
              this.search.number = this.number;
              this.search.mobile = this.mobile;
              this.search.code = this.code;
              this.$http({
                  url: 'export/shop',
                  method: 'GET',
                  params: {search: this.search}
              }).then(function (res) {
                  if (res.data.status == 1) {
                      location.href = "/api/upload/download/" + res.data.name;
                  }
              })
          },
          expertDetail(id){
              location.href = "expert_detail/"+id;
          },
          expertDelete(id){
              var vue = this;
              layer.confirm('您确定删除？', {
                  btn: ['确定', '取消']
              }, function () {
                  vue.$http.delete('expert/deleteExport/' + id).then(function (res) {
                      if (res.data.status == 1) {
                          layer.msg(res.data.msg);
                          vue.$dispatch('update');
                      } else {
                          layer.msg(res.data.msg);
                      }
                  });
              }, function () {
  
              });
          },
          listen(data){
              this.getAlldeal(data);
              this.$router.go({name: 'shop_deal', params: {id: data}});
          }
      },
      watch: {
          status(){
              this.getAlldeal();
          }
      },
  
  }
</script>