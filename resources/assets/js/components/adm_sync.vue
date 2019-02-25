
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">数据同步</div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="box_css">
      <div class="syt">
        <form role="form" class="form-horizontal">
          <div class="form-group">
            <div class="col-sm-10">
              <!--label.la(@click="enter('inquiry')")
              i.icon-folder-download
              | 测试队列
              -->
              <label @click="enter('doctor')" class="la"><i class="icon-folder-download"></i>同步医生数据</label>
              <label @click="enter('scheduling')" class="la"><i class="icon-folder-download"></i>同步医生排班</label>
              <label @click="enter('clinique')" class="la"><i class="icon-folder-download"></i>同步诊所信息</label>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      ready(){
          headNav(5);
      },
      data(){
          return{
  
          }
      },
  
      methods: {
  
          enter(route){
              var _this = this;
              var confirm = layer.confirm('您确定您的操作吗？', {
                  btn: ['确定', '取消'],
                  skin: 'layui-layer-molv'
              }, function () {
                  _this.doctor_sync(route);
                  layer.close(confirm);
              }, function () {
  
              });
          },
          doctor_sync:function (route) {
              this.$http.get('sync/'+route).then(function(res){
  
              })
          }
      }
  }
</script>