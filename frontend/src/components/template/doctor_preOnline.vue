<template lang='jade'>
    .fixbody.doctor_outline.preOnline
        header
            .left(onclick="back()")
                i.icon-arrow-left
            .center {{type ==3?'视频问医':'远程问诊'}}
        #wrap
            .doc_banner
                .avatar(v-if="info.photoSUrl" v-bind:style="bg(info.photoSUrl)")
                .avatar(v-else v-bind:style="bg('/img/doctor_default.png')")
                h1
                    b {{info.name}}
                    //sub {{info.title}}
                span.stars(v-bind:show="info.level")
                    i.icon-nav1
                    i.icon-nav1
                    i.icon-nav1
                    i.icon-nav1
                    i.icon-nav1
                p.tit {{section}}
                    span {{info.titleName}}
            ul.list-group
                li
                    a {{type==3?'视频费':'咨询费'}}
                        .val.price_cor {{type==3?info.video_amount:info.web_amount | netReturnMoney}}

                li.rest(v-if="info.net_chat == 3")
                  i.icon-warning
                  span 休息中  2017-02-24 17:00 至 2017-02-27 17:00

            .btn.btn-block(v-if="subscribe_id > 0" @click="again(info.net_return_money)")
                span 复诊
            .btn.btn-block(v-else @click="online()")
                span {{type ==3?'视频问医':'在线咨询'}}
            .btn.btn-block.btn_cor(v-if="info.net_chat == 3")
                span(v-if="subscribe_id > 0") 复诊
                span(v-else) {{type ==3?'视频问医':'在线咨询'}}

</template>
<script>
    import {errorMsg} from '../../vuex/store';
    export default {
        data(){
            return {
              info: [],
              diseases: {},
              again_type:'subscribe',
              section: ''
            }
        },
        filters:{
            netReturnMoney(value){
                return value>0 ? '￥'+value : '免费'
            }
        },
        created(){
            console.log(JSON.stringify(this.$route.query));
            this.id = this.$route.query.id;
            this.type = this.$route.query.type;
            this.subscribe_id = this.$route.query.subscribe_id;
            this.subscribe_type = this.$route.query.subscribe_type;
            this.getDetail();
        },
        methods:{
            getDetail() {
                this.$http.get(this.$store.state.apiUrl+'doctor/detail/'+ this.id+'?include=diseases,sections').then(function (res) {
                    this.info = res.data.data;
                    this.info.local_type = this.type;
                    if(res.data.data.diseases){
                      this.diseases = res.data.data.diseases.data;
                    }
                    if(res.data.data.sections){
                      if(res.data.data.sections.data[0]){
                        this.section = res.data.data.sections.data[0].name;
                      }

                    }
                },function (response) {
                    errorMsg(response.data.data.errors);
                });
            },
            bg: function (url) {
                if (url) return 'background-image:url(' + url + ')'
            },
            again:function(clinic_money){
                this.$http({url:this.$store.state.apiUrl+'clinic/again',method:'GET',params:{id:this.subscribe_id,type:this.again_type}}).then(function (res) {
                    if(res.data.status){
                        this.$router.push({path:'/payment',query: { id: res.data.data.order_sn,type:this.subscribe_type,clinic_money:clinic_money}});
                    }else{
                        $api.pop(res.data.msg);
                    }
                },function (response) {
                    errorMsg(response.data.data.errors);
                });
            },
            online(){

                var self = this

                this.$http.get(this.$store.state.apiUrl+'bespeak/can/'+this.id).then(function (result) {

                   console.log(result)

                  if(result.data.status){
                    
                    

                  }else{

                    $api.pop(result.data.msg);

                  }

                });
                if(this.$store.state.tcmuser && true){
                    this.$router.push({path:'/doctor/online',query: { id: this.id,type:this.$route.query.type}});//
                }else{
                    window.location.href = '/wechat/doctor/online?id=' + this.id+'&type='+this.$route.query.type;
                }
                //this.$router.push({path:'/doctor/online',query: { id: this.id, type:this.type }});//
            }
        }
    };

</script>
