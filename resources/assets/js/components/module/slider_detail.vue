
<!--增加月报-->
<template>
  <div id="slider_detail" class="modal fade">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">修改轮播</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-2 control-label">名称：</label>
              <div class="col-sm-10">
                <input type="text" v-model="val.title" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">描述：</label>
              <div class="col-sm-10">
                <input type="text" v-model="val.desc" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">链接地址：</label>
              <div class="col-sm-10">
                <input type="text" v-model="val.url" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">图片上传：
                <div style="transform: translate(150px,-40px);" class="col-sm-10">
                  <input type="file" name="image" v-on:change="uploadFile($event)"/><img v-bind:src="val.image" style="transform: translate(10px,18px);"/>
                </div>
              </label>
            </div>
            <p>上传图片尺寸大小为：640*300</p>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" @click="save()" class="btn btn-primary">保存</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      props: ['val'],
      ready(){
      },
      data(){
          return {
          };
      },
      methods: {
          save(){
              this.$http.put('slider/update', this.val).then(function (res) {
                  if (res.data.status) {
                      $('#slider_detail').modal('hide');
                      this.$dispatch('refreshList');
                  } else {
                      layer.msg(res.data.msg);
                  }
              });
          },
          uploadFile(e) {
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
                      self.val.image = 'http://static.taiheguoyi.com/' + ret.data.image_thumb_url;
                      console.log(self.val.image);
                  }
              });
  
          }
      }
  }
</script>