
<template>
  <div class="container main_warp">
    <div class="box_css"><a onclick="itemPop2(undefined,'addtestt');" class="btn btn-primary btn-sm"><span>添加问题</span>
        <!--ssssss--></a>
      <div class="new_item">
        <form role="form" class="form-horizontal">
          <div class="form-group">
            <label for="" class="col-sm-2 control-label"><span class="notice-w">*</span>问卷标题：</label>
            <div class="col-sm-3">
              <input type="text" name="title" v-model="data.title" class="form-control"/>
            </div>
          </div>
          <div v-for="ddd in test" class="form-group sel_ti">
            <div class="float_box clearfix">
              <label v-if="ddd.type =='radio'" style="float:left;" class="col-sm-2 control-label">单选题 ：</label>
              <label v-if="ddd.type =='checkbox'" style="float:left;" class="col-sm-2 control-label">复选题 ：</label>
              <label v-if="ddd.type =='text'" style="float:left;" class="col-sm-2 control-label">问答题 ：</label>
              <label v-if="ddd.type =='photo'" style="float:left;" class="col-sm-2 control-label">图片题 ：</label>
              <div style="float:left;line-height:35px;" class="itemss"><span>{{ddd.title}}</span></div><i @click="del(ddd.id)" style="float:left;line-height:35px;color:red;" class="icon-bin"></i><i @click="saveTest(ddd)" style="margin-left:10px;float:left;line-height:35px;" class="icon-pencil"></i>
            </div>
            <template v-if="ddd.type == 'checkbox' || ddd.type =='radio'" track-by="$index">
              <p v-for="answer in ddd.option" style="padding-left:255px" class="result"><span> {{answer.val}}</span></p>
            </template>
            <template v-if="ddd.type == 'photo'">
              <div style="padding-left:255px;" class="f_box clearfix">
                <p v-for="val in ddd.option" style="float:left;margin-right:10px;" class="result"><img v-bind:src="val" style="width:160px"/></p>
              </div>
            </template>
          </div>
        </form>
      </div>
    </div>
    <addtest :ddd.sync="ddd"></addtest>
    <pop-addtest :id.sync="id"></pop-addtest>
  </div>
</template>
<script type="text/javascript">
  import addtest from "./module/addtest.vue"
  export default {
      components: {
          addtest
      },
      ready(){
          headNav(4);
      },
      data(){
          return {
              data:{},
              id:0,
              sid:0,
              ddd:{},
              test:[],
          }
      },
      events:{
          questionsave(){
              this.getTest(this.id);
          }
      },
      created(){
          this.id = this.$route.params.id;
          this.getTest(this.id);
      },
      methods: {
          //ss
          saveTest(val){
              var _this = this;
              this.ddd = val;
              $('#addtest').modal('show');
              setTimeout(function(){
                  $(".clone").delegate('.icon-minus', 'click', function () {
                      $(this).parents('.form-group').remove()
                  });
  
                  $(".clone").delegate('.icon-plus', 'click', function () {
                      $(this).parents('.form-group').clone(false).appendTo($(this).parents('.clone'))
                  });
              },1000);
              //ssss
          },
  
          del(id){
              var vue = this;
              layer.confirm('您确定要删除吗？', {
                  btn: ['确定', '取消']
              }, function () {
                  vue.$http.delete('exam/' + id).then(function (res) {
                      layer.msg(res.data.msg);
                      if (res.data.status == 1) {
                          vue.$dispatch('questionsave');
                      }
                  });
              }, function () {
              });
  
          },
          getTest(id){
              this.$http.get('exam/'+id).then(function (res) {
                  this.$set('data',res.data.data);
                  this.$set('id',res.data.data.id);
                  this.$set('test',res.data.data.options);
              });
          },
      },
  }
</script>