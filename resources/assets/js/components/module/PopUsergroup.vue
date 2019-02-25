
<!--权限配置-->
<template>
  <div id="usergroup" class="modal fade">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">权限配置</h4>
        </div>
        <div class="modal-body">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" role="tab" data-toggle="tab">新增用户组</a></li>
            <li><a href="#tab2" role="tab" data-toggle="tab">权限配置</a></li>
          </ul>
          <div class="tab-content">
            <div id="tab1" class="tab-pane fade in active">
              <div class="form-group">
                <label for="">用户组名称</label>
                <input type="text" placeholder="输入组名称" v-model="roles.name" class="form-control"/>
              </div>
              <div class="form-group">
                <label for="">用户组标识</label>
                <input type="text" placeholder="用户组标识" v-model="roles.display_name" class="form-control"/>
              </div>
              <div class="form-group">
                <label for="">描述</label>
                <textarea placeholder="除了描述之外的其他功能" rows="3" v-model="roles.description" class="form-control"></textarea>
              </div>
            </div>
            <div id="tab2" class="tab-pane fade">
              <div id="priTree" class="permission_set"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
          <button type="button" v-on:click="getRole(roles)" class="btn btn-primary">保存并修改</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  import {errorMsg} from  '../../common.js';
  export  default{
      data(){
          return {
              roles:{},
          }
      },
      methods:{
          getRole(role){
              this.$set('roles.auth',window.datas);
              this.$http.post('role',role).then(function(res){
                  layer.msg(res.data.msg);
                  this.$dispatch("refreshList");
                  this.$dispatch("count");
                  $('#usergroup').modal('hide');
              },function(res){
                  console.log(res);
                  errorMsg(res.data.errors);
              })
          },
      }
  }
</script>