
<template>
  <div id="addsection" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <div v-for="val in allSection" @click="checkAttr(val.id)" class="col-sm-2">
                <input type="checkbox" name="checkboxName" class="checked_{{val.id}}"/><span class="checked_f{{val.id}}">{{val.name}}</span>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" @click="store()" class="btn btn-primary">保存</button>
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      props:['id'],
      data(){
          return {
              data:{},
              allSection:{},
              name:'',
              sid:0,
              sectionArr : []
          };
      },
      ready(){
          this.getDisease();
      },
      methods:{
          getDisease() {
              this.$http({url: 'section/index',method:'GET',params:{noPage:true}}).then(function (res) {
                  this.allSection = res.data.sections;
              });
          },
          checkAttr(id) {
              var _this = this;
              var index = $.inArray(id, _this.sectionArr);
              if (index == -1) {
                  $(".checked" + id).prop("checked", true);
                  this.sectionArr.push(id);
              } else {
                  $(".checked" + id).prop("checked", false);
                  this.sectionArr.splice(index, 1);
              }
              console.log(this.sectionArr);
          },
          store() {
              this.$http({url: 'doctor/addisease/' + this.id, method: 'PUT', params: {data: this.sectionArr, type:'section'}}).then(function (res) {
                  if (res.data.status == 1) {
                          $("#addsection").modal("hide");
                          this.sectionArr = [];
                          this.$dispatch("refreshList");
                      } else {
                          layer.msg(res.data.msg);
                  }
              });
          }
      }
  }
</script>