
<!--增加月报-->
<template>
  <div id="addtest" class="modal fade">
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
              <div class="col-sm-10"><span v-if="ddd.type =='radio'">单选</span><span v-if="ddd.type =='checkbox'">复选</span><span v-if="ddd.type =='text'">问答</span><span v-if="ddd.type =='photo'">图片</span></div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">问题描述：</label>
              <div class="col-sm-10">
                <input type="text" v-model="ddd.title" class="form-control"/>
              </div>
            </div>
            <div style="display:none" id="photo" class="form-group">
              <label for="" class="col-sm-2 control-label">图片上传：</label>
              <div class="col-sm-10">
                <input id="test" type="file" name="file"/>
                <template v-for="val in ddd.option"><img v-bind:src="val" style="width:120px;"/>
                  <!--sss--><i @click="deletes(val)" style="color:red" class="icon-bin"></i>
                </template>
              </div>
            </div>
            <div class="clone ff">
              <div v-for="d in ddd.option" class="form-group">
                <label class="col-sm-2 control-label"><span>选项：</span><i class="icon-minus"></i><i class="icon-plus"></i></label>
                <div class="col-sm-10">
                  <input type="text" v-model="d.val" name="contentans" class="form-control"/>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">是否必填：</label>
              <div class="col-sm-10">
                <select v-model="ddd.must" class="form-control">
                  <option value="0">不必填</option>
                  <option value="1">必填</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">排序值：</label>
              <div class="col-sm-1">
                <input type="text" v-model="ddd.sort" class="form-control"/>
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
      props: ['ddd'],
      ready(){
          this.uploadFile();
      },
      data(){
          return {
              data: {},
              answer: ''
          };
      },
      watch: {
          'ddd.type': function (value) {
              if (value == 'text') {
                  $('.clone').hide();
                  $('#photo').hide();
              } else if (value == 'photo') {
                  $('.clone').hide();
                  $('#photo').show();
              } else {
                  $('.clone').show();
                  $('#photo').hide();
              }
          }
      },
      methods: {
          uploadFile(){
              var vue = this;
              layui.use('upload', function () {
                  layui.upload({
                      url: '/api/upload/add'
                      , elem: '#test'
                      , method: 'post'
                      , success: function (res) {
                          if (vue.ddd.option != null && vue.ddd.option.length == 3) {
                              layer.msg('最多上传三张示例图片');
                              return;
                          }
  
                          if(isEmpty(vue.ddd.option)){
                              vue.ddd.option = [];
                          }
                          if (vue.ddd.option == null || vue.add.option == '') {
                              vue.ddd.option = [];
                          }
                          console.log(vue.ddd.option);
                          vue.ddd.option.push(res.data);
                      }
                  });
              });
          },
          deletes(val){
              this.ddd.option.splice($.inArray(val, this.ddd.option), 1);
          },
          add(){
              if ($.trim(this.ddd.title) == '') {
                  layer.msg('请输入标题');
                  return;
              }
              if (this.ddd.type == 'radio' || this.ddd.type == 'checkbox') {
                  var data = $("form").serializeArray();
                  this.ddd.option = [];
                  for (var i = 0; i < data.length; i++) {
                      if (data[i].name == 'contentans') {
                          if ($.trim(data[i].value) != '') {
                              this.ddd.option.push(data[i].value);
                          }
                      }
                  }
              }
              this.$http.put('exam', this.ddd).then(function (res) {
                  if (res.data.status) {
                      location.reload();
                  }
              });
  
          }
      }
  }
</script>