
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">经营统计</div>
      <!--.pull-right-->
      <!--    a.btn.btn-primary.btn-sm(@click="exports()")-->
      <!--        i.icon-plus-->
      <!--        span 导出表格-->
    </div>
  </div>
  <div class="container main_warp">
    <div class="user_table_box table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="col-sm-1">平台总收入</th>
            <th class="col-sm-1">门诊总收入</th>
            <th class="col-sm-1">在线咨询总收入</th>
            <th class="col-sm-1">药方总收入</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{count.total}}</td>
            <td>{{count.clinic_amount}}</td>
            <td>{{count.net_amount}}</td>
            <td>{{count.recipe_amount}}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <dl>
      <dd class="row clearfix">
        <div style="margin-bottom:0px;float:left;" class="form-group clearfix">
          <div style="float:left;line-height:30px;padding-left:30px;"><span></span>开始时间：</div>
          <div style="width:60%" class="col-sm-2">
            <input type="date" v-model="time.startTime" class="form-control"/>
          </div>
        </div>
        <div style="margin-bottom:0px;float:left;" class="form-group clearfix">
          <div style="float:left;line-height:30px;padding-left:30px;"><span></span>结束时间：</div>
          <div style="width:60%" class="col-sm-2">
            <input type="date" v-model="time.endTime" class="form-control"/>
          </div>
        </div>
      </dd>
    </dl>
    <div class="user_table_box table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="col-sm-1">日期</th>
            <th class="col-sm-1">预约人数</th>
            <th class="col-sm-1">接诊数</th>
            <!--th.col-sm-1 转诊数-->
            <th class="col-sm-1">现场看诊数</th>
            <th class="col-sm-1">远程问诊数</th>
            <th class="col-sm-1">抓药数</th>
            <th class="col-sm-1">代煎量</th>
            <th class="col-sm-1">快递量</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="val in data">
            <td>{{val.time}}</td>
            <td>{{val.sub}}</td>
            <td>{{val.accept}}</td>
            <!--td {{val.transfer}}-->
            <td>{{val.people}}</td>
            <td>{{val.net}}</td>
            <td>{{val.medicinal}}</td>
            <td>{{val.help}}</td>
            <td>{{val.express}}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      created(){
          this.getCount();
          this.getData();
      },
      ready(){
          headNav(2);
      },
      data(){
          return {
              count: {},
              data: {},
              cur: 0,
              all: 0,
              title:[
                 '日期',
                 '预约人数',
                 '接诊数',
                 '转诊数',
                 '现场看诊数',
                 '远程问诊数',
                 '抓药数',
                 '代煎量',
                 '快递量'
              ],
              time:{
                  startTime: '',
                  endTime: ''
              },
          }
      },
      watch: {
          time: {
              handler: function (val, oldVal) {
                  this.getData();
              },
              deep: true
          }
      },
      events:{
          update(){
              this.getData();
          }
      },
      methods: {
          getCount(){
              this.$http({url: 'count/deal', method: 'GET'}).then(function (res) {
                  this.$set('count', res.data.data);
              })
          },
          getData(){
              this.$http({url: 'count/manage', method: 'GET', params: {time:this.time}}).then(function (res) {
                  if(res.data.errcode !=200){
                      layer.msg(res.data.msg);
                      return ;
                  }
                  this.$set('data', res.data.data);
              });
          },
          exports(){//
              var title = '经营统计';
              var width = {'A':10,'B':10,'C':10,'D':10,'E':10,'F':10,'G':10,'H':10,'I':10};
              this.$http({
                  url: 'count/exports',
                  method: 'GET',
                  params: {title:title,head: this.title,data:this.data,width:width}
              }).then(function (res) {
                  if(res.data.errcode ==200){
                      location.href = "/api/upload/download/" + res.data.data;
                  }else{
                      layer.msg(res.data.msg);
                  }
              });
          }
      }
  }
</script>