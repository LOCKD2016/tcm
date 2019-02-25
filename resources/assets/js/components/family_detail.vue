
<template>
  <div class="tit_nav">
    <div class="container">
      <div class="pull-left">患者管理 > 患者详情
        <label></label>
      </div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="new_item">
      <button @click="save()" class="btn btn-primary"> 修改</button>
      <!--sssss-->
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
            <p>性别： {{user.sex ? user.sex : '暂无'}}</p>
            <p>身份证号： {{user.pincode ? user.pincode : '暂无'}}</p>
            <p>国籍： {{user.county ? user.county : '暂无'}}</p>
          </div>
          <div class="col-sm-3">
            <p>真实姓名： {{user.realname}}</p>
            <p>年龄： {{user.age ? user.age : '暂无'}}</p>
            <p>身高： {{user.height ? user.height : '暂无'}}</p>
            <p>体重： {{user.weight ? user.weight : '暂无'}}</p>
            <p>常居住地： {{user.province ? user.province : '暂无'}}{{user.city}}{{user.area}}</p>
          </div>
        </div>
      </form>
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab4" role="tab" data-toggle="tab">关联人</a></li>
        <li><a href="#tab5" role="tab" data-toggle="tab">就诊记录</a></li>
        <!--lia(href="#tab2" role="tab" data-toggle="tab") 订单记录
        -->
      </ul>
      <div class="tab-content">
        <div id="tab4" class="tab-pane fade in active">
          <form role="form" class="form-horizontal user_table_box table-responsive">
            <table class="table table-bordered check_lis">
              <thead>
                <tr>
                  <th class="col-sm-1">关联人</th>
                  <th class="col-sm-1">是否为VIP/家庭</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="val in user.users">
                  <td>{{val.realname}}</td>
                  <td>{{val.pivot.type ==1 ? '会员卡用户' : '普通家庭成员'}}</td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
        <div id="tab5" class="tab-pane fade">
          <form role="form" class="form-horizontal user_table_box table-responsive">
            <table class="table table-bordered check_list">
              <thead>
                <tr>
                  <th class="col-sm-1">下单人</th>
                  <th class="col-sm-1">医师</th>
                  <th class="col-sm-1">就诊方式</th>
                  <th class="col-sm-1">类型</th>
                  <th style="width:12%" class="col-sm-1">就诊时间</th>
                  <th class="col-sm-1">诊断（处方名称）</th>
                  <th class="col-sm-1">处方</th>
                  <th class="col-sm-1">剂量</th>
                  <th class="col-sm-1">操作</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="val in user.clinic">
                  <td>{{val.userName}}</td>
                  <td>{{val.doctorsName}}</td>
                  <td>{{val.recipe_status ==1 ? '门诊' : '网诊'}}</td>
                  <td>{{val.is_first == 1 ? '复诊' : '初诊'}}</td>
                  <td>{{val.created_at}}</td>
                  <template v-if="val.has_one_recipe">
                    <td>{{val.has_one_recipe.disease}}</td>
                    <td><span v-for="r in val.has_one_recipe.recipe">{{r.name}} {{r.dosage}} {{r.other}}</span></td>
                    <td>{{val.has_one_recipe.recipe_head.sum}}</td>
                  </template>
                  <template v-else="v-else">
                    <td></td>
                    <td></td>
                    <td></td>
                  </template>
                  <td><span @click="clinic(val.id)">详情</span></td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
      </div>
    </div>
    <clinic_detail :clinic_id.sync="clinic_id"></clinic_detail>
    <save_appuser :item.sync="item"></save_appuser>
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
      data(){
          return {
              user_id:0,
              user:{},
              item:{},
              clinic_id:0
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
              this.$set('item.save_type', 'family');
              $("#save_appuser").modal("show");
          },
          clinic(clinic_id){
              this.$set('clinic_id',clinic_id);
              $("#clinic_detail").modal("show");
          },
          getUserDetail(id){
              if(id>0){
                  this.$http.get('appuser/detail/' + id + '/family').then(function (res) {
                      this.$set('user',res.data.data);
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