
<!--覆盖-->
<template>
  <div id="affiche" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">修改公告</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">公告详情：</label>
              <div class="col-sm-8">
                <textarea v-model="item.content" class="form-control"></textarea>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
          <button type="button" v-on:click="save()" class="btn btn-primary">保存</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      props:['item'],
      data(){
          return {
              data:{}
          };
      },
      methods:{
          save(){
              this.$http.post('save_affiche',this.item).then(function (res) {
                  if(res.data.status)
                      $('#affiche').modal('hide');
              })
          },
      },
      watch:{
          'item':function(value){
  
          }
      }
  }
</script>