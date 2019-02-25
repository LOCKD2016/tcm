<template lang='jade'>
.fixbody
    header
        router-link.left(to="/" tag="a" onclick="$('.search_bar').hide()")
            i.icon-arrow-left
        .center
            span 在线咨询
            //sub
                span 北京
                i.icon-arrow-down
        .right(@click="linkSearch()")
            i.icon-search

    #wrap
       .itemsBox.clearfix
            dl(v-for="s in sections" @click="sectionDoctor(s.disease.name)")
              dt(v-bind:style="bg(s.icon)")
              dd {{s.disease.name}}
            dl(@click="all()")
              dt(style="background-image:url('/static/img/tu.png')")
              dd 全部常见疾病
       .panel
          h4.j_doctor
            span(v-if="long>0") 近期问诊过的医师
            span(v-else) 无近期问诊过的医师

          template(v-for="lt in lists")
            .doctor_list
                .link(@click="det(lt.doctor.id)")
                    .avatar(v-bind:style="bg(lt.doctor.avatar)")
                    h3
                        span {{lt.doctor.name}}
                        sub {{lt.doctor.title}}
                    h5(v-if="lt.doctor.clinique[0] && !lt.doctor.clinique[1]") {{lt.doctor.clinique[0].store}}
                    h5(v-if="lt.doctor.clinique[1]") {{lt.doctor.clinique[0].store}}/{{lt.doctor.clinique[1].store}}
                    p.labBox
                        span(v-for="(e,index) in lt.doctor.expert" v-if="index<3") {{e}}
                    .price(v-if="parseInt(lt.doctor.clinic_money)==0") 免费
                    .price(v-else) ￥{{parseInt(lt.doctor.clinic_money)}}
                    h6
                        span 患者推荐指数
                        .stars(v-bind:show="lt.doctor.level")
                            i.icon-nav1.active
                            i.icon-nav1
                            i.icon-nav1
                            i.icon-nav1
                            i.icon-nav1
                .btn.btn-jv(@click="subscribe(lt.doctor.id)") 在线咨询
          .doc_main
            .btn_toggle
                span(@click="allDoctor()") 查看全部在线医生
                i.icon-arrow-right
                //i.icon-triangle-down


</template>
<script>
    export default {
        data(){
            return {
                lists:[],
                sections:[],
                long:0,
                name:''
            }
        },
        created(){
            this.getList();
            this.getSection();
        },
        mounted() {

        },
        methods:{
            getList:function () {
                this.$http({url:this.$store.state.apiUrl+'subscribe/doctorbyrecent', method:'GET'}).then(function(res){
                    if(res.data.status){
                        this.lists = res.data.data.list;
                        this.long = this.lists.length;
                        console.log(this.long);
                    }
                })
            },
            getSection:function () {
                this.$http({url:this.$store.state.apiUrl+'disease/diseaseseven', method:'GET'}).then(function(res){
                    if(res.data.status){
                        this.sections = res.data.data.data;
                    }
                })
            },
            subscribe:function(id){
                this.$http.get(this.$store.state.apiUrl+'users/integral').then(function (res) {
                    if(res.data.status == 1){
                        this.$router.push({path:'/doctor/preOnline',query: { id: id, type:2 }});
                    }
                });
            },
            sectionDoctor(disease){
                this.$router.push({ path: '/doctor/type/2', query:{disease:disease}});
            },
            allDoctor(){
                this.$router.push({ path: '/doctor/type/2'});
            },
            det:function(id){
                this.$router.push({path:'/doctor_detail/id',query: { id: id ,clinicId:2}});
            },
            all:function(){
                this.$router.push({path:'/doctor_illness'});
            },
            linkSearch:function (){
                this.$router.push({ path: '/search', query:{type: 2}});
            },
            bg:function(url){
                if(url){
                    return 'background-image:url('+url+')'
                }
            },

        }
    };
    </script>
