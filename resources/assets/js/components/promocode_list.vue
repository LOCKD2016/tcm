
<template>
  <div class="container main_warp">
    <div class="tit_nav">
      <div class="container clearfix">
        <div class="pull-left">优惠码列表</div>
      </div>
    </div>
    <div class="user_table_box table-responsive">
      <table class="table table-bordered check_list">
        <thead>
          <tr>
            <th class="col-sm-1">序号</th>
            <th class="col-sm-2">活动名称</th>
            <th class="col-sm-2">优惠金额</th>
            <th class="col-sm-3">有效期</th>
            <th class="col-sm-1">数量</th>
            <th class="col-sm-1">发放数量</th>
            <th class="col-sm-1">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(index,code) in codes">
            <td>{{index+1}}</td>
            <td>{{code.name}}</td>
            <td>￥{{code.discount}}</td>
            <td>{{code.start_time}} - {{code.end_time}}</td>
            <td>{{code.total}}</td>
            <td>{{code.num}}</td>
            <td><span v-if="code.end_time &lt; code.nowtime &amp;&amp; code.num &lt; code.total" @click="fail(code.id)">发放</span><span v-if="code.num &gt;= code.total &amp;&amp; code.end_time &gt; code.nowtime" @click="no(code.id)">发放</span><span v-if="code.end_time &lt; code.nowtime &amp;&amp; code.num &gt;= code.total" @click="fail(code.id)">发放</span><span v-if="code.end_time &gt; code.nowtime &amp;&amp; code.num &lt; code.total" @click="sendCode(code.id)">发放</span></td>
          </tr>
        </tbody>
      </table>
      <nav>
        <ul class="pagination">
          <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur" v-on:gopage="listen"></paginate>
        </ul>
      </nav>
    </div>
    <pop-sendcode :id.sync="id"></pop-sendcode>
  </div>
</template>
<script type="text/javascript">
  export default{
      created(){
          this.page = this.$route.params.id;
          this.getCode();
      },
  ready(){
          headNav(4);
      },
      data(){
          return{
              codes:{},
              cur:'',
              all:'',
              id:''
          }
      },
      watch:{
      },
      events: {
          update(){
              this.getCode(this.cur);
          }
      },
      methods:{
          sendCode(id){
              this.$set('id', id);
              $("#sendcode").modal("show");
          },
          no(){
              layer.msg('活动优惠码已发完');
          },
          fail(){
              layer.msg('活动优惠码已过期');
          },
          getCode(page=''){
              if (page) {
                  this.page = page;
              }
              this.$http({url:'promo/list',method:'GET',params:{page:this.page,search:this.searchs}}).then(function (res) {
                  this.$set('codes',res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
              })
          },
          listen(data){
              this.getCode(data);
              this.$router.go({name: 'promocode_list', params: {id: data}});
          }
  
      }
  }
</script>