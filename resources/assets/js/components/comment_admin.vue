
<template>
  <div class="container main_warp">
    <div class="tit_nav">
      <div class="container clearfix">
        <div class="pull-left">评论管理</div>
      </div>
    </div>
    <div class="search_box">
      <dl>
        <dd class="row">
          <div style="margin-bottom:0;" class="form-group">
            <div style="float: left;line-height: 30px;margin-left: 10px;"><span></span>病名：</div>
            <div class="col-sm-2">
              <input type="text" v-model="search.disease" class="form-control"/>
            </div>
          </div>
          <div style="margin-bottom:0;" class="form-group">
            <div style="float: left;line-height: 30px;margin-left: 10px;"><span></span>医生：</div>
            <div class="col-sm-2">
              <input type="text" v-model="search.doctor" class="form-control"/>
            </div>
          </div>
          <div style="margin-bottom:0;" class="form-group">
            <div style="float: left;line-height: 30px;margin-left: 10px;"><span></span>患者：</div>
            <div class="col-sm-2">
              <input type="text" v-model="search.name" class="form-control"/>
              <!--ss sss-->
            </div>
          </div>
        </dd>
      </dl>
      <dl>
        <dd class="row">
          <div style="margin-bottom:0;" class="form-group">
            <div style="float: left;line-height: 30px;margin-left: 10px;"><span></span>疗效：</div>
            <div class="col-sm-1">
              <select v-model="search.condition" @change="getData(1)" class="form-control">
                <option value="" selected="selected">全部</option>
                <!--1:痊愈: 2:有效 3：无效 4：恶化-->
                <option value="1">痊愈</option>
                <option value="2">有效</option>
                <option value="3">无效</option>
                <option value="4">恶化</option>
              </select>
            </div>
          </div>
          <div style="margin-bottom:0;" class="form-group">
            <div style="float: left;line-height: 30px;margin-left: 10px;"><span></span>开始时间：</div>
            <div class="col-sm-2">
              <input type="date" v-model="search.start" class="form-control"/>
            </div>
          </div>
          <div style="margin-bottom:0;" class="form-group">
            <div style="float: left;line-height: 30px;margin-left: 10px;"><span></span>结束时间：</div>
            <div class="col-sm-2">
              <input type="date" v-model="search.end" class="form-control"/>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="input-group">
              <div @click="getData(1)" class="input-group-btn">
                <div class="btn btn-default"><i class="icon-search"></i></div>
              </div>
            </div>
          </div>
        </dd>
      </dl>
    </div>
    <div class="user_table_box table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="col-sm-1">病名</th>
            <th class="col-sm-1">患者</th>
            <th class="col-sm-1">医生</th>
            <th style="width:120px" class="col-sm-1">评价时间</th>
            <th class="col-sm-1">疗效</th>
            <th class="col-sm-1">态度（星）</th>
            <th class="col-sm-3">评价详情</th>
            <th class="col-sm-1">审核状态</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="val in data">
            <td>{{val.disease}}</td>
            <td>{{val.user.realname}}</td>
            <td>{{val.doctor.name}}</td>
            <td>{{val.created_at}}</td>
            <td>{{val.condition}}</td>
            <td>
              <div v-bind:class="val.manner"></div>
            </td>
            <td>{{val.content}}</td>
            <td>
              <select v-model="val.status" @change="save_type(val.id, val.status)">
                <option value="0">未审核</option>
                <option value="1">审核通过</option>
                <option value="2">审核拒绝</option>
              </select>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur" v-on:gopage="listen"></paginate>
  </div>
</template>
<script type="text/javascript">
  export default {
      created(){
          this.page = this.$route.params.id;
          this.getData(1);
      },
      ready(){
          headNav(3);
      },
      data(){
          return {
              data: {},
              cur: 0,
              all: 0,
              search:{
                  doctor:'',
                  name:'',
                  disease:'',
                  condition:'',
                  start:'',
                  end:''
              }
          }
      },
      methods: {
          save_type(id,status){
              this.$http({url: 'comment/save/'+id, method: 'put', params: {status: status}}).then(function (res) {
                  if(res.data.status == 0) {
                      layer.msg(res.data.msg);
                  }
              });
          },
          getData(page=''){
              if (page) {
                  this.page = page;
              }
              this.$http({url: 'comment/comment', method: 'GET', params: {page: this.page,search:this.search}}).then(function (res) {
                  this.$set('data', res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
              });
          },
          listen(data){
              this.getData(data);
              this.$router.go({name: 'comment_admin', params: {id: data}});
          }
      }
  }
</script>