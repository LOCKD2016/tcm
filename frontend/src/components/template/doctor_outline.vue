<template lang='jade'>
    .fixbody.doctor_outline
        header
            .left(onclick="back()")
                i.icon-arrow-left
            .center 预约医生
        #patSet.pop
        .calendar_bg
        #wrap
            .doc_banner
                .avatar(v-if="doctor.photoSUrl" v-bind:style="bg(doctor.photoSUrl)")
                .avatar(v-else v-bind:style="bg('/img/doctor_default.png')")
                h1
                    b {{doctor_name}}医师
                p
                    span 患者推荐指数：
                    span.stars(v-bind:show="doctor.level")
                        i.icon-nav1
                        i.icon-nav1
                        i.icon-nav1
                        i.icon-nav1
                        i.icon-nav1

                p.tit(v-if="doctor.sections") {{doctor.sections.data[0].name}}
                    span {{doctor.title}}

            #step2
                ul.list-group
                    li(@click="hospital()")
                        span 就诊医院
                        .val {{hospital_name}}
                        i.icon-arrow-right
                    li(v-if="this.schedules.length>0" @click="dateSet()")
                        span 就诊日期
                        .val(v-html="tms(scheduleDate)")
                        i.icon-arrow-right
                    li.li_jz(v-if="this.schedules.length>0")
                        span.s 就诊时间
                        .jz_time
                            template(v-for="(s,ind) in schedulingTime")
                                span.timeactive(@click="timeActive(ind)") {{s}}
                    li.li_js(v-else-if="this.no_available_time==1")
                        span.s  抱歉，您预约的医生没有时间
            #step1.none
                .doc_main
                    .btn_toggle
                        span 该医生近一月内在该医馆无门诊信息

            .btn.btn-fix(v-if="this.schedules.length>0" @click="confirmOrder()") 确定预约

        //出诊医馆
        .hospital.none
           header
              .left(@click="backTo()")
                  i.icon-arrow-left
              .center 就诊医馆
           ul.addrs
               li(v-for="(h,ind) in hospitals" v-bind:class="hospital_id == h.id ? 'active':''" @click="setClinic(ind,h.id,h.name)")
                  h3 {{h.name}}
                  p {{h.address}}
                  i.icon-check-c
                  span.addr_a
        //就诊日期
        .dateSet.none
           header
              .left(@click="backTo2()")
                  i.icon-arrow-left
              .center 就诊日期
           .top_nav
               p.tit 仅开放一月内的门诊预约
               .box.box_2
                   .pricesBox.clearfix
                       .prices(v-for="(s,ind) in schedules" v-bind:value="ind"  @click="changeTime($event)" v-bind:class="{active:ind==schedule_set}" v-html="tms(s.date)")
                           span
                           i.icon-check

        .layer_pop.none
            .content
                .txt 请先去完善信息！
                .pop_btn.clearfix
                    .p_btn.l(@click="dodel()") 确定
                    .p_btn(@click="canceldel()") 取消


