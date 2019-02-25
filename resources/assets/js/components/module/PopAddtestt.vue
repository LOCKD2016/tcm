
<!--增加月报-->
<template>
  <div id="addtestt" class="modal fade">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">添加问题</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-2 control-label">问题类型：</label>
              <div class="col-sm-10">
                <select type="text" v-model="type" class="form-control">
                  <option value="radio" selected="selected">单选</option>
                  <option value="checkbox">复选</option>
                  <option value="text">问答</option>
                  <option value="photo">图片</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">问题描述：</label>
              <div class="col-sm-10">
                <input type="text" v-model="data.title" class="form-control"/>
              </div>
            </div>
            <div class="form-group upload display:none">
              <label for="" class="col-sm-2 control-label"><span class="notice-w">*</span>图片上传：</label>
              <div id="showImg" class="col-sm-9 clearfix">
                <input id="test" type="file" name="file"/><img v-for="val in photo" v-bind:src="val"/>
              </div>
            </div>
            <div class="clone ff">
              <div class="form-group">
                <label class="col-sm-2 control-label"><span>选项：</span><i onclick="minus(this)" class="icon-minus"></i><i onclick="plus(this)" class="icon-plus"></i></label>
                <div class="col-sm-10">
                  <input type="text" name="answer" class="form-control"/>
                </div>
              </div>
            </div>
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
      props:['id'],
      ready(){
          this.uploadFile();
      },
      data(){
          return{
              type:'radio',
              data:{},
              photo:[],
              answer:''
          };
      },
      watch:{
          type(value) {
              if(value =='text'){
                  $('.clone').hide();
                  $('.upload').hide();
              }else if(value == 'photo'){
                  $('.clone').hide();
                  $('.upload').show();
              }else{
                  $('.clone').show();
                  $('.upload').hide();
              }
          }
      },
      methods:{
          uploadFile(){
              var vue = this;
              layui.use('upload', function () {
                  layui.upload({
                      url: '/api/upload/add'
                      , elem: '#inquiry'
                      , method: 'post'
                      , success: function (res) {
                          vue.photo.push(res.data);
                      }
                  });
              });
          },
          add(){
              var data = $("form").serializeArray();
              var dd = [];
              for (var i = 0; i < data.length; i++) {
                  if (data[i].name == 'answer') {
                      if ($.trim(data[i].value) != '') {
                          dd.push(data[i].value);
                      }
                  }
              }
              for (var j = 0; j < dd.length; j++) {
                  if ($.trim(dd[j].value) != '') {
                      dd.splice(j, 1);
                  }
              }
              if ($.trim(this.data.title) == '') {
                  layer.msg('请输入标题');
                  return;
              }
              if ((this.type == 0 || this.type == 1) && dd.length == 0) {
                  layer.msg('请输入选项');
                  return;
              }
              this.data.exam_id = this.id;
              this.data.type = this.type;
  
              this.data.option = dd;
  
              if(this.type == 'photo'){
                  this.data.option = this.photo;
              }
  
              console.log(this.data)
              return ;
              this.$http.post('questionInfo',this.data).then(function (res) {
                  layer.msg(res.data.msg);
                  if(res.data.status ==1){
                      this.data = {};
                      $('.ff>.form-group:last').siblings().remove();
                      $('.ff>.form-group:last').find("input").val("");
                      this.$dispatch("questionsave");
                      $('#addtestt').modal('hide');
                  }
              });
          }
      }
  }
</script>