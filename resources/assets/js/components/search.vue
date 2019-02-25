
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left"><span>查询 -- {{name}}</span></div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="index_nr">
      <ul class="nav nav-tabs">
        <li class="active col-sm-1"><a href="#tab1" role="tab" data-toggle="tab">用户( {{peopleNum}} )</a></li>
        <li class="col-sm-1"><a href="#tab2" role="tab" data-toggle="tab">专家( {{expertNum}} )</a></li>
        <li class="col-sm-1"><a href="#tab3" role="tab" data-toggle="tab">卡片( {{articleNum}} )</a></li>
        <!--li.col-sm-1-->
        <!--    a(href="#tab3" role="tab" data-toggle="tab") 常见问题( {{commonNum}} )-->
      </ul>
    </div>
    <div class="tab-content">
      <div id="tab1" class="tab-pane fade in active">
        <div class="item_list">
          <div class="list">
            <div class="nr_list">
              <table class="table table-bordered check_list">
                <thead>
                  <tr>
                    <th class="col-xs-1">序号</th>
                    <th>用户昵称</th>
                    <th>手机号</th>
                    <th>人生阶段</th>
                    <th>邮箱</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="person in people" @click="peopleDetail(person.id)">
                    <td>{{person.i}}</td>
                    <td>{{person.nickname}}</td>
                    <td>{{person.mobile}}</td>
                    <td>{{person.stage}}</td>
                    <td>{{person.email}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div id="tab2" class="tab-pane fade">
        <div class="item_list">
          <div class="list">
            <div class="nr_list">
              <table class="table table-bordered check_list">
                <thead>
                  <tr>
                    <th class="col-xs-1">序号</th>
                    <th>姓名</th>
                    <th>就职单位</th>
                    <th>医院科室</th>
                    <th>职称</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="e in experts" @click="expertDetail(e.id)">
                    <td>{{e.i}}</td>
                    <td>{{e.name}}</td>
                    <td>{{e.hospital}}</td>
                    <td>{{e.department}}</td>
                    <td>{{e.job}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div id="tab3" class="tab-pane fade">
        <div class="item_list">
          <div class="list">
            <div class="nr_list">
              <table class="table table-bordered check_list">
                <thead>
                  <tr>
                    <th class="col-xs-1">序号</th>
                    <th>卡片标题</th>
                    <th>卡片类型</th>
                    <th>关联医生</th>
                    <th>浏览量</th>
                    <th>帮助有用次数</th>
                    <th>帮助无用次数</th>
                    <th>收藏次数</th>
                    <th>评论次数</th>
                    <th>点赞总量</th>
                    <th>分享总量</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="a in articles" @click="articleDetail(a.id)">
                    <td>{{a.i}}</td>
                    <td>{{a.title}}</td>
                    <td>{{a.type}}</td>
                    <td>{{a.expert}}</td>
                    <td>{{a.read_num}}</td>
                    <td>{{a.help_yes_num}}</td>
                    <td>{{a.help_no_num}}</td>
                    <td>{{a.collection_num}}</td>
                    <td>{{a.comment_num}}</td>
                    <td>{{a.like_num}}</td>
                    <td>{{a.share_num}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!--.tab-pane.fade#tab4-->
      <!--    .item_list-->
      <!--        .list-->
      <!--            .nr_list-->
      <!--                table.table.table-bordered.check_list-->
      <!--                    thead-->
      <!--                        tr-->
      <!--                            th.col-xs-1 序号-->
      <!--                            th.col-xs-1 人员姓名-->
      <!--                            th.col-xs-1 跟进人-->
      <!--                            th.col-xs-1 所在公司-->
      <!--                            th.col-xs-1 状态-->
      <!--                            th 最新动态-->
      <!--                            th.col-xs-1 考虑创业方向-->
      <!--                            th 考虑加入何种公司-->
      <!--                            th.col-xs-1 备注-->
      <!--                    tbody-->
      <!--                        tr(v-for="c in commons")-->
      <!--                            td {{p.i}}-->
      <!--                            td {{p.name}}-->
      <!--                            td {{p.follow_id}}-->
      <!--                            td {{p.company}}-->
      <!--                            td {{p.status}}-->
      <!--                            td {{p.dynamic}}-->
      <!--                            td {{p.venture}}-->
      <!--                            td {{p.team}}-->
      <!--                            td {{p.remark}}-->
    </div>
  </div>
</template>
<script type="text/javascript">
  export  default{
      created(){
          this.getSearch();
      },
      data(){
          return{
              name:'',
              articles: {},
              experts: {},
              people: {},
              commons: {},
              peopleNum:0,
              expertNum:0,
              commonNum:0,
              articleNum:0
          }
      },
      methods:{
          getSearch(){
              var name  = location.search;
              name = decodeURI(name.substring(1));
              name =  name.replace(/(^\s*)|(\s*$)/g, "");
              this.name = name;
              if(name == ''){
                  layer.msg('输入内容不能为空！');
                  return false;
              }
              var obj = {};
              obj.search  = name;
               this.$http.post('operation/indexSearch', obj).then(function (res) {
                  this.$set('articles',res.data.articleData);
                  this.$set('articleNum',res.data.articleData.length);
                  this.$set('experts',res.data.expertData);
                  this.$set('expertNum',res.data.expertData.length);
                  this.$set('people',res.data.peopleData);
                  this.$set('peopleNum',res.data.peopleData.length);
                   this.$set('commons', res.data.commonData);
                   this.$set('commonNum', res.data.commonData.length);
               });
          },
          expertDetail(id){
              this.$router.go({name:'expert_detail',params:{id:id}});
          },
          articleDetail(id){
              this.$router.go({name:'editer_article',params:{id:id}});
          },
          peopleDetail(id){
              this.$router.go({name:'user_detail',params:{id:id}});
          }
      }
      /*watch:{
          name(value){
              //this.name = value;
              alert(value);
              this.getSearch();
          }
      }*/
  }
</script>