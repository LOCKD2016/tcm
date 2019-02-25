
<!--部门管理新建-->
<template>
  <div id="sectionadd" class="modal fade">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">新建科室</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-2 control-label">名称：</label>
              <div class="col-sm-10">
                <input type="text" v-model="data.name" class="form-control"/>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
          <button type="button" v-on:click="add()" class="btn btn-primary">添加</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      data(){
          return {
              data:{}
          };
      },
      methods: {
          add(){
              this.$http.post('section/add', this.data).then(function (res) {
                  if (res.data.status == 1) {
                      layer.msg(res.data.msg);
                      this.$set('data',{});
                      this.$dispatch("add");
                      $("#sectionadd").modal("hide");
                  } else {
                      layer.msg(res.data.msg);
                  }
              });
          },
      }
  }
</script>