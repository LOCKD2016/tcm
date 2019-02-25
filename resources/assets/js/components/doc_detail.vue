
<template>
  <div class="tit_nav">
    <div class="container">
      <div class="pull-left">医生管理 > 医生详情
        <label></label>
      </div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="new_item">
      <form role="form" class="form-horizontal">
        <div class="form-group">
          <label for="" class="col-sm-1 control-label">头像：</label>
          <div class="col-sm-2">
            <div class="sel_box">
              <!--/.img-face-t(v-bind:style="{backgroundImage:'url(' +data.photoSUrl+')' }")--><img v-bind:src="data.head_img_L" style="width:120px;height:120px;" class="doc_headimg"/>
            </div>
          </div>
          <div class="col-sm-3">
            <input name="file" type="file" class="test form-control layui-upload-file"/>
          </div>
          <div class="col-sm-3">
            <p>医生姓名： {{data.name}}</p>
            <p>手机号： {{data.mobile}}</p>
            <p>地  区： {{data.address}}</p>
            <p>网诊诊费：
              <input v-model="data.web_amount"/>
            </p>
            <p>视频问医：
              <input v-model="data.video_amount"/>
            </p>
            <p>职称：
              <select v-model="data.titleId" v-on:change="titleEdit(data.titleId)" class="form-control">
                <option v-for="t in data.title" v-bind:value="t.id">{{t.name}}</option>
              </select>
            </p>
            <p>介绍信息：
              <textarea style="width: 500px; height: 300px;" v-model="data.intro"></textarea>
            </p>
            <p>患者推荐指数：
              <p>患者正常推荐指数:
                <p class="star_{{data.level}}"></p>
                <p>{{data.level}}</p>
              </p>
              <p>后台自定义推荐指数:
                <p class="star_{{data.diy_level}}"></p>
                <p>
                  <select v-model="data.diy_level" class="form-control">
                    <option v-bind:value="1">1</option>
                    <option v-bind:value="2">2</option>
                    <option v-bind:value="3">3</option>
                    <option v-bind:value="4">4</option>
                    <option v-bind:value="5">5</option>
                  </select>
                </p>
              </p>
              <p>是否使用后台自定义方式:
                <input type="radio" name="use_diy" v-bind:checked="data.use_diy==1" v-bind:value="1"/><span>否</span>
                <input type="radio" name="use_diy" v-bind:checked="data.use_diy==0" v-bind:value="0"/><span>是</span>
              </p>
            </p>
            <p>是否可以在聊天中查看患者门诊处方及病历:
              <input type="radio" name="read_recipe" v-bind:checked="data.read_recipe==0" v-bind:value="0"/><span>否</span>
              <input type="radio" name="read_recipe" v-bind:checked="data.read_recipe==1" v-bind:value="1"/><span>是</span>
            </p>
            <button type="button" @click="doctorEdit(data)" style="margin-top:10px" class="btn btn-primary">保存</button>
          </div>
        </div>
      </form>
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab1" role="tab" data-toggle="tab">个人信息</a></li>
        <li><a href="#tab6" role="tab" data-toggle="tab">医生所属科室</a></li>
        <li><a href="#tab2" role="tab" data-toggle="tab">医生擅长</a></li>
        <li><a href="#tab3" role="tab" data-toggle="tab">医生排班</a></li>
        <li><a href="#tab4" role="tab" data-toggle="tab">出诊诊所</a></li>
        <!--li
        a(href="#tab5" role="tab" data-toggle="tab") 休息记录
        
        -->
      </ul>
      <div class="tab-content">
        <div id="tab1" class="tab-pane fade in active">
          <form role="form" class="form-horizontal">
            <!--.form-group
            label.col-sm-1.control-label(for='') 介绍信息：
            .col-sm-10
                textarea(style="width: 500px; height: 300px;",v-model="data.intro")
                button.btn.btn-primary(type='button',@click="doctorEdit(data)",style="margin-top:10px") 保存
            -->
            <div class="form-group">
              <label for="" style="float:none" class="col-sm-1 control-label">认证信息：</label>
              <div class="col_box">
                <div class="labels">
                  <div class="sel_box clearfix">
                    <div class="ys_title">医师执业证书：</div>
                    <div v-for="img in data.profession_auth" track-by="$index" class="img-face2"><img v-bind:src="img"/></div>
                  </div>
                </div>
              </div>
              <div class="col_box">
                <div class="labels">
                  <div class="sel_box clearfix">
                    <div class="ys_title">医师资格证书：</div>
                    <div v-for="img in data.qualification_auth" track-by="$index" class="img-face2"><img v-bind:src="img"/></div>
                  </div>
                </div>
              </div>
              <div class="col_box">
                <div class="labels">
                  <div class="sel_box clearfix">
                    <div class="ys_title">专业技术资格证书：</div>
                    <div v-if="data.major_qualification_auth" class="img-face2"><img v-bind:src="data.major_qualification_auth"/></div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div id="tab6" class="tab-pane fade">
          <div class="col-sm-2">
            <button type="button" @click="addSection()" class="btn btn-primary">添加</button>
          </div>
          <table class="table table-bordered check_list">
            <thead>
              <tr>
                <th class="col-sm-1">序号</th>
                <th class="col-sm-1">名称</th>
                <th class="col-sm-1">操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="val in sections">
                <td>{{$index +1}}</td>
                <td>{{val.name}}</td>
                <td><span @click="delDisease(val.id, 'section')" class="doc_red">删除</span></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div id="tab2" class="tab-pane fade">
          <div class="col-sm-2">
            <button type="button" @click="adDisease()" class="btn btn-primary">添加</button>
          </div>
          <table class="table table-bordered check_list">
            <thead>
              <tr>
                <th class="col-sm-1">序号</th>
                <th class="col-sm-1">名称</th>
                <th class="col-sm-1">操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="val in diseases">
                <td>{{$index +1}}</td>
                <td>{{val.name}}</td>
                <td><span @click="delDisease(val.id, 'disease')" class="doc_red">删除</span></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div id="tab3" class="tab-pane fade">
          <div class="col-sm-2"></div>
          <table class="table table-bordered check_list">
            <thead>
              <tr>
                <th class="col-sm-1">序号</th>
                <th class="col-sm-1">出诊开始时间</th>
                <th class="col-sm-1">出诊结束时间</th>
                <th class="col-sm-1">出诊诊所</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="val in schedules">
                <td>{{$index +1}}</td>
                <td>{{val.start_time}}</td>
                <td>{{val.end_time}}</td>
                <td>{{val.clinque.name}}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div id="tab4" class="tab-pane fade">
          <div class="col-sm-2"></div>
          <table class="table table-bordered check_list">
            <thead>
              <tr>
                <th class="col-sm-1">序号</th>
                <th class="col-sm-1">出诊诊所</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="val in cliniques">
                <td>{{$index +1}}</td>
                <td>{{val.name}}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div id="tab5" class="tab-pane fade">
          <div class="col-sm-2"></div>
          <table class="table table-bordered check_list">
            <thead>
              <tr>
                <th class="col-sm-1">序号</th>
                <th class="col-sm-1">开始时间</th>
                <th class="col-sm-1">结束时间</th>
                <th class="col-sm-1">休息天数</th>
                <th class="col-sm-1">操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="val in leave">
                <td>{{$index +1}}</td>
                <td>{{val.start_time}}</td>
                <td>{{val.end_time}}</td>
                <td>{{val.day}}</td>
                <td>
                  <select v-model="val.status" v-on:change="save(val.id,val.status)" class="form-control">
                    <option value="0">待审核</option>
                    <option value="1">审核通过</option>
                    <option value="2">审核不通过</option>
                  </select>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <exportcheck :id.sync="id"></exportcheck>
      <addsection :id.sync="id"></addsection>
    </div>
  </div>
