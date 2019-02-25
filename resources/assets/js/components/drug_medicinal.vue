
<template>
  <div class="container main_warp">
    <div class="tit_nav">
      <div class="container clearfix">
        <div class="pull-left">药品订单管理1</div>
        <div class="pull-right"><a @click="exportData()" class="btn btn-sm btn-primary">导出管理</a></div>
      </div>
    </div>
    <div class="search_box">
      <dl>
        <!--dt 筛选-->
        <dd class="row">
          <div class="col-sm-1"> 订单编号</div>
          <div class="col-sm-2">
            <div class="input-group">
              <input type="text" v-model="search.order_sn" placeholder="" class="form-control auto_inp"/>
            </div>
          </div>
          <div class="col-sm-1"> 患者</div>
          <div class="col-sm-1">
            <div class="input-group">
              <input type="text" v-model="search.user_name" placeholder="" class="form-control auto_inp"/>
            </div>
          </div>
          <!--.col-sm-1  医师-->
          <!--.col-sm-2
          .input-group
              input.form-control.auto_inp(type="text" v-model="search.doc_name" placeholder="")
          -->
        </dd>
      </dl>
      <dl>
        <dd class="row">
          <div class="col-sm-1"> 付款方式</div>
          <div class="col-sm-1">
            <select v-model="search.pay_type" @change="getDate(1)" class="form-control">
              <option value="">不限</option>
              <option value="0">未支付</option>
              <option value="1">微信支付</option>
            </select>
          </div>
          <div class="col-sm-1"> 付款时间</div>
          <div class="col-sm-2">
            <div class="input-group">
              <input type="date" v-model="search.pay_time" @change="getDate(1)" class="form-control auto_inp"/>
            </div>
          </div>
          <!--.col-sm-1  来源-->
          <!--.col-sm-1
          select.form-control(v-model="search.source")
              option(value=0) 不限
              option(value=1) 网诊
              option(value=2) 门诊
          -->
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
            <th class="col-sm-1">患者</th>
            <th class="col-sm-1">就诊医师</th>
            <th class="col-sm-1">收费项目</th>
            <th class="col-sm-1">应收</th>
            <th class="col-sm-1">实收</th>
            <th class="col-sm-1">付款方式</th>
            <th style="width:12%;" class="col-sm-1">付款时间</th>
            <th class="col-sm-1">退款金额</th>
            <th class="col-sm-1">状态</th>
            <th class="col-sm-1">备注</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(index,val) in data">
            <td> {{val.order_sn}}</td>
            <td>{{val.clinic.appuser}}</td>
            <td>{{val.clinic.doctor}}</td>
            <td> {{val.order_type}}</td>
            <td>{{val.payable_amount}}</td>
            <td> {{val.pay_amount}}</td>
            <td> {{val.pay_type}}</td>
            <td> {{val.pay_time}}</td>
            <td> {{val.refund_amount}}</td>
            <td> {{val.status}}</td>
            <td>{{val.note}}</td>
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
          return{
              data:{},
              cur: 0,
              all: 0,
              total: 0,
              search: {
                  order_type: 'recipe',
                  pay_type: '',
                  source: 0,
                  cur_total:10
              },
          }
      },
      events: {
          update(){
              this.getDate();
          }
      },
      methods:{
          getDate(page=''){
              if(page){
                  this.page = page;
              }
              this.$http({url:'order/prescription',method:'GET',params:{page:this.page,search:this.search}}).then(function (res) {
                  this.$set('data',res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
                  this.$set('total', pagination.total);
              })
          },
          exportData(){
              var title = '药费订单';
              var head = [
                  '订单编号',
                  '患者',
                  '就诊医师',
                  '订单类型',
                  '应收',
                  '实收',
                  '付款方式',
                  '付款时间',
                  '退款金额',
                  '状态',
                  '备注'
              ];
              var width = {'A': 20, 'B': 10, 'C': 10, 'D': 10, 'E': 10, 'F': 10, 'G': 10, 'H': 20, 'I': 10,'J':10,'K':20};
              this.$http({
                  url: 'exports/exports',
                  method: 'post',
                  params: {title: title, head: head, search: this.search,type: 'drug_medicinal', width: width}
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
              this.$router.go({name: 'drug_medicinal', params: {id: data}});
          }
  
      }
  }
</script>