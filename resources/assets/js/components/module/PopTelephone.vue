
<template>
  <div id="telephone" class="modal fade">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">添加客服</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-2 control-label">诊所：</label>
              <div class="col-sm-4">
                <select v-model="user.clinique_id" class="form-control">
                  <option v-for="c in cliniques" v-bind:value="c.id">{{c.name}}</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">姓名：</label>
              <div class="col-sm-4">
                <input type="text" v-model="user.kname" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">手机号：</label>
              <div class="col-sm-4">
                <input type="text" v-model="user.telephone" class="form-control"/>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
          <button type="button" v-on:click="addUser(user)" class="btn btn-primary">添加</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  import {errorMsg} from '../../common.js';
  export default {
      created() {
          this.getClinique();
      },
      data(){
          return {
              user: {},
              cliniques: {}
          };
      },
      methods: {
          addUser(user){
              if(!user.clinique_id){
                  layer.msg('请选择诊所');
                  return false;
              }
              if (!user.kname) {
                  layer.msg('请输入客服姓名');
                  return false;
              }
              if (!user.telephone) {
                  layer.msg('请输入客服手机号');
                  return false;
              }
              this.$http.post('tel/addTelephone', user).then(function (res) {
                  if (res.data.status) {
                      $("#telephone").modal("hide");
                      layer.msg(res.data.msg);
                      this.$dispatch('telphone');
                  } else {
                      layer.msg(res.data.msg);
                  }
              },function(res){
                  var data = res.data;
                  errorMsg(data.errors);
              });
          },
          getClinique() {
              this.$http.get('clinique/index').then(function (res) {
                  if (res.data.status) {
                      this.cliniques = res.data.data;
                  }
              })
          }
  
      }
  }
</script>