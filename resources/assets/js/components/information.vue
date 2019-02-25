
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">数据管理</div>
    </div>
  </div>
  <div class="container main_warp">
    <label>评论</label>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>痊愈</th>
          <th>明显好转</th>
          <th>好转</th>
          <th>没变化</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>{{com.info.N1}}</td>
          <td>{{com.info.N2}}</td>
          <td>{{com.info.N3}}</td>
          <td>{{com.info.N4}}</td>
          <td><span v-on:click="edit_com(com)">修改</span></td>
        </tr>
      </tbody>
    </table>
    <label>预约</label>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>免费取消次数</th>
          <th>免费取消预约时间N(小时)</th>
          <th>24h < 实际时间 < N</th>
          <th>12h < 实际时间 < 24</th>
          <th>12h > 实际时间</th>
          <th>实际时间 > 预约时间</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>{{sub.info.M}}</td>
          <td>{{sub.info.N}}</td>
          <td>{{sub.info.N1}}</td>
          <td>{{sub.info.N2}}</td>
          <td>{{sub.info.N3}}</td>
          <td>{{sub.info.N4}}</td>
          <td><span v-on:click="edit_com(sub)">修改</span></td>
        </tr>
      </tbody>
    </table>
    <label>邮费</label>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>地区</th>
          <th>首价(元)</th>
          <th>首重(Kg)</th>
          <th>续重(元/Kg)</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="val in area">
          <td>{{val.name}}</td>
          <td>{{val.initiate_price}}</td>
          <td>{{val.initiate_weight}}</td>
          <td>{{val.continue_price}}</td>
          <td><span v-on:click="edit_area(val)">修改</span></td>
        </tr>
      </tbody>
    </table>
    <nav>
      <ul class="pagination">
        <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur" v-on:gopage="listen"></paginate>
      </ul>
    </nav>
    <information :val.sync="val"></information>
    <area :area_val.sync="area_val"/>
  </div>
</template>
<script type="text/javascript">
  import information from "./module/information.vue"
  import area from "./module/area.vue"
  export default {
      components: {
          information,
          area
      },
      created(){
          this.getData();
          this.getArea(1);
      },
      ready(){
          headNav(5);
      },
      data(){
          return{
              sub:{},
              com:{},
              area:{},
              area_val:{},
              cur:0,
              all:0,
              val:{}
          }
      },
      events: {
          refreshList(){
              this.getData();
          }
      },
      methods: {
          edit_area(val){
              this.area_val = val;
              $('#area').modal('show');
          },
          getArea(page){
              this.$http({url:'area',params:{page:page}}).then(function(res){
                  this.$set('area',res.data.data.data);
                  this.$set('cur', res.data.data.current_page);
                  this.$set('all', res.data.data.last_page);
              });
          },
          edit_com(val){
              this.val = val;
              $('#information').modal("show");
          },
          getData(){
              this.$http({url: 'config' ,method:'get'}).then(function(res){
                  this.$set('com',res.data.data[0]);
                  this.$set('sub',res.data.data[1]);
              })
          },
          listen(data){
              this.getArea(data);
              //this.$router.go({name: 'comment_admin', params: {id: data}});
          }
      }
  }
</script>