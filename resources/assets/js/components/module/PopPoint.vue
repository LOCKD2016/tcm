
<!--物流状态-->
<template>
  <div id="point" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">穴位选择</h4>
        </div>
        <div style="margin-top:5px;">
          <input type="text" name="search" style="margin-left: 7px;"/><a @click="search()" class="btn btn-primary btn-sm"><i class="icon-plus"></i><span style="margin-left:5px">搜索</span></a>
        </div>
        <div @click="addChecks(1,5)" style="overflow:hidden">
          <input type="radio" name="checkName" style="float: left;margin-left: 5px;"/><span style="float: left;">全部取消</span>
          <button type="button" @click="addPoint()" style="float: right;margin-right: 5px;" class="btn btn-primary">添加</button>
        </div>
        <div class="modal-body"><span v-if="data.status ==200 &amp;&amp; data.data.point_id != ''"><span>历史选择:<span>{{data.data.relate}} {{data.data.point_id}}</span></span></span>
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <div v-for="item in relate" @click="addChecks(item.point,$index)" class="col-sm-2">
                <input type="radio" name="checkName"/><span>{{item.name}}</span>
              </div>
            </div>
            <div class="form-group">
              <div v-for="point in points" @click="addChecks(point)" class="col-sm-2">
                <input type="checkbox" name="checkboxName" class="checked{{point.id}}"/><span>{{point.name}}</span>
              </div>
            </div>
          </form>
          <div style="width:100%;overflow: hidden;">
            <div v-if="checks.length" v-for="item in checks" track-by="$index" style="width:45%;float: left;margin-left: 20px;"><img v-bind:src="item.img"/></div>
            <!--span {{item.name}}-->
          </div>
        </div>
        <!--.modal-footerbutton.btn.btn-default(type='button', data-dismiss='modal') 取消
        -->
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      props: ['id'],
      created(){
          this.id = this.$route.params.id;
      },
      data(){
          return {//
              points: {},
              relate:{},
              data:{},
              checks:[],
              checkRadio:0
          };
      },
      ready(){
  
      },
      methods: {
          search(){
              var name = $('input[name="search"]').val();
              $.each(this.points,function (key,val) {
                  $('.checked'+val.id).next('span').css({color:'#333','font-size':"14px"});
                  if(val.name == name){
                      $('.checked'+val.id).next('span').css({'color':'red','font-size':"20px"});
                  }
              })
          },
          addPoint:function () {
              var obj = {};
              obj.system = 'relate';
              obj.param = {relate:this.checkRadio,point:this.checks};
              this.$http.post('law/update/'+this.id,obj).then(function (res) {
                  layer.msg(res.data.msg);
                  if(res.data.status == 200){
                      $("#point").modal("hide");
                      this.$dispatch("refreshln");
                  }
              });
          },
          addChecks(val,prev){
              if(typeof (prev) !='undefined'){
                  this.checks = [];
                  $('input:checkbox').prop("checked", false);
                  this.checkRadio = 0;
                  if(prev ==5) return;
                  this.checkRadio = prev +1;
                  for (var i = 0; i < val.length; i++) {
                      this.checks.push(val[i]);
                      $('.checked'+val[i].id).prop('checked',true);
                  }
              }else{
                  var checksid = [];
                  $.each(this.checks, function (index, val) {
                      checksid.push(val.id);
                  });
                  var index = checksid.indexOf(val.id);
                  if(index >-1){
                      this.checks.splice(index,1);
                      $('.checked'+val[i].id).prop('checked',false);
                  }else{
                      this.checks.push(val);
                      $('.checked' + val.id).prop('checked', true);
                  }
              }
          },
          getPoint(){
              this.$http({url: 'law/point', method: 'GET'}).then(function (res) {
                      this.$set('points', res.data);
              })
          },
          getRekate:function () {
              this.$http({url: 'law/relate', method: 'GET'}).then(function (res) {
                  this.$set('relate', res.data);
              })
          },
          getDetail:function () {
              this.$http({url: 'law/show/'+this.id, method: 'GET'}).then(function (res) {
                  this.$set('data', res.data);
              })
          }
      },
      watch: {
          id(newValue){
              if(newValue){
                  this.getPoint();
                  this.getRekate();
                  this.getDetail();
              }
          },
      }
  
  }
</script>