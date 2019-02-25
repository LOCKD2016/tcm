
<template>
  <div id="diseasecomadd" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <div v-for="val in data" @click="getDisease(val.id)" class="col-sm-2"><span class="checked_f{{val.id}}">{{val.name}}</span></div>
            </div>
            <div class="form-group">
              <div v-for="val in childDisease" @click="checkAttr(val.id)" class="col-sm-4">
                <input type="checkbox" name="checkboxName" class="checked{{val.id}}"/><span>{{val.name}}</span>
                <!--ssss-->
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
      data(){
          return {
              data:{},
              childDisease:{},
              diseaseArr : []
          };
      },
      ready(){
          this.getDetail();
      },
      methods:{
          getDetail(){
              this.$http({url: 'section', params: {id: 1}}).then(function (res) {
                  this.data = res.data.data;
                  this.getDisease(this.data[0].id);
              });
          },
          getDisease(id){
              var _this = this;
              this.$http({url: 'disease/' + id}).then(function (res) {
                  this.childDisease = res.data.data;
                  $('span').attr('style',"color:#333");
                  $(".checked_f"+id).attr("style","color:red");
                  this.$nextTick(function () {
                      _this.changeProp()
                  });
              });
          },
          changeProp(){
              var _this = this;
              $.each(this.childDisease,function (k,v) {
                  var index = $.inArray(v.id,_this.diseaseArr);
                  if(index != -1){
                      $(".checked"+v.id).prop("checked",true);
                  }
              })
          },
          checkAttr(id){
              var _this = this;
              var index = $.inArray(id,_this.diseaseArr);
              if (index == -1) {
                  $(".checked" + id).prop("checked", true);
                  this.diseaseArr.push(id);
              }else{
                  $(".checked" + id).prop("checked", false);
                  this.diseaseArr.splice(index, 1);
              }
          },
          store(){
              this.$http({
                  url:'disease_common',
                  method:'post',
                  params:{disease:this.diseaseArr}
              }).then(
                      function (res) {
                          if(res.data.status ==1){
                              this.$dispatch('update');
                              $("#diseasecomadd").modal("hide");
                          }else{
                              layer.msg(res.data.msg);
                          }
                      }
              );
          }
      }
  }
</script>