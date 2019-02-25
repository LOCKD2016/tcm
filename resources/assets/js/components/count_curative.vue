
<template>
  <div class="container main_warp">
    <div class="tit_nav">
      <div class="container clearfix">
        <div class="pull-left">疗效统计</div>
        <div class="pull-right"><a @click="exports()" class="btn btn-primary btn-sm"><i class="icon-plus"></i><span>导出表格</span></a></div>
      </div>
    </div>
    <div class="search_box">
      <dl>
        <dt>筛选</dt>
        <dd class="row">
          <div class="col-sm-2">
            <div class="input-group">
              <input type="search" v-model="name" placeholder="输入疾病名称" class="form-control auto_inp"/>
            </div>
          </div>
          <div class="col-sm-2">
            <div class="input-group">
              <input type="date" v-model="time.startTime" class="form-control auto_inp"/>
            </div>
          </div>
          <div class="col-sm-2">
            <div class="input-group">
              <input type="date" v-model="time.endTime" class="form-control auto_inp"/>
              <div @click="getData()" class="input-group-btn">
                <div class="btn btn-default"><i class="icon-search"></i></div>
              </div>
            </div>
          </div>
          <div class="col-sm-2"><span>共 {{total}} 条记录</span></div>
        </dd>
      </dl>
    </div>
    <div class="user_table_box table-responsive">
      <table class="table table-bordered check_list">
        <thead>
          <tr>
            <th class="col-sm-1">病名</th>
            <th class="col-sm-1">总计（例）</th>
            <th class="col-sm-1">医生（位）</th>
            <th class="col-sm-1">痊愈数</th>
            <th class="col-sm-1">好转数</th>
            <th class="col-sm-1">无效数</th>
            <th class="col-sm-1">恶化数</th>
            <th class="col-sm-1">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="val in data">
            <td>{{val.name}}</td>
            <td>{{val.num}}</td>
            <td>{{val.doc_count ? val.doc_count : 0}}</td>
            <td>{{val.cure ? val.cure: 0}}</td>
            <td> {{val.valid ? val.valid : 0}}</td>
            <td> {{val.invalid ? val.invalid : 0}}</td>
            <td> {{val.worsen ? val.worsen : 0}}</td>
            <td><span @click="detail(val.id)">查看</span></td>
          </tr>
        </tbody>
      </table>
      <nav>
        <ul class="pagination">
          <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur" v-on:gopage="listen"></paginate>
        </ul>
      </nav>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default{
      created(){
          this.page = this.$route.params.id;
          this.getData();
      },
      ready(){
          headNav(2);
      },
      data(){
          return{
              data:{},
              cur: 0,
              all: 0,
              total: 0,
              title: [
                  '病名',
                  '总计（例）',
                  '医生（位）',
                  '痊愈数',
                  '好转数',
                  '无效数',
                  '恶化数',
              ],
              time:{
                  startTime: '',
                  endTime: ''
              },
              name: ''
          }
      },
      events: {
          update(){
              this.getData();
          }
      },
      methods:{
          getData(page=''){
              if(page){
                  this.page = page;
              }
              this.$http({url:'count/curative',method:'GET',params:{page:this.page,name:this.name,time:this.time}}).then(function (res) {
                  this.$set('data',res.data.data.data);
                  this.$set('cur', res.data.data.current_page);
                  this.$set('all', res.data.data.last_page);
                  this.$set('total', res.data.data.total);
              })
          },
          exports(){
              var title = '疗效统计';
              var width = {'A': 10, 'B': 10, 'C': 10, 'D': 10, 'E': 10, 'F': 10, 'G': 10, 'H': 10, 'I': 10};
              var obj = {};
              for (var i=0; i< this.data.length;i++){
                  var name = this.data[i].name;
                  var num = this.data[i].num;
                  var doc_count = this.data[i].doc_count;
                  var cure = this.data[i].cure ?  this.data[i].cure  : 0;
                  var valid = this.data[i].valid ?  this.data[i].valid  : 0;
                  var invalid = this.data[i].invalid ?  this.data[i].invalid  : 0;
                  var worsen = this.data[i].worsen ?  this.data[i].worsen  : 0;
                  obj[i] = [name,num,doc_count,cure,valid,invalid,worsen];
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
              this.$router.go({name: 'count_curative', params: {id: data}});
          },
          detail(id){
              this.$router.go({name: 'count_curative_detail', params: {id: id,page:1}});
          }
      }
  }
</script>