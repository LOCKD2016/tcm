
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">方案统计</div>
      <div class="pull-right"><a @click="exports()" class="btn btn-primary btn-sm"><i class="icon-plus"></i><span>导出表格</span></a></div>
    </div>
  </div>
  <div class="container main_warp">
    <dl>
      <dd class="row">
        <div class="form-group">
          <div style="float: left;margin-left: 10px;line-height: 30px;">商品:</div>
          <div class="col-sm-2">
            <select v-model="good_id" class="form-control">
              <option v-bind:value="1">至阳三伏贴</option>
              <option v-bind:value="2">个性三伏贴</option>
            </select>
          </div>
        </div>
      </dd>
    </dl>
    <div class="user_table_box table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="col-sm-1">序号</th>
            <th class="col-sm-1">商品</th>
            <th class="col-sm-1">医生</th>
            <th class="col-sm-1">成人男</th>
            <th class="col-sm-1">成人女</th>
            <th class="col-sm-1">儿童</th>
            <th class="col-sm-1">总计</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(i,val) in data">
            <td>{{i+1}}</td>
            <td>{{val.goods_name}}</td>
            <td>{{val.doctor_name}}</td>
            <td>{{val.sum_man}}</td>
            <td>{{val.sum_women}}</td>
            <td>{{val.sum_child}}</td>
            <td>{{val.total}}</td>
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
      },
      ready(){
          headNav(2);
      },
      data(){
          return {
              data: {},
              cur: 0,
              all: 0,
              title: ['序号','商品','医生','成人男','成人女','儿童','总计'],//2
              good_id: 1
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
              },
              deep: true
          }
      },
      events:{
          lnquirycount(){
              this.getData();
          }
      },
      methods: {
          getData(){
              this.$http({url: 'count/lnquiry', method: 'GET', params: {time: this.time,goods_id: this.good_id}}).then(function (res) {
                  if(res.data.errcode !=200){
                      layer.msg(res.data.msg);
                      return ;
                  }
                  this.$set('data', res.data.data);
              });
          },
          exports(){
              var title = '三伏贴方案统计';
              if(this.good_id ==1){
                  title = '至阳'+ title;
              }else{
                  title = '个性'+ title;
              }
              var width = {'A':10,'B':10,'C':10,'D':10,'E':10,'F':10,'G':10};
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