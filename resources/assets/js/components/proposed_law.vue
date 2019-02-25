
<template>
  <div class="container main_warp">
    <div class="tit_nav">
      <div class="container clearfix">
        <div class="pull-left">医师方案处理</div>
      </div>
    </div>
    <div id="searchList" class="search_box">
      <dl>
        <dt>查询：</dt>
        <dd class="row">
          <div class="col-sm-2">
            <select v-model="goods_type" v-on:change="getList()" class="form-control">
              <option value="0" selected="selected">请选择购买类型</option>
              <option value="1">成人男</option>
              <option value="2">成人女</option>
              <option value="3">儿童</option>
            </select>
          </div>
          <div class="col-sm-2">
            <select v-model="done" v-on:change="getList()" class="form-control">
              <option selected="selected" value="2">请选择完成状态</option>
              <option value="1">未完成</option>
              <option value="2">已完成</option>
            </select>
          </div>
          <div class="col-sm-2">
            <select v-model="status" v-on:change="getList()" class="form-control">
              <option value="0" selected="selected">请选择处理状态</option>
              <option value="1">未处理</option>
              <option value="2">已处理</option>
              <option value="3">已发送</option>
            </select>
          </div>
        </dd>
      </dl>
      <dl>
        <dt>查询：</dt>
        <dd class="row">
          <div class="col-sm-2">
            <div class="input-group">
              <input type="search" v-model="realname" placeholder="请输入就诊人姓名" class="form-control auto_inp"/>
              <div v-on:click="getList()" class="input-group-btn">
                <div class="btn btn-default"><i class="icon-search"></i></div>
              </div>
            </div>
          </div>
          <div class="col-sm-2">
            <div class="input-group">
              <input type="search" v-model="number" placeholder="请输入订单编号" class="form-control auto_inp"/>
              <div v-on:click="getList()" class="input-group-btn">
                <div class="btn btn-default"><i class="icon-search"></i></div>
              </div>
            </div>
          </div>
          <div class="col-sm-2">
            <div class="input-group">
              <input type="search" v-model="doctor" placeholder="请输入处理人姓名" class="form-control auto_inp"/>
              <div v-on:click="getList()" class="input-group-btn">
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
            <th class="col-sm-1">序号</th>
            <th class="col-sm-1">就诊人</th>
            <th class="col-sm-1">购买类型</th>
            <th class="col-sm-1">敷贴类型</th>
            <th class="col-sm-1">穴位</th>
            <th class="col-sm-1">处理状态</th>
            <th class="col-sm-1">备注</th>
            <th class="col-sm-1">处理人</th>
            <th class="col-sm-1">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(index,list) in lists">
            <td>{{index+1}}</td>
            <td>{{list.name}}</td>
            <td>{{list.goods_type}}</td>
            <td>
              <select v-model="list.type" v-on:change="setChange(list.id,list.type,list.status)">
                <option value="0">未选择</option>
                <option value="1">温和型</option>
                <option value="2">增强型</option>
              </select>
            </td>
            <td>{{list.point_names}}</td>
            <td>{{list.status_show}}</td>
            <td>{{list.note}}</td>
            <td>{{list.doctor_id}}</td>
            <td><span @click="detail(list.id)">查看</span><span v-if="list.status==2" @click="point(list.id)">方案</span><span v-else="v-else" class="grey">方案</span><span v-if="list.status==2" @click="note(list.id)">备注</span><span v-else="v-else" class="grey">备注</span><span v-if="list.status == 2 &amp;&amp; list.type != 0 &amp;&amp; list.point_names != ''" @click="send(list.id,list.status)">发送</span><span v-if="list.type == 0 || list.point_names == '' || list.status !=2 " class="grey">发送</span></td>
          </tr>
        </tbody>
      </table>
      <nav>
        <ul class="pagination">
          <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur" v-on:gopage="listen"></paginate>
        </ul>
      </nav>
    </div>
    <pop-point :id.sync="id"></pop-point>
    <pop-Addnote :id.sync="id"></pop-Addnote>
  </div>
</template>
<script type="text/javascript">
  export default{
      ready(){
          headNav(3);
      },
      created(){
          //ssssss
          this.page = this.$route.params.id;
          this.getList();//22
      },
      data(){
          return{
              lists:{},
              cur:0,
              all:0,
              total:0,
              id:0,
              goods_type:0,
              doctor:'',
              status:0,
              done:2,
              realname:'',
              number:'',
              search:{}
          }
      },
      events: {
          refreshln(){
              this.getList(this.cur);
          }
      },
      methods:{
          setChange(id,type,status) {
              if(status ==3){
                  layer.msg('已发送 不可修改');
                  return;
              }
              var obj = {};
              obj.system = 'type';
              obj.param = {type:type};
              this.$http.post('law/update/'+id, obj).then(function (res) {
                  layer.msg(res.data.msg);
              })
          },
          send(id,status){
              if(status ==3) {
                  layer.msg('已发送');
                  return;
              }
              var obj = {};
              obj.system = 'send';
              obj.param = {status: 3};
              this.$http.post('law/update/'+id ,obj).then(function (res) {
                  layer.msg(res.data.msg);
                  if(res.data.status ==200){
                      this.$dispatch('refreshln');
                  }
              })
          },
          point(id){
              this.$http.get('law/forbid/' + id).then(function (res) {
                  if (res.data.status) {
                      this.$set('id', id);
                      $("#point").modal("show");
                  }else{
                      layer.msg(res.data.msg);
                  }
              });
  
          },
          note(id){
              this.$http.get('law/forbid/' + id).then(function (res) {
                  if (res.data.status) {
                      this.$set('id', id);
                      $("#addnote").modal("show");
                  } else {
                      layer.msg(res.data.msg);
                  }
              });
  
          },
          getList(page=''){
              if (page) {
                  this.page = page;
              }
              this.search.goods_type = this.goods_type;
              this.search.doctor = this.doctor;
              this.search.status = this.status;
              this.search.realname = this.realname;
              this.search.number = this.number;
              this.search.done = this.done;
              this.search.doctor = this.doctor;
              this.$http({url:'law/index',method:'GET',params:{page:this.page,search:this.search}}).then(function (res) {
                  this.$set('lists',res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
                  this.$set('total', pagination.total);
              })
          },
          detail(id){
              this.$http.get('law/deal/'+id).then(function (res){
                 if(!res.data.status){
                     layer.msg(res.data.msg);
                 }
              });
              this.$router.go({name: 'proposed_detail', params: {id: id}});
          },
          listen(data){
              this.getList(data);
              this.$router.go({name: 'proposed_law', params: {id: data}});
          },
  
      }
  }
</script>