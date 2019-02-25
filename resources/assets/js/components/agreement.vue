
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">用户协议11111</div>
    </div>
  </div>
  <div class="container main_warp">
    <form role="form" style="margin:0 auto;" class="form-horizontal">
      <div class="form-group">
        <label class="col-sm-1 control-label">app协议：</label>
        <div style="margin-left:20px" class="col-sm-10">
          <div id="container"></div><a type="button" @click="edit(data[0].id, 'app')" class="btn btn-primary">修改</a>
        </div>
        <label class="col-sm-1 control-label">微信协议：</label>
        <div style="margin-left:20px" class="col-sm-10">
          <div id="container2"></div><a type="button" @click="edit(data[1].id, 'wechat')" class="btn btn-primary">修改</a>
          <!--sss-->
        </div>
      </div>
    </form>
    <div v-html="showPreview"></div>
  </div>
</template>
<script type="text/javascript">
  export default {
      created(){
          this.ue = UE.getEditor('container');
          this.ue2 = UE.getEditor('container2');
      },
      mounted(){
  
      },
      ready(){
          headNav(4);
  
          this.getData()
      },
      data(){
          return{
              ue: '',
              ue2: '',
              data:{},
              showPreview:''
          }
      },
      methods: {
          getData(){
              var self = this;
              self.$http({url: 'configs/agreement' ,method:'get'}).then(function(res){
                  self.$set('data', res.data.data);
                  self.ue.ready(function () {
                      self.ue.setContent(self.data[0].value);
                  })
                  self.ue2.ready(function () {
                      self.ue2.setContent(self.data[1].value);
                  })
              })
          },
          edit(id, type){
              var data = {};
              data.id = id;
              if (type == 'app') {
                  data.value = this.ue.getContent();
              }else{
                  data.value = this.ue2.getContent();
              }
              this.$http.post('configs/agreementedit', data).then(function (res) {
                  if(res.data.status){
                      layer.msg('修改成功');
                      this.getData();
                  }
              })
          }
      }
  }
</script>