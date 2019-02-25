
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">聊天管理</div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="item_list">
      <div class="list">
        <div class="search_box">
          <dl>
            <dt>筛选</dt>
            <dd class="row">
              <div class="col-sm-3">
                <div class="input-group">
                  <input type="search" v-model="name" placeholder="输入医生姓名搜索" class="form-control auto_inp"/>
                  <div v-on:click="getData(1)" class="input-group-btn">
                    <div class="btn btn-default"><i class="icon-search"></i></div>
                  </div>
                </div>
              </div>
              <div class="col-sm-2"><span>共 {{total}} 条记录</span></div>
            </dd>
          </dl>
        </div>
        <div class="user_table_box table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>序号</th>
                <th>医生姓名</th>
                <th>患者昵称</th>
                <th>患者姓名</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(index, val) in data">
                <td>{{index+1}}</td>
                <td>{{val.doctor.name}}</td>
                <td>{{val.user.nickname}}</td>
                <td>{{val.user.realname}}</td>
                <td><span v-link="{ path: 'message_detail/', query: { id: val.id, doctor_name: val.doctor.name, user_name: val.user.realname} }">查看</span></td>
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
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      components:{
  
      },
      data(){ //页面用到的数据
          return {
              cur:0,
              all:0,
              total:0,
              page:1,
              data:{},
              val:{
                  content:{}
              },
              name: '',
          };
      },
      created(){//实例创建后调用
          this.page = this.$route.params.id;
          this.getData(1);
      },
      events: {
          userupdate(){
              this.getData();
          }
      },
      methods:{
          getData(page){
              console.log('****');
              this.$http({url:'message/getMessage', method:'GET', params: {page: page, name: this.name}}).then(function(res){
                  console.log('888888');
                  console.log(res);
                  this.$set('data', res.body.data.data);
                  var data = res.data.data;
                  this.$set('cur', data.current_page);
                  this.$set('all', data.last_page);
                  this.$set('total', data.total);
              });
          },
          detail(val){
              //this.$set('val', val);
              //$("#clinique").modal("show");
              var id = val.id;
              this.$router.push({
                  path: '/message',
                  query: {
                      id: id,
                  }
              });
          },
          listen(data){
              this.getData(data);
          }
      }
  }
</script>