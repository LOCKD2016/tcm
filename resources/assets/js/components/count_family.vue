
<template>
  <div class="container main_warp">
    <div class="tit_nav">
      <div class="container clearfix">
        <div class="pull-left">患者统计</div>
      </div>
    </div>
    <div class="user_table_box table-responsive">
      <div class="search_box">
        <dl>
          <dd class="row">
            <div class="form-group">
              <label for="" class="col-sm-1 control-label"><span></span>选择地区：</label>
              <div class="col-sm-1">
                <select id="s_province" name="s_province" class="form-control"></select>
              </div>
              <div class="col-sm-1">
                <select id="s_city" name="s_city" class="form-control"></select>
              </div>
              <div class="col-sm-1">
                <select id="s_county" name="s_county" class="form-control"></select>
              </div>
              <div class="col-sm-1">
                <div class="input-group">
                  <div @click="getDate()" class="input-group-btn">
                    <div class="btn btn-default"><i class="icon-search"></i></div>
                  </div>
                </div>
              </div>
            </div>
          </dd>
        </dl>
      </div>
      <div id="first"></div>
      <div id="two"></div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default{
      created(){
          this.getDate();
      },
      ready(){
          headNav(2);
          _init_area();
      },
      data(){
          return{
              id:0,
              man:[],
              woman:[],
              max:0,
              sumMan:0,
              sumWoman:0,
              area:{}
          }
      },
      methods:{
          initIChart(){
              var data = [
                  {
                      name: '男'+ this.sumMan +'人',
                      value: this.man,
                      color: '#47b2c8'
                  },
                  {
                      name: '女' + this.sumWoman + '人',
                      value: this.woman,
                      color: '#db6086',
                  }
              ];
              var sumMan = this.sumMan;
              var sumWoman = this.sumWoman;
              var max = this.max;
              var chart = new iChart.BarMulti2D({
                  render: 'first',
                  data: data,
                  labels: ["60岁以上","45-60岁","31-45岁","19-30岁", "13-18岁","0-12岁"],
                  title: {
                      text: '患者年龄/性别分布图',
                      color: '#585757'
                  },
                  sub_option: {
                      border: {
                          enable: true,
                          color: '#fcfcfc'
                      },
                      listeners: {
                          parseText: function (r, t) {
                              return t + "人"+ (t*100/(sumWoman+sumMan)).toFixed(2)+"%";
                          }
                      }
                  },
                  width: 1200,
                  height: 600,
                  background_color: '#ffffff',
                  legend: {
                      enable: true,
                      background_color: null,
                      border: {
                          enable: false
                      }
                  },
                  coordinate: {
                      scale: [{
                          position: 'bottom',
                          start_scale: 0,
                          end_scale: max,
                          scale_space: max/10
                      }],
                      background_color: null,
                      axis: {
                          width: 0
                      },
                      width: 800,
                      height: 400
                  }
              });
              chart.draw();
          },
          getDate(){
              var search = {};
              if($("#s_province").val() != '省份'){
                  search.province = $("#s_province").val();
              }
              if ($("#s_city").val() != '地级市') {
                  search.city = $("#s_city").val();
              }
              if ($("#s_county").val() != '市、县级市') {
                  search.area = $("#s_county").val();
              }
              this.$http({
                  url:'count/user',
                  method:'GET',
                  params:{search:search}
              }).then(function (res) {
                  this.$set('man',res.data.data[0]);
                  this.$set('woman',res.data.data[1]);
                  this.$set('max',res.data.data[2]);
                  this.$set('sumMan',res.data.data[3]);
                  this.$set('sumWoman',res.data.data[4]);
                  this.initIChart();
              })
          },
          exportData(){
              this.searchs.name = this.name;
              this.searchs.type = this.type;
              this.$http({
                  url: 'export/export',
                  method: 'GET',
                  params: {search: this.searchs}
              }).then(function (res) {
                  if (res.data.status == 1) {
                      location.href = "/api/upload/download/" + res.data.name;
                  }
              })
          }
      }
  }
</script>