
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">发货列表</div>
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
              <input id="seaItem" type="search" v-model="mobile" placeholder="输入手机号搜索" class="form-control auto_inp"/>
              <div @click="getSend()" class="input-group-btn">
                <div class="btn btn-default"><i class="icon-search"></i></div>
              </div>
            </div>
          </div>
          <div class="col-sm-2">
            <div class="input-group">
              <input type="search" v-model="number" placeholder="输入订单编号搜索" class="form-control auto_inp"/>
              <div @click="getSend()" class="input-group-btn">
                <div class="btn btn-default"><i class="icon-search"></i></div>
              </div>
            </div>
          </div>
          <div class="col-sm-2">
            <select v-model="city" name="city" class="form-control first">
              <option value="0" selected="selected">请选择地区</option>
              <option v-for="city in cities" track-by="$index" v-bind:value="city.province">{{city.province}}</option>
            </select>
          </div>
          <div class="col-sm-2">
            <select v-model="status" class="form-control">
              <option value="0" selected="selected">请选择购买类型</option>
              <option value="1">成人男</option>
              <option value="2">成人女</option>
              <option value="3">儿童</option>
            </select>
          </div>
          <div class="col-sm-2">
            <select v-model="shipping_status" class="form-control">
              <option value="5" selected="selected">请选择发货状态</option>
              <option value="0">未发货</option>
              <option value="1">已发货</option>
            </select>
          </div>
        </dd>
      </dl>
      <dl>
        <dt>查询：</dt>
        <dd class="row">
          <div style="width:500px;" class="col-sm-2">
            <input style="width:200px" type="text" readonly="readonly" name="startTime" @click.stop="open($event,'picker2')" v-model="calendar.items.picker2.value" placeholder="请输入开始时间" class="form-control pull-left time_date"/><span class="pull-left zhi"> --</span>
            <input style="width:200px" type="text" readonly="readonly" name="endTime" @click.stop="open($event,'picker3')" v-model="calendar.items.picker3.value" placeholder="请输入结束时间" class="form-control pull-left time_date"/>
            <calendar :show.sync="calendar.show" :type="calendar.type" :value.sync="calendar.value" :x="calendar.x" :y="calendar.y" :begin.sync="calendar.begin" :end.sync="calendar.end" :range.sync="calendar.range" :months="calendar.months"></calendar>
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
            <th>药名</th>
            <th>敷贴类型</th>
            <th>敷贴数量</th>
            <th>购买类型</th>
            <th>订单编号</th>
            <th>用户昵称</th>
            <th>收货人</th>
            <th>联系方式</th>
            <th class="col-sm-2">收货地址</th>
            <th>发货状态</th>
            <th>快递单号</th>
            <!--th.col-sm-1 用户备注-->
            <th class="col-sm-1">管理员备注</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(index,send) in sends">
            <td>{{index+1}}</td>
            <td>{{send.goods_name}}</td>
            <td>{{send.type}}</td>
            <td>{{send.num}}</td>
            <td>{{send.goods_type}}</td>
            <td>{{send.order_sn}}</td>
            <td>{{send.nickname}}</td>
            <td>{{send.consignee}}</td>
            <td>{{send.mobile}}</td>
            <td>{{send.province}}{{send.city}}{{send.district}}{{send.address}}</td>
            <td>{{send.shipping_status}}</td>
            <td>{{send.express_number}}</td>
            <!--td {{send.postscript}}-->
            <td>{{send.note}}</td>
            <td><span v-if="send.shipping_status == '未发货'" @click="addlogistics(send.id)">添加物流</span>
              <!--span(v-else,@click="allogistics(send.id)") 物流进度--><span v-if="send.shipping_status == '已发货'" @click="updateLogistics(send.id)">修改物流</span>
              <!--span.del(@click="SendDelete(send.id)") 删除--><span @click="noteAdd(send.id)">备注</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur" v-on:gopage="listen"></paginate>
    <pop-addlogistics :id.sync="id"></pop-addlogistics>
    <!--pop-allogistics(:id.sync="id")-->
    <pop-logisticsupdate :id.sync="id"></pop-logisticsupdate>
    <pop-Dealnote :id.sync="id"></pop-Dealnote>
  </div>
