
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">问题详情</div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="new_item">
      <form role="form" class="form-horizontal">
        <div class="form-group">
          <label for="" class="col-sm-1 control-label">问题类型:</label>
          <div class="col-sm-1"><span>{{type}}</span></div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label">是否必填:</label>
          <div class="col-sm-1">
            <select v-model="necessary" class="form-control">
              <option value="0">否</option>
              <option value="1">是</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label">问题名称:</label>
          <div class="col-sm-3">
            <input type="text" name="question" v-model="question"/>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"></label>
          <div class="col-sm-1">
            <button type="button" @click="update()" class="btn btn-primary">修改</button>
          </div>
        </div>
        <div class="form-group"><span style="color:red">* 修改问题后请点击修改对问题进行保存</span></div>
        <div v-if="type_id&lt;2" class="form-group">
          <label for="" class="col-sm-1 control-label">答案</label>
        </div>
        <div v-for="(index,a) in list" class="form-group">
          <label v-if="type_id&lt;2" class="col-sm-1 control-label">{{index+1}}、</label>
          <div v-if="type_id&lt;2" class="col-sm-3"><span>{{a.lid}}  ===></span><span onclick="textEdit(this,'{{a.id}}','answer')">{{a.answer}}</span></div>
          <div v-if="type_id&lt;2" class="col-sm-1">
            <div @click="order(a.id)" class="close">↑</div>
          </div>
          <div v-if="type_id&lt;2" class="col-sm-1">
            <div @click="del(a.id)" class="close">×</div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      created(){
          this.id = this.$route.params.id;
          this.getInfo(this.id);
      },
      ready(){
          headNav(2);
      },
      data(){
          return {
              question: '',
              type_id: 0,
              list: {},
              id: 0,
              type: '',
              necessary: 0
          }
      },
      events: {
          lnquestion(){
              this.getInfo(this.id);
          }
      },
      methods: {
          getInfo(id){
              this.$http.get('question/'+id).then(function (res){
                  this.$set('question',res.data.question.question);
                  this.$set('type_id',res.data.question.type);
                  this.$set('necessary',res.data.question.necessary);
                  var type = res.data.question.type;
                  this.returnType(type);
                  this.$set('list',res.data.question.answer);
              })
          },
          returnType(type){
              if(type==0){
                  this.type = '单选';
              }else if(type==1){
                  this.type = '多选';
              }else if(type==2){
                  this.type = '填空';
              }
          },
          goback(){
              this.$route.go('/lnquiry_list');
          },
          update(){
              if(!this.id){
                  layer.msg('系统错误，请按F5刷新重试')
              }
              var obj = {};
              obj.question = this.question;
              obj.necessary = this.necessary;
              var id = this.id;
              this.$http.put('question/'+id,obj).then(function (res){
                  if (res.data.status) {
                      layer.msg(res.data.msg);
                  }
                  vue.$dispatch('lnquestion');
              })
          },
          order(id){
              var obj = {};
              obj.qid = this.id;
              obj.order = 1;
              var vue = this;
              vue.$http.put('answer/' + id, obj).then(function (res) {
                  if (res.data.status) {
                      layer.msg(res.data.msg);
                  }
                  vue.$dispatch('lnquestion');
              })
          },
          qita(){
              layer.msg('其他是不能修改的');//123123
              return false;
          },
          del(id){
              var vue = this;
              layer.confirm('您确定删除？', {
                  btn: ['确定', '取消']
              }, function () {
                  vue.$http.delete('answer/' + id).then(function (res) {
                      if (res.data.status) {
                          layer.msg(res.data.msg);
                          vue.$dispatch('lnquestion');
                      }
                  })
              }, function () {
  
              });
          }
      }
  }
</script>