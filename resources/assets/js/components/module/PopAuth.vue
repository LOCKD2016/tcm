
<!--覆盖-->
<template>
  <div id="auth" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">添加权限</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">选择权限等级：</label>
              <div class="col-sm-8">
                <select v-model="auth.pid" class="form-control">
                  <option selected="selected" value="0">==第一级权限类==</option>
                  <option v-for="oneAuth in oneAuths" v-bind:value="oneAuth.id">{{oneAuth.path}} {{oneAuth.name}}</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">展示名：</label>
              <div class="col-sm-8">
                <input type="text" v-model="auth.display_name" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">权限名：</label>
              <div class="col-sm-8">
                <input type="text" v-model="auth.name" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">描述：</label>
              <div class="col-sm-8">
                <input type="text" v-model="auth.description" class="form-control"/>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
          <button type="button" v-on:click="Add(auth)" class="btn btn-primary">保存</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  import {errorMsg} from '../../common.js';
  export default {
      ready(){
          this.getOneAuth();
      },
      data(){
          return {
              oneAuths:[],
              auth:{}
          };
      },
      //aaaa
      methods:{
          getOneAuth(){
              this.$http.get('auth/0').then(function(res){
                  this.$set('oneAuths',res.data);
              })
          },
          Add(auth){
              this.$http.post('auth',auth).then(function(res){
                  layer.msg(res.data.msg);
                  this.getOneAuth();
                  this.$set('auth.display_name','');
                  this.$set('auth.name','');
                  this.$set('auth.description','');
                  this.$set('auth.pid',0);
                  this.$dispatch('count');
              },function (res) {
                  var data = res.data;
                  errorMsg(data.errors);
              })
          }
      }
  }
</script>