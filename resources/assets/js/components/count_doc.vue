
<template>
  <div class="container main_warp">
    <div class="tit_nav">
      <div class="container clearfix">
        <div class="pull-left">医师统计</div>
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
          <div class="col-sm-2">
            <div class="input-group">
              <input type="search" v-model="name" placeholder="输入医生姓名" class="form-control auto_inp"/>
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
              <div @click="getDate()" class="input-group-btn">
                <div class="btn btn-default"><i class="icon-search"></i></div>
              </div>
            </div>
          </div>
          <div style="margin-left: 20px;" class="col-sm-2"><span>共 {{total}} 条记录</span></div>
        </dd>
      </dl>
    </div>
    <div class="user_table_box table-responsive">
      <table class="table table-bordered check_list">
        <thead>
          <tr>
            <th class="col-sm-1">医师</th>
            <th class="col-sm-1">预约人数</th>
            <th class="col-sm-1">接诊数</th>
            <!--th.col-sm-1 转诊数-->
            <th class="col-sm-1">现场看诊数</th>
            <th class="col-sm-1">远程问诊数</th>
            <th class="col-sm-1">抓药数</th>
            <th class="col-sm-1">代煎量</th>
            <th class="col-sm-1">快递量</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="val in data">
            <td>{{val.doctor}}</td>
            <td>{{val.bespeaks}}</td>
            <td>{{val.accept}}</td>
            <!--td-->
            <td>{{val.clinic}}</td>
            <td>{{val.web}}</td>
            <td>{{val.medicine}}</td>
            <td>{{val.tisane}}</td>
            <td>{{val.express}}</td>
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
          this.getDate();
      },
      ready(){
          headNav(2);//
      },
      data(){
          return{
              data:{},
              cur: 0,
              all: 0,
              total: 0,
              title: [
                  '医师',
                  '预约人数',
                  '接诊数',
                  '转诊数',
                  '现场看诊数',
                  '远程问诊数',
                  '抓药数',
                  '代煎量',
                  '快递量'
              ],
              time:{
                  startTime: '',
                  endTime: ''
              },
              name: ''
          }
      },
      watch: {
  
      },
      events: {
          update(){
              this.getDate();
          }
      },
      methods:{
          getDate(page=''){
              if(page){
                  this.page = page;
              }
              this.$http({url:'count/doctor',method:'GET',params:{page:this.page,name:this.name,time:this.time}}).then(function (res) {
                  this.$set('data',res.data.data.data);
                  this.$set('cur', res.data.data.current_page);
                  this.$set('all', res.data.data.last_page);
                  this.$set('total', res.data.data.total);
              })
          },
          exports(){
              var title = '医师统计';
              var width = {'A': 10, 'B': 10, 'C': 10, 'D': 10, 'E': 10, 'F': 10, 'G': 10, 'H': 10, 'I': 10};
              this.$http({
                  url: 'count/exports',
                  method: 'GET',
                  params: {title: title, head: this.title, data: this.data, width: width}
              }).then(function (res) {
                  if (res.data.errcode == 200) {
                      location.href = "/api/upload/download/" + res.data.data;
                  } else {
                      layer.msg(res.data.msg);
                  }
              });
          },
          listen(data){
              this.getDate(data);
              this.$router.go({name: 'count_doc', params: {id: data}});
          }
  
      }
  }
</script>