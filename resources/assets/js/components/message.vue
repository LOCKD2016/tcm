
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">诊所管理</div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="item_list">
      <div class="list">
        <div class="user_table_box table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>序号</th>
                <th>医生姓名</th>
                <th>患者姓名</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="val in data">
                <td>{{val.name}}</td>
                <td>12</td>
                <td>{{val.telephone}}</td>
                <td><span @click="save(val)">查看</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <clinique :val.sync="val"></clinique>
    </div>
  </div>
</template>
<script type="text/javascript">
  import clinique from  './module/clinique.vue'
  export default {
      components:{
          clinique
      },
      data(){ //页面用到的数据
          return {
              cur:0,
              all:0,
              data:{},
              val:{
                  content:{}
              }
          };
      },
      created(){//实例创建后调用
          this.getData();
      },
      events: {
          userupdate(){
              this.getData();
          }
      },
      methods:{
          getData(){
              this.$http({url:'message/getMessage',method:'GET'}).then(function(res){
                  this.$set('data', res.data.data);
              });
          },
          save(val){
              this.$set('val', val);
              $("#clinique").modal("show");
          }
      }
  }
</script>