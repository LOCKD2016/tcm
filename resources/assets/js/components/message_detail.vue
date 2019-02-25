
<template>
  <div class="tit_nav">
    <div class="container">
      <div class="pull-left">聊天详情：{{doctor_name}}医生 — {{user_name}}患者</div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="user_table_box table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="col-sm-1">名称</th>
            <th style="width:50%" class="col-sm-2">发送内容</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(index, val) in data">
            <td>{{ val.type | msgType }}</td>
            <td>{{ val.content.text }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default{
      components: {
  
      },
      data(){
          return {
              data: {},
              val:{},
              id: 1,
              doctor_name: '',
              user_name: '',
          };
      },
      created(){
          console.log(1111111111);
          this.id = this.$route.query.id;
          this.doctor_name = this.$route.query.doctor_name;
          this.user_name = this.$route.query.user_name;
          this.getData(this.id);
      },
      ready(){
          headNav(4);
      },
      filters:{
          msgType(val){
                 if(val == 1){
                      return '患者'
                 }else if(val == 2){
                      return '医生'
                 }else{
                      return '系统'
                 }
          }
      },
      methods: {
          getData(id){
              this.$http({url: 'message/getMessageDetail/'+id, method: 'GET'}).then(function (res) {
                  console.log(2222222222);
                  console.log(res);
                  this.$set('data', res.data.data);
              });
          },
      }
  }
</script>