</template>
<script type="text/javascript">
  import exportcheck from "./module/export_check.vue";
  import addsection from "./module/add_section.vue";
  export default{
      created(){
          this.id = this.$route.params.id;
  
          this.$http.get('doctor/show/' + this.id).then(function (res) {
              this.$set('data', res.data.data);
              this.$set('diseases', res.data.data.diseases);
              this.$set('sections', res.data.data.sections);
              this.$set('schedules', res.data.data.schedules);
              this.$set('cliniques', res.data.data.cliniques);
              this.$set('leave', res.data.data.leave);
  
             this.$nextTick(function(){
  
                 this.uploadFile()
  
             })
  
          })
      },
      components: {
          exportcheck,
          addsection,
      },
      ready(){
          headNav(0);
      },
      data(){
          return {
              id: 0,
              aid: 0,
              title: 0,
              data: {},
              leave: {},
              diseases: {},
              sections: {},
              schedules: {},
              cliniques: {},
              item: {},
              cur: 0,
              all: 0,
              total: 0,
              page: 1,
              time: ''
          }
      },
      events: {
          refreshList() {
              this.getDate();
          }
      },
      watch: {
  
      },
      methods: {
          getDate(){
              this.$http.get('doctor/show/' + this.id).then(function (res) {
                  this.$set('data', res.data.data);
                  this.$set('diseases', res.data.data.diseases);
                  this.$set('sections', res.data.data.sections);
                  this.$set('schedules', res.data.data.schedules);
                  this.$set('cliniques', res.data.data.cliniques);
                  this.$set('leave', res.data.data.leave);
              })
          },
          doctorEdit(){
              var data={};
              data.web_amount = this.data.web_amount * 100;
              data.video_amount = this.data.video_amount * 100;
              data.title = this.title;
              data.intro = this.data.intro;
              data.head_img_L = this.data.head_img_L;
              data.level = this.data.level;
              data.diy_level = this.data.diy_level;
              data.use_diy = $('input[name="use_diy"]:checked').val();
              data.read_recipe = $('input[name="read_recipe"]:checked').val();
              this.$http.put('doctor/update/' + this.id, data).then(function (res) {
                  layer.msg(res.data.msg);
              })
          },
          adDisease() {
              $("#exportcheck").modal("show");
          },
          addSection() {
              $("#addsection").modal("show");
          },
          delDisease(id, type) {
              var data = {};
              data.type = type;
              this.$http.put('doctor/deldisease/' + this.id + "/" + id, data).then(function (res) {
                  if(res.data.status) {
                      this.getDate();
                  }else{
                      layer.msg(res.data.msg);
                  }
              })
          },
          titleEdit(titleId){
              this.title = titleId;
          },
          save(id,status){
              var data = {};
              data.status = status;
              this.$http.put('doctor/leave/' + id, data).then(function (res) {
                  layer.msg(res.data.msg);
              })
          },
          uploadFile() {
              var index = layer.load(1, {
                 shade: [0.1, '#fff'] //0.1透明度的白色背景
              });
              var vue = this;
              layui.use('upload', function () {
                  layui.upload({
                      url: '/api/upload/img'
                      , elem: '.test' //指定原始元素，默认直接查找class="layui-upload-file"
                      , method: 'post'
                      , before: function (input) {
  
                      }
                      , success: function (res) {
                          vue.data.head_img_L = res.data;
                          $('.doc_headimg').attr('src', res.data);
                          $('.doc_headimg').show();
                      }
                  });
              })
          }
      }
  }
  //sss
</script>