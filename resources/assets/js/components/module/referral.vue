
<template>
  <div id="referral" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">{{bespeakUser.nickname}} 的问诊单</h4><br/>
          <!--form.form-horizontal(role='form')
          .form-group
              label.col-sm-10(for='') 转诊记录
              .col-sm-10(v-for="val in record")
                  p {{val.doctor.name}}
                  p(v-if="val.status ==6") 拒诊
                  p(v-else) 未接诊
          -->
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label for="" class="col-sm-10">疾病描述：</label>
              <div v-if="bespeak.redundant_first==0" class="col-sm-10"><span v-for="img in bespeak.disease"><a v-bind:href="img"><img v-bind:src="img" style="width:30%;margin-left:5px;margin-top:5px;"/></a></span></div>
              <div v-if="bespeak.redundant_first==1" class="col-sm-10">
                <p>{{bespeak.disease}}</p>
              </div>
            </div>
          </form>
          <hr/>
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label for="" class="col-sm-12">推荐医师科室筛选</label>
              <div v-for="val in section" @click="sectionClick(val.id)" style="margin-bottom:5px" class="col-sm-2"><span class="checked{{val.id}}"><a class="color">{{val.name}}</a></span></div>
            </div>
            <table class="table table-bordered check_list user_table_box">
              <thead>
                <tr>
                  <th class="col-sm-3">医师</th>
                  <th class="col-sm-3">联系方式</th>
                  <th class="col-sm-3"><span> 操作</span><span>&nbsp;&nbsp;&nbsp</span><span @click="next()"><a class="color">换一批</a></span></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in doctor" v-bind:class="{ check_background_red  : bespeak.doctor_id == item.id}">
                  <td>{{item.name}}</td>
                  <td>{{item.mobile}}</td>
                  <td>
                    <!--span.col(v-on:click="sub('sub_sub',item.id)") 推送问诊单--><span v-on:click="sub('suc_sub',item.id)" class="col">确认转诊</span>
                    <!--span.col(v-on:click="cancel()") 取消-->
                  </td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      props:['id'],
      data(){
          return {
              bespeak:{},
              bespeakUser:{},
              bespeakDoctor:{},
              doctor:{},
              section:{},
              record:{},
              section_id:0,
              page:1,
              cur:1,
              all:1,
              total:1
          };
      },
      methods:{
          sub(type,doc_id){
              this.$http({
                  url:"bespeaks/update/"+this.id,
                  method:"PUT",
                  params:{type:type,doctor_id:doc_id}
              }).then(function (res) {
                  layer.msg(res.data.msg);
                  if(res.data.errcode ==200){
                      this.getReferral(this.id);
                      this.$dispatch("update");
                  }
              });
          },
          next(){
              this.page++;
              if(this.page > this.all){
                  this.page = 1;
              }
              this.getReferral(this.id);
          },
          getReferral(id){
              this.$http({url:'bespeaks/show/'+id,params:{section:this.section_id, page:this.page}}).then(function (res) {
                  this.$set('bespeak',res.data.bespeak);
                  this.$set('bespeakUser',res.data.bespeak.user);
                  this.$set('bespeakDoctor',res.data.bespeak.doctor);
                  this.$set('section',res.data.section);
                  this.$set('doctor',res.data.doctor.data);
                  var pagination = res.data.doctor;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.last_page);
                  this.$set('total', pagination.total);
                  this.section.unshift({id:0,name:"全部"});
              });
          },
          sectionClick(id){
              this.section_id = id;
              this.page = 1;
              this.getReferral(this.id);
          },
          bg: function (url) {
              if (url) return 'background-image:url(' + url + ')'
          },
          cancel(){
              $('#referral').modal('hide');
          }
      },
      watch:{
          'id':function(value){
              if(value>0){
                  this.getReferral(value);
              }
          }
      }
  }
</script>