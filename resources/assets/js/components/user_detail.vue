
<template>
  <div class="tit_nav">
    <div class="container">
      <div class="pull-left">用户管理 > 用户详情
        <label></label>
      </div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="new_item">
      <!--button(@click="save()",class="btn btn-primary")  修改-->
      <form role="form" class="form-horizontal">
        <div class="form-group">
          <label for="" class="col-sm-1 control-label">头像：</label>
          <div class="col-sm-2">
            <div class="sel_box">
              <div v-bind:style="{backgroundImage:'url(' +user.headimgurl+')' }" class="img-face-t"></div>
            </div>
          </div>
          <div class="col-sm-3">
            <p>患者昵称： {{user.nickname}}</p>
            <p>手机号： {{user.mobile}}</p>
            <p>性别： {{user.sex}}</p>
            <p>身份证号： {{user.pincode}}</p>
            <p>国籍： {{user.county}}</p>
          </div>
          <div class="col-sm-3">
            <p>真实姓名： {{user.realname}}</p>
            <p>年龄： {{user.age}}</p>
            <p>身高： {{user.height}}</p>
            <p>体重： {{user.weight}}</p>
            <p>常居住地： {{user.province ? user.province : '暂无'}}{{user.city}}{{user.area}}</p>
          </div>
        </div>
      </form>
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab5" role="tab" data-toggle="tab">就诊记录</a></li>
      </ul>
      <div class="tab-content">
        <div id="tab5" class="tab-pane fade in active">
          <form role="form" class="form-horizontal user_table_box table-responsive">
            <table class="table table-bordered check_list">
              <thead>
                <tr>
                  <th class="col-sm-1">医师</th>
                  <th class="col-sm-1">就诊方式</th>
                  <th class="col-sm-1">类型</th>
                  <th style="width:12%" class="col-sm-1">创建时间</th>
                  <th class="col-sm-1">诊断（处方名称）</th>
                  <th class="col-sm-1">处方</th>
                  <th class="col-sm-1">剂量</th>
                  <!--th.col-sm-1 操作-->
                </tr>
              </thead>
              <tbody>
                <tr v-for="(index,c) in clinics">
                  <td>{{c.doctor.name}}</td>
                  <td v-if="c.type == 0">网诊</td>
                  <td v-else="v-else">门诊</td>
                  <td v-if="c.first == 0">复诊</td>
                  <td v-else="v-else">初诊</td>
                  <td>{{c.created_at}}</td>
                  <td>{{c.disease}}</td>
                  <td>{{c.prescription.recipe}}</td>
                  <td>{{c.prescription.recipe_head}}</td>
                  <!--td
                  //span(@click="clinic(val.id)") 详情
                  
                  -->
                </tr>
              </tbody>
            </table>
          </form>
        </div>
      </div>
    </div>
    <!--clinic_detail(:clinic_id.sync="clinic_id")-->
    <!--save_appuser(:item.sync="item")-->
  </div>
</template>
<script type="text/javascript">
  import clinic_detail from "./module/clinic_detail.vue"
  import save_appuser from "./module/save_appuser.vue"
  
  export default {
      components: {
          clinic_detail,
          save_appuser
      },
      created(){
          this.user_id = this.$route.params.id;
      },
      ready(){
          headNav(0);
      },
      data(){
          return {
              user_id:0,
              user:{},
              item:{},
              orders:{},
              clinics:{},
              clinic_id:0,
          }
      },
      events: {
          refreshList(){
              this.getUserDetail(this.user_id);
          }
      },
      methods: {
          save(){
              this.$set('item', this.user);
              this.$set('item.save_type', 'user');
              $("#save_appuser").modal("show");
          },
          clinic(clinic_id){
              this.$set('clinic_id',clinic_id);
              $("#clinic_detail").modal("show");
          },
          getUserDetail(id){
              if(id>0){
                  this.$http.get('appuser/detail/' + id).then(function (res) {
                      this.$set('user',res.data.data);
                      this.$set('clinics',res.data.data.clinics);
                      this.$set('orders',res.data.data.orders);
                  });
              }
          }
      },
      watch: {
          user_id(newValue){
              this.getUserDetail(newValue);
          }
      }
  
  }
</script>