</template>
<script>
    import {errorMsg} from '../../vuex/store';
    export default {
        data(){
            return {
                //资源
                doctor:[], //医生详情
                hospitals:[], //所有诊所
                schedules:[], // 该医生排班信息
                schedulingTime:{}, //该医生所有的某一天的所有预约时间
                //键值
                hospitals_set: 0, //别选中诊所的键值
                schedule_set: 0,//预约日期 默认第一个
                //传递到后台的数据
                hospital_id: 0, // 诊所id
                hospital_name: '', // 诊所名称
                scheduleDate: '', //预约日期
                scheduleTime: '',  // 预约时间
                doctor_name: '',
                container_arr : [],
                no_available_time: 0 //是否有就诊时间 1没有

            }
        },
        created(){
            this.id = this.$route.query.id;
            this.type = this.$route.query.type;
            this.scheduleDate = this.$route.query.date;
            this.hospital_id = this.$route.query.clinicId;
            this.getDetail();
        },
        methods:{
            //确定  取消
            canceldel(){
                $('.layer_pop').addClass('none');
            },
            dodel(){
                $('.layer_pop').addClass('none');
                this.$router.push({path:'/my_fmld/my'});
            },

            getDetail() {
                this.$http.get(this.$store.state.apiUrl+'doctor/detail/'+ this.id+'?include=cliniques').then(function (res) {
                    if(res.data.status){
                        this.doctor = res.data.data;
                        this.doctor_name = res.data.data.name;
                        this.hospitals = res.data.data.cliniques.data; // 诊所信息
                        // if(this.hospital_id){
                        //     for(var i=0; i<this.hospitals.length;i++){
                        //         if(this.hospitals[i].id == this.hospital_id){
                        //             this.hospital_name = this.hospitals[i].name;
                        //         }
                        //     }
                        // }else{
                        //     this.hospital_id = res.data.data.cliniques.data[0].id;
                        //     this.hospital_name = res.data.data.cliniques.data[0].name;
                        // }
                        this.hospital_id = res.data.data.cliniques.data[0].id;
                        this.hospital_name = res.data.data.cliniques.data[0].name;

                        if(this.hospital_id > 0){
                          this.getSchedules();
                        }

                    }else{
                        $('#step1').removeClass('none');
                    }
                    this.status = res.data.status;
                },function (response) {
                    errorMsg(response.data.data.errors);
                });
            },
            // 获取排班时间
            getSchedules() {
                this.$http({url:this.$store.state.apiUrl+'schedule/lists/'+ this.id + '/' + this.hospital_id, method:'GET'}).then(function (res) {
                    var dates = res.data.data;
                    var unique_dates = [];
                    for(var i=0; i<dates.length;i++){
                      if(this.container_arr.indexOf(dates[i].date) == -1){
                        this.container_arr.push(dates[i].date);
                        unique_dates.push(dates[i])
                      }

                    }
                    this.schedules = unique_dates;
                    if(!this.scheduleDate){
                        this.scheduleDate = res.data.data[0].date;
                        if(this.scheduleDate){
                          this.getScheduleTime();
                        }
                    }else{
                      this.getScheduleTime();
                    }
                    if(this.schedules.length == 0){
                      this.no_available_time = 1;
                    }

                },function (response) {
                    errorMsg(response.data.data.errors);
                });
            },
            //获取就诊时间
            getScheduleTime(){
                this.$http({url:this.$store.state.apiUrl+'schedule/detail/'+this.id + '/'+ this.hospital_id + '/'+ this.scheduleDate, method:'GET'}).then(function (res) {
                    this.schedulingTime = res.data.data.list;
                },function (response) {
                    errorMsg(response.data.data.errors);
                });
            },
            setClinic(ind,clinicId,store){
                this.hospitals_set = ind;
                this.hospital_id = clinicId;
                this.hospital_name = store;
                $(".hospital").addClass("none");
            },
            bg: function (url) {
                if (url) return 'background-image:url(' + url + ')'
            },
            tms: function (d,t) {
                var _d = new Date(d),_t='上午',we=['日','一','二','三','四','五','六'];
                if(t==0){
                    _t='上午';
                }else if(t==1){
                    _t='下午';
                }else if(t==2){
                    _t='夜间';
                }else if(t==3){
                    _t='全天';
                }
                return _d.getMonth()+1+'月'+ _d.getDate()+'日 周'+ we[_d.getDay()];
            },
            changeTime:function (event) {

                this.schedule_set = $(event.currentTarget).attr("value");
                this.scheduleDate = this.schedules[this.schedule_set].date;

                this.getScheduleTime()

                $(".dateSet").addClass("none");
            },
            timeActive(i){
                $(".timeactive").eq(i).addClass('active');
                $(".timeactive").eq(i).siblings().removeClass('active');
                this.scheduleTime = $(".timeactive").eq(i).html();

                console.log(this.scheduleTime)
            },
            hospital:function () {
                $('.hospital').removeClass("none");
            },
            backTo:function () {
                $('.hospital').addClass("none");
            },
            dateSet:function () {
              $("body").addClass("calenOpen");
              $('.dateSet').removeClass("none");
              //$('.calendar').addClass('active')
            },
            backTo2:function () {
                $('.dateSet').addClass("none");
                //$('.calendar').removeClass('active')
            },
            peopleSet:function () {
              $('.peopleSet').removeClass("none");
            },
            backTo3:function () {
                $('.peopleSet').addClass("none");
            },
            confirmOrder:function(){
                if(!this.hospital_id){
                    $api.pop('请选择就诊诊所');
                    return false;
                }if(!this.id){
                    $api.pop('请选择就诊医生');
                    return false;
                }if(!this.scheduleDate){
                    $api.pop('请选择就诊日期');
                    return false;
                }if(!this.scheduleTime){
                    $api.pop('请选择就诊时间');
                    return false;
                }

                 var self = this;
                 var obj = {};
                 obj.doctor_id = parseInt(this.id);
                 obj.date = this.scheduleDate;
                 obj.time = this.scheduleTime;
                 obj.clinique_id = parseInt(this.hospital_id);
                 this.$http.post(this.$store.state.apiUrl+'bespeak/clinic',obj).then(function (res,error) {

                     if(res.data.status){
                         if(!res.data.data.bespeak_id){
                             $api.pop('获取预约ID失败');
                             return false;
                         }
                         this.$router.push({path:'/confirmOrder',query: {
                             clinique_id: self.hospital_id,
                             doctor_id: self.id,
                             date: self.scheduleDate,
                             time:self.scheduleTime,
                             doctor:self.doctor_name,
                             bespeak_id: res.data.data.bespeak_id,
                             clinque:self.hospital_name}
                         });
                     }else{
                         $api.pop(res.data.msg);
                     }
                 },function (response) {
                     errorMsg(response.data.data.errors);
                 });
            }
        }
    };
</script>
