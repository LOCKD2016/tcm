
<!--增加月报-->
<template>
  <div id="slider_add" class="modal fade">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">添加轮播</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-2 control-label">名称：</label>
              <div class="col-sm-10">
                <input type="text" v-model="data.title" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">描述：</label>
              <div class="col-sm-10">
                <input type="text" v-model="data.desc" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">链接地址：</label>
              <div class="col-sm-10">
                <input type="text" v-model="data.url" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">图片上传：</label>
              <div class="col-sm-10">
                <input type="file" name="image" v-on:change="uploadFile($event)"/><img v-bind:src="filePath"/>
              </div>
            </div>
            <p>上传图片尺寸大小为：640*300</p>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" @click="add()" class="btn btn-primary">保存</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      ready(){
      },
      data(){
          return {
              data: {},
              filePath:''
          };
      },
      methods: {
          add: function () {
              this.data.image = this.filePath;
              this.$http.post('slider/add',this.data).then(function (res) {
                  if(res.data.status){
                      $('#slider_add').modal('hide');
                      this.filePath = '';
                      this.data = {};
                      this.$dispatch('refreshList');
                  }else{
                      layer.msg(res.data.msg);
                  }
              });
          },
          uploadFile(e){
              var self = this;
              //dosomthing
              var that = e.target;
              var fd = new FormData();
              fd.append("image", that.files[0]);
              $.ajax({
                  url: "/api/upload/qiniu",
                  type: "POST",
                  processData: false,
                  contentType: false,
                  data: fd,
                  success: function (ret) {
                      console.log(ret.data.image_thumb_url);
                      self.filePath = 'http://static.taiheguoyi.com/'+ret.data.image_thumb_url;
                      console.log(self.filePath);
                  }
              });
  
          }
      }
  }
</script>