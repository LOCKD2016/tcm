
<template>
  <div class="container main_warp">
    <div class="tit_nav">
      <div class="container clearfix">
        <div class="pull-left">疗效统计详情</div>
        <div class="pull-right">
          <!--a.btn.btn-primary.btn-sm(@click="exports()")
          i.icon-plus
          span 导出表格
          -->
        </div>
      </div>
    </div>
    <div class="search_box">
      <dl>
        <dt>筛选</dt>
        <dd class="row">
          <!--.form-group(style="margin-bottom:0;")
          div(style="float:left") 治愈数：
          .col-sm-1
              select.form-control(v-model="sort")
                  option(value=1 selected) 不限
                  option(value=2) 按正序排序
                  option(value=3) 按倒序排序
          -->
          <div class="col-sm-1">起始时间：</div>
          <div class="col-sm-2">
            <div class="input-group">
              <input type="date" v-model="time.startTime" class="form-control auto_inp"/>
            </div>
          </div>
          <div class="col-sm-1">结束时间：</div>
          <div class="col-sm-2">
            <div class="input-group">
              <input type="date" v-model="time.endTime" class="form-control auto_inp"/>
              <div @click="getData()" class="input-group-btn">
                <div class="btn btn-default"><i class="icon-search"></i></div>
              </div>
            </div>
          </div>
          <div style="margin-left: 20px;" class="col-sm-2"><span>共 {{total}} 条记录</span>
            <!--ss-->
          </div>
        </dd>
      </dl>
    </div>
    <div class="user_table_box table-responsive">
      <table class="table table-bordered check_list">
        <thead>
          <tr>
            <th class="col-sm-1">医生</th>
            <th class="col-sm-1">总计</th>
            <th class="col-sm-1">痊愈数</th>
            <th class="col-sm-1">明显好转</th>
            <th class="col-sm-1">好转</th>
            <th class="col-sm-1">没变化</th>
            <!--th.col-sm-1 操作-->
          </tr>
        </thead>
        <tbody>
          <tr v-for="val in data">
            <td>{{val.name}}</td>
            <td>{{val.total}}</td>
            <td>{{val.recovery ? val.recovery: 0}}</td>
            <td> {{val.better ? val.better : 0}}</td>
            <td> {{val.good ? val.good : 0}}</td>
            <td> {{val.noChange ? val.noChange : 0}}</td>
            <!--tdspan(@click="detail(val.id)") 查看
            -->
          </tr>
        </tbody>
      </table>
      <nav>
        <ul class="pagination">
          <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur" v-on:gopage="listen"></paginate>
        </ul>
      </nav>
    </div>
    <disease_count :doctor_id.sync="doctor_id"></disease_count>
  </div>
</template>
<script type="text/javascript">
  import disease_count from "./module/disease_count.vue"
  export default{
      created(){
          this.page = this.$route.params.id;///
          this.getData();
      },
      components: {
          disease_count
      },
      ready(){
          headNav(2);
      },
      data(){
          return {
              data: {},
              cur: 0,
              all: 0,
              sort: 1,
              doctor_id: 0,
              total: 0,
              title: [
                  '医生',
                  '总计',
                  '痊愈数',
                  '好转数',
                  '无效数',
                  '恶化数',
              ],
              time: {
                  startTime: '',
                  endTime: ''
              }
          }
      },
      events: {
          update(){
              this.getData();
          }
      },
      methods: {
          detail(id){
              this.doctor_id = id;
              $('#disease_count').modal("show");
          },
          getData(page=''){
              if (page) {
                  this.page = page;
              }
              this.$http({url: 'count/comment', method: 'GET', params: {page: this.page, sort: this.sort, time: this.time}}).then(function (res) {
                  this.$set('data', res.data.data.data);
                  this.$set('cur', res.data.data.current_page);
                  this.$set('all', res.data.data.last_page);
                  this.$set('total', res.data.data.total);
              })
          },
          exports(){
              var title = '疗效统计';
              var width = {'A': 10, 'B': 10, 'C': 10, 'D': 10, 'E': 10, 'F': 10, 'G': 10, 'H': 10, 'I': 10};
              var obj = {};
              for (var i = 0; i < this.data.length; i++) {
                  var name = this.data[i].name;
                  var num = this.data[i].num;
                  var doc_count = this.data[i].doc_count;
                  var cure = this.data[i].cure ? this.data[i].cure : 0;
                  var valid = this.data[i].valid ? this.data[i].valid : 0;
                  var invalid = this.data[i].invalid ? this.data[i].invalid : 0;
                  var worsen = this.data[i].worsen ? this.data[i].worsen : 0;
                  obj[i] = [name, num, doc_count, cure, valid, invalid, worsen];
              }
              this.$http({
                  url: 'count/exports',
                  method: 'GET',
                  params: {title: title, head: this.title, data: obj, width: width}
              }).then(function (res) {
                  if (res.data.errcode == 200) {
                      location.href = "/api/upload/download/" + res.data.data;
                  } else {
                      layer.msg(res.data.msg);
                  }
              });
          },
          listen(data){
              this.getData(data);
              this.$router.go({name: 'count_curative_detail', params: {id: data}});
          }
      }
  }
</script>