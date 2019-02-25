
<!--覆盖-->
<template>
  <div id="authsave" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">修改权限</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">当前位置：</label>
              <div class="col-sm-8"><span>{{auth.fname}}</span></div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">更换位置：</label>
              <div class="col-sm-8">
                <select v-model="auth.pid">
                  <option selected="selected" value="0">==第一级权限类==</option>
                  <option v-for="oneAuth in oneAuths" v-bind:value="oneAuth.id">{{oneAuth.path}} {{oneAuth.name}}</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">权限名：</label>
              <div class="col-sm-8">
                <input type="text" v-model="auth.name" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">展示名：</label>
              <div class="col-sm-8">
                <input type="text" v-model="auth.display_name" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">描述：</label>
              <div class="col-sm-8">
                <input type="text" v-model="auth.description" class="form-control"/>
              </div>
            </div>
          </form>
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
            <button type="button" v-on:click="save(auth)" class="btn btn-primary">保存</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  import {errorMsg} from '../../common.js';
  export default {
      props: ['id'],
      ready(){
          this.getAuth(this.id);
      },
      data(){
          return {
              auth: {},
              oneAuths:[],
          };
      },
      methods: {
          getAuth(uid){
              if (uid) {
                  this.$http.get('auth/'+uid).then(function(res){
                     this.$set('auth',res.data.permission);
                      $("#authsave").modal("show");
                  });
                  this.$http.get('auth/0').then(function (res) {
                      this.$set('oneAuths', res.data);
                  });
  
              }
          },
          save(auth){
              if(auth.id == auth.pid){
                  layer.msg('不能归属于自己！');return;
              }
           this.$http.put('auth/'+auth.id,auth).then(function(res){
                  layer.msg(res.data.msg);
                  this.$dispatch("refreshList");
                  $('#authsave').modal('hide');
              }, function (res) {
                  var data = res.data;
                  errorMsg(data.errors);
              })
          }
      },
      watch: {
          id(newValue, oldValue){
              this.getAuth(newValue);
          }
      }
  }
</script>