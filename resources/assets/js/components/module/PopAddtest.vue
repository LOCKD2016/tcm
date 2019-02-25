
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
            <div id="photos" style="display:none" class="form-group">
              <label for="" class="col-sm-2 control-label">图片上传：</label>
              <div class="col-sm-10">
                <input id="tests" type="file" name="file"/>
                <template v-for="val in photo"><img v-bind:src="val" style="width:120px"/><i @click="deletes(val)" class="icon-bin"></i></template>
              </div>
            </div>
            <div class="clone ff">
              <div class="form-group">
                <label class="col-sm-2 control-label"><span>选项：</span><i class="icon-minus"></i><i class="icon-plus"></i></label>
                <div class="col-sm-10">
                  <input type="text" name="answer" class="form-control"/>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">是否必填：</label>
              <div class="col-sm-10">
                <select name="must" class="form-control">
                  <option value="0">不必填</option>
                  <option value="1">必填</option>
                </select>
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
      data(){
          return{
              type:'radio',
              data:{},
              photo:[],
              answer:''
          };
      },
      ready(){
          this.uploadFile();
      },
      watch:{
          type(value) {
              if(value =='text'){
                  $('.clone').hide();
                  $('#photos').hide();
              }else if(value == 'photo'){
                  $('.clone').hide();
                  $('#photos').show();
              }else{
                  $('.clone').show();
                  $('#photos').hide();
              }
          }
      },
      methods:{
          deletes(val){
              this.photo.splice($.inArray(val, this.photo), 1);
          },
          uploadFile(){
              var vue = this;
              layui.use('upload', function () {
                  layui.upload({
                      url: '/api/upload/add'
                      , elem: '#tests'
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
  
              this.data.exam_id = this.id;
              this.data.type = this.type;
              this.data.option = dd;
              if(this.type == 'photo'){
                  this.data.option = this.photo;
              }
              if(this.type == 'text'){
                  this.data.option = [];
              }
              this.$http.post('exam',this.data).then(function (res) {
                  layer.msg(res.data.msg);
                  if(res.data.status ==1){
                      location.reload();
                  }
              });
          }
      }
  }
</script>