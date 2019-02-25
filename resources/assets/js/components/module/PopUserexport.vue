
<!--项目导出-->
<template>
  <div id="userexport" class="modal fade">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">用户导出</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-inline">
            <div class="form-group">
              <label for="" class="control-label">阶段：</label>
              <select v-on:change="searchs()" v-model="" class="form-control">
                <option value="0">全部</option>
                <option value="1">备孕</option>
                <option value="2">怀孕</option>
                <option value="3">辣妈</option>
              </select>
            </div>
          </form>
          <table class="table table-bordered check_list">
            <thead>
              <tr>
                <th>用户名称</th>
                <th>手机号</th>
              </tr>
            </thead>
            <tbody>
              <tr onclick="checkTr(this)" v-for="project in projects">
                <td>{{project.project_name}}</td>
                <td>{{project.user_name}}</td>
                <td>{{project.new_month}}</td>
              </tr>
            </tbody>
          </table>
          <nav class="clearfix">
            <ul class="pagination pages">
              <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur"></paginate>
            </ul><span class="total">总数：{{num}}</span>
          </nav>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
          <button type="button" v-on:click="doAdd()" class="btn btn-primary">保存</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  import calendar from "../../calendar.vue"
  export default {
      components: {
          calendar
      },
      ready(){
          this.getItems(1);
          this.getAllUser();
      },
      data(){
          return {
              projects:{},
              cur:0,
              all:0,
              num:0,
              checkedNames:[],
              type:'',
              options:1,
              userId:0,
              startTime:'',
              endTime:'',
              sort:{},
              allUser: {},
              isAdmin: 0,
              calendar:{
                  show: false,
                  x: 0,
                  y: 0,
                  picker: "",
                  type: "date",
                  value: "",
                  begin: "",
                  end: "",
                  sep: "/",
                  weeks: [],
                  months: [],
                  range: false,
                  items: {
                      // 日期时间模式
                      picker3: {
                          type: "date",
                          value: "",
                          sep: "-",
                      }, picker2: {
                          type: "date",
                          value: "",
                          sep: "-",
                      },
                  }
              }
          };
      },
      methods: {
          open(e, type) {
              // 设置类型
              this.calendar.picker = type;
              this.calendar.type = this.calendar.items[type].type;
              this.calendar.range = this.calendar.items[type].range;
              this.calendar.begin = this.calendar.items[type].begin;
              this.calendar.end = this.calendar.items[type].end;
              this.calendar.value = this.calendar.items[type].value;
              // 可不用写
              this.calendar.sep = this.calendar.items[type].sep;
              this.calendar.weeks = this.calendar.items[type].weeks;
              this.calendar.months = this.calendar.items[type].months;
  
              this.calendar.show = true;
              this.calendar.x = e.target.offsetLeft;
              this.calendar.y = e.target.offsetTop + e.target.offsetHeight + 8;
          },
          getItems(page){
              this.$set('sort.check',this.checkedNames);
              this.$set('sort.status',this.options);
              this.$http({url: 'portfolio/out', method: 'GET', params: {page: page,sort: this.sort}}).then(function (res) {
                  this.$set('projects', res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
                  this.$set('num', pagination.total);
                  for (var i = 0; i < this.projects.length; i++) {
                      if (this.projects[0].isAdmin == 1) {
                          this.$set('isAdmin', 1);
                      }
  
                  }
              })
          },
          listen(data){
              this.getItems(data);
          },
          doAdd(){
              this.$set('sort.check', this.checkedNames);
              this.$set('sort.status', this.options);
              this.$http.post('portfolio/export/'+this.type,this.sort).then(function(res){
                  if(res.data.status == 1){
                      location.href="/api/upload/"+ res.data.name;
                  }
              });
          },
          searchs(){
              this.$set('sort.startTime',this.startTime);
              this.$set('sort.endTime', this.endTime);
              this.$set('sort.status',this.options);
              this.$set('sort.userId',this.userId);
              this.$set("cur", 1);
              this.getItems(this.cur);
          },
          getAllUser(){
              this.$http.get('user/roleUser').then(function (res) {
                  var data = res.data.data;
                  this.$set('allUser', data);
              });
          }
      },watch:{
          sort(newValue, oldValue){
              this.$set("cur", 1);
              this.getItems(this.cur);
          },
          'calendar.value': function (value) {
              this.calendar.items[this.calendar.picker].value = value
          },
          'calendar.items.picker2.value':function (newvalue,oldValue) {
              if(newvalue != oldValue){
                  this.$set('startTime',newvalue);
                  this.searchs();
              }
          },
          'calendar.items.picker3.value': function (newvalue, oldValue) {
              if (newvalue != oldValue) {
                  this.$set('endTime',newvalue);
                  this.searchs();
              }
          }
      }
  }
</script>