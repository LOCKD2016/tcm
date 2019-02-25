
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
          <label for="" class="col-sm-1 control-label"><span style="margin-right:33px;">就诊人信息</span><span v-if="info.status != 3">
              <select v-model="info.type" v-on:change="setChange(info.id,info.type,info.status)" style="margin-right:20px;" class="btn btn-primary">
                <option value="0">未选择</option>
                <option value="1">温和型</option>
                <option value="2">增强型</option>
              </select></span><span v-if="info.status != 3" @click="point(info.id)" style="margin-right:20px;" class="btn btn-primary">方案</span><span v-if="info.status != 3" @click="note(info.id)" style="margin-right:20px;" class="btn btn-primary">备注</span><span v-if="info.status != 3" @click="send(info.id,info.status)" class="btn btn-primary">发送</span></label>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label">
            <h3>真实姓名：</h3>
          </label>
          <div class="col-sm-5">
            <h3>{{family.realname}}</h3>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label">
            <h3>性别：</h3>
          </label>
          <div class="col-sm-5">
            <h3>{{family.sex}}</h3>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label">
            <h3>年龄：</h3>
          </label>
          <div class="col-sm-5">
            <h3>{{family.age}}</h3>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label">
            <h3>手机号码：</h3>
          </label>
          <div class="col-sm-5">
            <h3>{{family.mobile}}</h3>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label">
            <h3>身份证号：</h3>
          </label>
          <div class="col-sm-5">
            <h3>{{family.pincode}}</h3>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label">
            <h3>身高：</h3>
          </label>
          <div class="col-sm-5">
            <h3>{{family.height}}</h3>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label">
            <h3>体重：</h3>
          </label>
          <div class="col-sm-5">
            <h3>{{family.weight}}</h3>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label">
            <h3>国籍：</h3>
          </label>
          <div class="col-sm-5">
            <h3>{{family.country}}</h3>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label">
            <h3>常居住地：</h3>
          </label>
          <div class="col-sm-5">
            <h3>{{family.province}}{{family.city}}{{family.area}}</h3>
          </div>
        </div>
      </form>
      <form class="form-horizontal">
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span> 问诊单</span></label>
        </div>
      </form>
      <form v-for="(index,list) in lists" class="form-horizontal">
        <div class="form-group">
          <label for="" class="col-sm-1 control-label">
            <h3>{{$index+1}}、 {{list.question}}</h3>
          </label>
        </div>
        <div v-for="a in list.aid" class="form-group">
          <ul>
            <li v-if="list.type == 0">
              <input type="radio" checked="checked" style="margin-left:33px;"/><span style="margin-left:8px;">{{a}}</span>
            </li>
            <li v-if="list.type == 1">
              <input type="checkbox" checked="checked" style="margin-left:33px;"/><span style="margin-left:8px;">{{a}}</span>
            </li>
            <li v-if="list.type == 2"><span style="margin-left: 35px;">{{a}}</span>
              <div v-if="list.qid ==1" style="overflow hidden"><img v-bind:src="list.face_img" style="width:20%;margin-left:33px;"/><img v-bind:src="list.tongue_img" style="width:20%;margin-left:20px;"/></div>
            </li>
          </ul>
        </div>
      </form>
    </div>
    <pop-point :id.sync="id"></pop-point>
    <pop-Addnote :id.sync="id"></pop-Addnote>
  </div>
</template>
<script type="text/javascript">
  export default {
      ready(){
          headNav(2);
      },
      created(){
          this.id = this.$route.params.id;
          this.getInfo(this.id);
          this.getDetail(this.id);
      },
      data(){
          return {
              family: {},
              lists: {},
              id: 0,
              info: {},
              question:'',
          }
      },
      events: {
          lndetail(){
              this.getInfo(this.id);
          }
      },
      methods: {
          setChange(id, type, status) {
              if (status == 3) {
                  layer.msg('已发送 不可修改');
                  return;
              }
              var obj = {};
              obj.system = 'type';
              obj.param = {type: type};
              this.$http.post('law/update/' + id, obj).then(function (res) {
                  layer.msg(res.data.msg);
              })
          },
          send(id, status){
              if (status == 3) {
                  layer.msg('已发送');
                  return;
              }
              var obj = {};
              obj.system = 'send';
              obj.param = {status: 3};
              this.$http.post('law/update/' + id, obj).then(function (res) {
                  layer.msg(res.data.msg);
                  if (res.data.status == 200) {
                      this.$dispatch('refreshln');
                  }
              })
          },
          getInfo(id){
              this.$http.get('law/detail/'+id).then(function (res){
                  this.$set('lists', res.data.data);
                  this.$set('family', res.data.family);
              })
          },
          getDetail(id){
              this.$http.get('law/note/' + id).then(function (res) {
                  this.$set('info', res.data.data);
              })
          },
          point(id){
              this.$http.get('law/forbid/' + id).then(function (res) {
                  if (res.data.status) {
                      this.$set('id', id);
                      $("#point").modal("show");
                  } else {
                      layer.msg(res.data.msg);
                  }
              });
  
          },
          note(id){
              this.$http.get('law/forbid/' + id).then(function (res) {
                  if (res.data.status) {
                      this.$set('id', id);
                      $("#addnote").modal("show");
                  } else {
                      layer.msg(res.data.msg);
                  }
              });
  
          },
          goback(){
              this.$router.go('/lnquiry_list');
          },
  
      }
  }
</script>