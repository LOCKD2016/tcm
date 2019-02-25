
<template>
  <div id="usergroupedit" class="modal fade">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">权限配置</h4>
        </div>
        <div class="modal-body">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab3" role="tab" data-toggle="tab">新增用户组</a></li>
            <li><a href="#tab4" role="tab" data-toggle="tab">权限配置</a></li>
          </ul>
          <div class="tab-content">
            <div id="tab3" class="tab-pane fade in active">
              <div class="form-group">
                <label for="">用户组名称</label>
                <input type="text" placeholder="投资团队" v-model="roles.name" class="form-control"/>
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
            <div id="tab4" class="tab-pane fade">
              <div id="priTree1" class="permission_set"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
          <!--button.btn.btn-primary(type='button' ,v-on:click="del(roles.id)") 删除组123-->
          <button type="button" v-on:click="addRole(roles)" class="btn btn-primary">保存并修改</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  import {errorMsg} from  '../../common.js';
  export  default{
      props: ['id'],
      created(){
          this.getRole(this.id);
      },
      data(){
          return {
              roles: {},
              data: {},
              datas: {},
          }
      },
      methods: {
          getRole(id){
              if (id > 0) {
                  this.$http.get('role/' + id).then(function (res) {
                      this.$set('roles', res.data.data);
                      getRolesTree(this.roles.auth);
                  });
              }
          },
          addRole(roles){
              this.data = window.obj;
              this.$set('roles.auth', this.data);
              this.$http.put('role/' + this.id, roles).then(function (res) {
                  layer.msg(res.data.msg);
                  this.$dispatch("refreshList");
                  $("#usergroupedit").modal("hide");
              }, function (res) {
                  var data = res.data;
                  errorMsg(data.errors);
              })
          },
          del(id){
              this.$http.delete('role/' + id).then(function (res) {
                  if (res.data.status == 1) {
                      layer.msg(res.data.msg);
                      this.$dispatch("refreshList");
                  } else {
                      layer.msg(res.data.msg);
                  }
              })
          }
      }, watch: {
          id(newValue, oldValue){
              this.getRole(newValue);
              this.$set('datas', {});
          }
      }
  }
</script>