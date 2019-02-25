
<!--部门管理新建-->
<template>
  <div id="groupedit" class="modal fade">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">设置用户组</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-2 control-label">所属权限组：</label>
              <div class="col-sm-10"><span v-for="rol  in roles">
                  <label v-if="rol.status == 1">
                    <input type="checkbox" v-bind:value="rol.id" v-model="checkedNames" checked="checked"/>
                    <label>{{rol.display_name}}</label>
                  </label>
                  <label v-else="v-else">
                    <input type="checkbox" v-bind:value="rol.id" v-model="checkedNames"/>
                    <label>{{rol.display_name}}</label>
                  </label></span></div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
          <button type="button" v-on:click="groupsave(rid)" class="btn btn-primary">修改</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  import {errorMsg} from '../../common.js';
  export default {
      props: ['groupid'],
      ready(){
          this.getRole(this.id);
      },
      data(){
          return {
              roles:{},
              checkedNames:[],
              id: 0
          };
      },
      methods: {
          getRole(id){
              if (id > 0) {
                  this.$http.get('user/role/' + id).then(function (res) {
                      this.$set('roles', res.data.roles);
                  });
              }
          },
          groupsave(){
              var obj = {};
              obj.check = this.checkedNames;
              this.$http.put('user/saverole/' + this.id, obj).then(function (res) {
                  if (res.data.status == 1) {
                      layer.msg(res.data.msg);
                      this.$dispatch("admuser");
                      $("#groupedit").modal("hide");
                  } else {
                      layer.msg(res.data.msg);
                  }
              })
          }
      }, watch: {
          groupid(newValue, oldValue){
              this.id = newValue;
              this.getRole(newValue);
          }
      }
  }
</script>