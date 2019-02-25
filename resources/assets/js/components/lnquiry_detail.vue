
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">问诊单详情</div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="new_item">
      <form role="form" class="form-horizontal">
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>标题：</span></label>
          <div class="col-sm-5"><span onclick="textEdit(this,'{{lnquiry.id}}','title')">{{lnquiry.title}}</span></div>
          <div style="color:red" class="col-sm-1">＊</div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>注意事项：</span></label>
          <div class="col-sm-5"><span onclick="txtEdit(this,'{{lnquiry.id}}','attention')">{{lnquiry.attention}}</span></div>
          <div style="color:red" class="col-sm-1">＊</div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>说明：</span></label>
          <div class="col-sm-5"><span onclick="txtEdit(this,'{{lnquiry.id}}','explain')">{{lnquiry.explain}}</span></div>
          <div style="color:red" class="col-sm-1">＊</div>
        </div>
        <div v-for="(index,l) in list" class="form-group">
          <div @click="close(l.id)" style="color:red;opacity:1;" class="close">×</div>
          <div @click="edit(l.id)" style="color:#402d65;opacity:1;" class="close">✎</div>
          <label class="col-sm-1 control-label">问题排序</label>
          <div class="col-sm-1"><span onclick="lnEdit(this,'{{l.id}}','order','{{lnquiry.id}}')">{{l.order}}</span></div>
          <div style="color:red" class="col-sm-1">＊</div>
          <label for="" class="col-sm-1 control-label"></label>
          <div class="col-sm-3"><span>{{l.question}} (跳转序号{{l.id}})</span></div>
          <div class="form-group">
            <label class="col-sm-3 control-label"></label>
            <div v-for="(index,a) in l.answers" class="col-sm-3"><span v-if="a.hrefid">跳转序号 ==></span><span v-if="a.hrefid" onclick="textEdit(this,'{{a.id}}','hrefid')">{{a.hrefid}} ＊</span><span v-if="a.answer">答案 {{index+1}} : {{a.answer}}</span></div>
          </div>
        </div>
        <div class="form-group"><span style="color:red">* 点击标题、注意事项、说明、问题排序即可直接修改</span></div>
      </form>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      created(){
          this.id = this.$route.params.id;//324
          this.getInfo(this.id);
      },
      data(){
          return {
              lnquiry: {},
              list: {},
              id: 0
          }
      },
      events: {
          lndetail(){
              this.getInfo(this.id);
          }
      },
      methods: {
          getInfo(id){
              this.$http.get('lnquiry/'+id).then(function (res){
                  this.$set('lnquiry',res.data.lnquiry);
                  this.$set('list',res.data.lnquiry.list);
              })
          },
          goback(){
              this.$router.go('/lnquiry_list');
          },
          close(id){
              var vue = this;
              var obj = {};
              obj.qid = id;
              obj.lid = this.id;
              layer.confirm('您确定删除？', {
                  btn: ['确定', '取消']
              }, function () {
                  vue.$http.post('lnquiry/delquestion/', obj).then(function (res) {
                      if (res.data.status) {
                          layer.msg(res.data.msg);
                      }
                      vue.$dispatch('lndetail');
                  })
              }, function () {
  
              });
          },
          order(id){
              if(!id){
                  layer.msg('系统错误，请按F5刷新重试')
              }
              var obj = {};
              obj.qid = id;
              obj.lid = this.id;
              obj.order = $('input[name="'+id+'order"]').val();
              this.$http.post('lnquiry/order/',obj).then(function (res){
                  if (res.data.status) {
                      layer.msg(res.data.msg);
                  }
                  vue.$dispatch('lndetail');
              })
          },
          edit(id){
              this.$router.go({name: 'question_answer',params: {id: id}});//2
          }
      }
  }
</script>