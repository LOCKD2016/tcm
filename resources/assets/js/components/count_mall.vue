
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">商城统计</div>
      <div class="pull-right"><a @click="exports()" class="btn btn-primary btn-sm"><i class="icon-plus"></i><span>导出表格</span></a></div>
    </div>
  </div>
  <div class="container main_warp">
    <dl>
      <dd class="row">
        <div class="form-group">
          <div style="margin-left: 10px;float: left;line-height: 30px;">商品:</div>
          <div class="col-sm-2">
            <select v-model="good_id" class="form-control">
              <option v-bind:value="1">至阳三伏贴</option>
              <option v-bind:value="2">个性三伏贴</option>
            </select>
          </div>
        </div>
      </dd>
      <dd style="margin:10px 0" class="row">
        <div class="form-group">
          <div style="float: left;line-height: 30px;">开始时间:</div>
          <div class="col-sm-2">
            <input type="date" v-model="time.startTime" class="form-control"/>
          </div>
          <div style="margin-left: 10px;float: left;line-height: 30px;">结束时间:</div>
          <div class="col-sm-2">
            <input type="date" v-model="time.endTime" class="form-control"/>
          </div>
          <div style="margin-left: 10px;float: left;line-height: 30px;">总计:</div>
          <div style="line-height:30px" class="col-sm-2"><span>共 {{total_income}} 元</span></div>
        </div>
      </dd>
    </dl>
    <div class="user_table_box table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="col-sm-1">序号</th>
            <th class="col-sm-1">日期</th>
            <th class="col-sm-1">商品</th>
            <th class="col-sm-1">当天收入(元)</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(i,val) in data">
            <td>{{i+1}}</td>
            <td>{{val.time}}</td>
            <td>{{val.goods_name}}</td>
            <td>{{val.income}}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      created(){
          this.getData();
          this.getTotalIncome(1);
      },
      ready(){
          headNav(2);
      },
      data(){
          return {
              data: {},
              cur: 0,
              all: 0,
              title: ['日期','商品','当天收入(元)'],
              time:{
                  startTime: '',
                  endTime: ''
              },
              good_id: 1,
              total_income: 0
          }
      },
      watch:{
          time: {
              handler: function (val) {
                  this.getData();
              },
              deep: true
          },
          good_id: {
              handler: function (val) {
                  this.getData();
                  this.getTotalIncome(val);
              },
              deep: true
          }
      },
      events:{
          mallcount(){
              this.getData();
          },
          malltotal(){
              this.getTotalIncome();
          }
      },
      methods: {
          getData(){
              this.$http({url: 'count/mall', method: 'GET', params: {time: this.time,good_id: this.good_id}}).then(function (res) {
                  if(res.data.errcode !=200){
                      layer.msg(res.data.msg);
                      return ;
                  }
                  this.$set('data', res.data.data);
              });
          },
          getTotalIncome(id){
            this.$http.get('count/total/'+id).then(function (res){
                  this.total_income = res.data.data;
            });
          },
          exports(){
              var title = '三伏贴经营统计';
              if (this.good_id == 1) {
                  title = '至阳' + title;
              } else {
                  title = '个性' + title;
              }
              var width = {'A':10,'B':10,'C':10,'D':10};
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