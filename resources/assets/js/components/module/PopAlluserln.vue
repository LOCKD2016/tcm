
<!--物流状态-->
<template>
  <div id="alluserln" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">问诊单详情</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label for="" class="col-sm-1 control-label"><span>就诊人信息</span></label>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-1 control-label">
                <h3>真实姓名：{{family.realname}}</h3>
              </label>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-1 control-label">
                <h3>性别：{{family.sex}}</h3>
              </label>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-1 control-label">
                <h3>年龄：{{family.age}}</h3>
              </label>
              <div class="col-sm-5">
                <h3></h3>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-1 control-label">
                <h3>手机号码：{{family.mobile}}</h3>
              </label>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-1 control-label">
                <h3>身份证号：{{family.pincode}}</h3>
              </label>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-1 control-label">
                <h3>身高：{{family.height}}</h3>
              </label>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-1 control-label">
                <h3>体重：{{family.weight}}</h3>
              </label>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-1 control-label">
                <h3>国籍：{{family.country}}</h3>
              </label>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-1 control-label">
                <h3>常居住地：{{family.province}}{{family.city}}{{family.area}}</h3>
              </label>
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
                  <div v-if="list.qid ==1" style="overflow hidden"><img v-bind:src="list.face_img" style="width:90%;margin-left:30px;margin-bottom:10px;"/><img v-bind:src="list.tongue_img" style="width:90%;margin-left:30px;"/></div>
                </li>
              </ul>
            </div>
          </form>
        </div>
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
          return {
              family: {},
              lists: {},
              id: 0,
              question:'',
          }
      },
      events: {
          lndetail(){
              this.getInfo(this.id);
          }
      },
      methods: {
          getInfo(id){
              this.$http.get('law/detail/'+id).then(function (res){
                  this.$set('lists', res.data.data);
                  this.$set('family', res.data.family);
              })
          },
          goback(){
              this.$router.go('/lnquiry_list');
          },
      },
      watch:{
          id(newValue){
              this.getInfo(newValue);
          }
      }
  
  }
</script>