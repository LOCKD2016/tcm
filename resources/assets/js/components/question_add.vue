
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">添加问题</div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="new_item">
      <form role="form" class="form-horizontal">
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>问题类型：</span></label>
          <div class="col-sm-1">
            <select v-model="type" class="form-control">
              <option value="0">单选</option>
              <option value="1">多选</option>
              <option value="2">填空</option>
            </select>
          </div>
          <label for="" class="col-sm-1 control-label"><span>是否必填：</span></label>
          <div class="col-sm-1">
            <select v-model="necessary" class="form-control">
              <option value="0">否</option>
              <option value="1">是</option>
            </select>
          </div>
          <label v-if="type &lt; 2" class="col-sm-1 control-label"><span>是否有其他：</span></label>
          <div v-if="type &lt; 2" class="col-sm-1">
            <select v-model="other" class="form-control">
              <option value="0">否</option>
              <option value="1">是</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label">问题名称：</label>
          <div class="col-sm-4">
            <input type="text" name="title" placeholder="请输入问题名称" v-model="title" class="form-control"/>
          </div>
        </div>
        <div v-if="type==0" v-for="(i,p) in perChoose" class="form-group">
          <div v-on:click="closeper(i)" style="color:red" class="close">×</div>
          <label for="" class="col-sm-1 control-label">问题答案{{i+1}}：</label>
          <div class="col-sm-4">
            <input type="text" name="answer" placeholder="请输入问题答案" v-model="p.answer" class="form-control"/>
          </div>
          <div @click="addQuestion()" class="col-sm-1">+</div>
        </div>
        <div v-if="type==1" v-for="(index,m) in moreChoose" class="form-group">
          <div v-on:click="closemore(index)" style="color:red" class="close">×</div>
          <label for="" class="col-sm-1 control-label">问题答案{{index+1}}：</label>
          <div class="col-sm-4">
            <input type="text" name="answer" placeholder="请输入问题答案" v-model="m.answer" class="form-control"/>
          </div>
          <div @click="addQuestion()" style="color:green" class="col-sm-1">+</div>
        </div>
        <div class="form-group btn_box">
          <button type="button" @click="goback()" class="btn btn-default">取消</button>
          <button type="button" @click="save()" class="btn btn-primary">添加</button>
        </div>
      </form>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      data(){
          return {
              title: '',
              type: 0,
              necessary: 0,
              other: 0,
              perChoose: [{'answer':''}],
              moreChoose: [{'answer':''}],
              fill: ''
          }
      },
      methods: {
          goback(){
              this.$router.go('/lnquiry_list');
          },
          save(){
              if (!this.title) {
                  layer.msg('问题名称不能为空');
              }
              var obj = {};
              obj.title = this.title;
              obj.type = this.type;
              obj.necessary = this.necessary;
              obj.other = this.other;
              if(this.type==0){
                  obj.content = this.perChoose;
              }else if(this.type==1){
                  obj.content = this.moreChoose;
              }
              var _this = this;
              _this.$http.post('question/store/', obj).then(function (res){
                  if(res.data.status>0){
                      layer.msg('添加成功');
                      _this.$router.go('/question_list');
                  }else{
                      layer.msg(res.data.msg);
                  }
              })
          },
          addQuestion(){
              if(this.type==0){
                  this.perChoose.push({'answer':''});
              }else if(this.type==1){
                  this.moreChoose.push({'answer':''});
              }
          },
          closeper(key){
              this.perChoose.splice(key, 1);
          },
          closemore(key){
              this.moreChoose.splice(key, 1);
          }
      }
  }
</script>