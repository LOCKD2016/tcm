
<template>
  <div id="save_appuser" class="modal fade">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">修改信息</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-2 control-label">真实姓名：</label>
              <div class="col-sm-10">
                <input type="text" v-model="item.realname" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">手机号：</label>
              <div class="col-sm-10">
                <input type="text" v-model="item.mobile" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">生日：</label>
              <div class="col-sm-10">
                <input type="date" v-model="item.birthday" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">性别：</label>
              <div class="col-sm-10">
                <select type="text" v-model="item.sex" class="form-control">
                  <option selected="selected">未知</option>
                  <option>男</option>
                  <option>女</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">身高：</label>
              <div class="col-sm-10">
                <input type="number" v-model="item.height" placeholder="cm" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">体重：</label>
              <div class="col-sm-10">
                <input type="number" v-model="item.weight" placeholder="kg" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">身份证号：</label>
              <div class="col-sm-10">
                <input type="number" v-model="item.pincode" class="form-control"/>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
          <button type="button" v-on:click="sub()" class="btn btn-primary">添加</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      props: ['item'],
      data(){
          return {
              item: {},
          };
      },
      computed: {
          _sex: function () {
              return this.item.sex == '未知' ? 0 : (this.item.sex == '男' ? 1 : 2)
          }
      },
      methods: {
          sub(){
              this.$http.post('appuser/edit', this.item).then(function (res) {
                  if(res.data.status){
                      this.$dispatch("refreshList");
                      $('#save_appuser').modal('hide');
                  }
              }, function (res) {
  
              });
          },
      }
  }
</script>