
<template>
  <div id="checkdisease" class="modal fade">
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
      props:['id','disease','aid'],
      data(){
          return {
              data:{},
              childDisease:{},
              name:'',
              sid:0,
              diseaseArr : []
          };
      },
      ready(){
          this.getDetail(this.id);
      },
      methods:{
          getDetail(id){
              if(id >0){
                  this.$http({url: 'section', params: {id: id}}).then(function (res) {
                      this.data = res.data.data;
                      this.getDisease(this.data[0].id);
                  });
              }
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
                  url:'doctor/update/'+this.id,
                  method:'PUT',
                  params:{saveType:'disease',params:{disease:this.diseaseArr}}
              }).then(
                      function (res) {
                          if(res.data.status ==1){
                              this.$emit('newevent')
                              $("#checkdisease").modal("hide");
                          }else{
                              layer.msg(res.data.msg);
                          }
                      }
              );
          }
      },
      watch:{
          aid:function (value,oldVale) {
              var _this = this;
              $.each(this.disease, function (k, v) {
                  _this.diseaseArr.push(v.id);
              })
          }
      }
  }
</script>