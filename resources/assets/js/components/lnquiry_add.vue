
<template>
  <div class="container main_warp">
    <div class="tit_nav">
      <div class="container clearfix">
        <div class="pull-left">添加问诊单</div>
      </div>
    </div>
    <div class="new_item">
      <form role="form" class="form-horizontal">
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>类型：</span></label>
          <div class="col-sm-1">
            <select v-model="detail.type" class="form-control">
              <option value="0">成人男</option>
              <option value="1">成人女</option>
              <option value="2">儿童</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>标题：</span></label>
          <div class="col-sm-5">
            <input type="text" name="title" placeholder="请输入问诊单标题" v-model="detail.title" class="form-control"/>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>注意事项：</span></label>
          <div class="col-sm-5">
            <input type="text" name="attention" placeholder="请输入问诊单注意事项" v-model="detail.attention" class="form-control"/>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>说明：</span></label>
          <div class="col-sm-5">
            <textarea name="explain" placeholder="请输入问诊单说明" v-model="detail.explain" class="form-control"></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>请勾选问诊单问题：</span></label>
        </div>
        <div v-for="q in questions" class="form-group">
          <div class="col-sm-6">
            <input type="checkbox" value="{{q.id}}" v-model="checkedIds"/><span>{{q.type}} {{q.question}}</span>
          </div>
          <div v-if="q.id == 1 || q.id == 33" style="color:red" class="col-sm-1">＊</div>
        </div>
        <div class="form-group">
          <div style="color:red" class="col-sm-2">说明: 标记为 ＊ 的问题为必选项</div>
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
      created(){
          this.getQuestions();
      },
      data(){
          return {
              questions: {},
              detail: {
                  'type':0
              },
              checkedIds: []
          }
      },
      methods: {
          getQuestions(){
              this.$http.get('question/all').then(function (res){
                  this.$set('questions',res.data.questions);
              })
          },
          goback(){
              this.$router.go('/lnquiry_list');
          },
          save(){
              if (!this.detail.title) {
                  layer.msg('标题不能为空'); return false;
              }
              if (!this.detail.attention) {
                  layer.msg('注意事项不能为空'); return false;
              }
              if (!this.detail.explain) {
                  layer.msg('说明不能为空'); return false;
              }
              if (!this.checkedIds) {
                  layer.msg('您还没有选择问题');
                  return false;
              }
              //判断必选题选择了吗
              var firstpos = this.checkedIds.indexOf('1');
              var twopos = this.checkedIds.indexOf('33');
              console.log(this.checkedIds);
              console.log(twopos);
              console.log(firstpos);
              if(firstpos == -1 || twopos == -1){
                  layer.msg('备注或必填资料题,您还没有选择,请选择!');
                  return false;
              }
              var obj = {};
              var _this = this;
              obj.detail = this.detail;
              obj.detail.checkedIds = this.checkedIds;
              this.$http.post('lnquiry/store/', obj).then(function (res){
                  if(res.data.status==1){
                      layer.msg('添加成功');
                      _this.$router.go('/lnquiry_list/1');
                  }else{
                      layer.msg(res.data.msg);
                  }
              })
          }
      }
  }
</script>