</template>
<script type="text/javascript">
  import calendar from "../../js/calendar.vue"//
  export default {
      components: {
          calendar
      },
      created(){
          this.page = this.$route.params.id;
          this.getSend();
          this.getCity();
      },
      ready(){
          headNav(3);
      },
      data(){
          return {
              sends:{},
              cur:'',
              all:'',
              total:'',
              mobile: '',
              status: '',
              number: '',
              startTime:'',
              endTime:'',
              city: 0,
              shipping_status: 5,
              search: {},
              id:'',
              cities:{},
              express_company:'',
              calendar: {
                  show: false,
                  x: 0,
                  y: 0,
                  picker: "",
                  type: "date",
                  value: "",
                  begin: "",
                  end: "",
                  //weeks: [],
                  months: [],
                  range: false,
                  items: {
                      // 日期时间模式、、
                      picker3: {
                          type: "datetime",
                          value: "",
                          sep: "-",
                      }, picker2: {
                          type: "datetime",
                          value: "",
                          sep: "-",
                      },
                  }
              }
          };
      },
      events: {
          update(){
              this.getSend();
          }
      },
      methods:{
          noteAdd(id){
              this.$set('id', id);
              $("#dealnote").modal("show");
          },
          getSend(page=''){
              if (page) {
                  this.page = page;
              }
              this.search.number = this.number;
              this.search.mobile = this.mobile;
              this.search.city = this.city;
              this.search.status = this.status;
              this.search.shipping_status = this.shipping_status;
              this.search.startTime = this.startTime;
              this.search.endTime = this.endTime;
              this.$http({url: 'deal/send', method: 'GET', params: {page: this.page,search:this.search}}).then(function (res) {
                  this.$set('sends', res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
                  this.$set('total', pagination.total);
              });
          },
          getCity(){
              this.$http({
                  url: 'deal/sendcity',
                  method: 'GET',
              }).then(function (res) {
                  this.$set('cities', res.data);
              });
          },
          exportData(){
              this.search.number = this.number;
              this.search.mobile = this.mobile;
              this.search.city = this.city;
              this.search.status = this.status;
              this.$http({
                  url: 'export/send',
                  method: 'GET',
                  params: {search: this.search}
              }).then(function (res) {
                  if (res.data.status == 1) {
                      location.href = "/api/upload/download/" + res.data.name;
                  }
              })
          },
          addlogistics(id){
              this.$set('id', id);
              $("#addlogistics").modal("show");
          },
          allogistics(id){
              this.$set('id', id);
              $("#allogistics").modal("show");
          },
          updateLogistics(id){
              this.$set('id', id);
              $("#logisticsupdate").modal("show");
          },
          listen(data){
              this.getSend(data);
              this.$router.go({name: 'send_list', params: {id: data}});
          },
          open(e, type) {
              // 设置类型123
              this.calendar.picker = type;
              this.calendar.type = this.calendar.items[type].type;
              this.calendar.range = this.calendar.items[type].range;
              this.calendar.begin = this.calendar.items[type].begin;
              this.calendar.end = this.calendar.items[type].end;
              this.calendar.value = this.calendar.items[type].value;
              // 可不用写
              this.calendar.sep = this.calendar.items[type].sep;
              this.calendar.weeks = this.calendar.items[type].weeks;
              this.calendar.months = this.calendar.items[type].months;
  
              this.calendar.show = true;
              this.calendar.x = e.target.offsetLeft;
              this.calendar.y = e.target.offsetTop + e.target.offsetHeight + 8;
          }
      },
      watch: {
          city(){
              this.getSend();
          },
          status(){
              this.getSend();
          },
          shipping_status(){
              this.getSend();
          },
          startTime(){
              this.getSend();
          },
          endTime(){
              this.getSend();
          },
          'calendar.items.picker2.value': function (newvalue, oldValue) {
              if (newvalue != oldValue) {
                  this.$set('startTime', newvalue);
              }
          },
          'calendar.items.picker3.value': function (newvalue, oldValue) {
              if (newvalue != oldValue) {
                  this.$set('endTime', newvalue);
              }
          },
          'calendar.value': function (value) {
              this.calendar.items[this.calendar.picker].value = value
          }
      }
  }
</script>