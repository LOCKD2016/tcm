<template lang='jade'>
.fixbody
    header
        router-link.left(to="/my" tag="a")
            i.icon-arrow-left
        .center 我的诊疗
    #wrap
        .swiper-container.swiper-container-list
            .orderBOX.swiper-wrapper
                .swiper-slide
                    .order_list(v-for="list in lists")
                        .mark(v-if="list.type==0" v-bind:type=2)
                        .mark(v-if="list.type==1" v-bind:type=1)
                        .head {{list.status | returnStatus}}

                        template
                            .main
                                .clinics(v-if="list.doctor")
                                    .avatar(v-if="list.doctor.data.photoSUrl" v-bind:style="bg(list.doctor.data.photoSUrl)")
                                    .avatar(v-else v-bind:style="bg('/img/doctor_default.png')")
                                    h3
                                      span {{list.doctor.data.name}}
                                      sub {{list.doctor.data.titleName}}
                                    p.labBox 擅长：
                                      span(v-for="(e,index) in list.doctor.data.diseases.data" v-if="index<3") {{e.name}}
                                    h6
                                      span 患者推荐指数：
                                      .stars(v-bind:show="list.doctor.data.level")
                                        i.icon-nav1
                                        i.icon-nav1
                                        i.icon-nav1
                                        i.icon-nav1
                                        i.icon-nav1

                            .foot(v-if="list.status == 10 && list.comment == 1")
                                .btn.btn-o.btn-jv(@click="comment(list.id)") 评价

                    .visible(@click='loadMore')

</template>
<script>
    import {errorMsg} from '../../vuex/store';
    export default {
        data() {
            return{
                lists:[],
                page: 1,
                total: 0,
            }
        },
        mounted(){
            let self = this;
            this.getList();
            setTimeout(function(){
                self.swiper = new Swiper('.swiper-container-list', {
                    direction: 'vertical',
                    slidesPerView: 'auto',
                    observer: true,
                    observeParents: true,
                    mousewheelControl: true,
                    freeMode: true,
                    freeModeMomentumRatio : 0.4,
                    freeModeMomentumVelocityRatio : 0.2,
                    resistanceRatio : 0.7,
                    preventLinksPropagation : true,
                    preventClicksPropagation : true,
                    preventClicks : true,
                    onTouchEnd: function(s){
                        var _viewHeight = s.height;
                        var _contentHeight = s.virtualSize;
                        if(s.translate <= _viewHeight - _contentHeight - 50 && s.translate < 0) {
                          if(self.statusOrder){
                            self.$nextTick(function(){
                              $(".visible").html('正在加载...');
                              self.getList();
                            })

                          }
                        }
                    },
                });
            },400);
        },
        events: {
            update(){
                this.getList();
            }
        },
        filters:{
          returnStatus(status){
            switch (status){
              case 0:
                return '诊疗未开始';
                break;
              case 5:
                return '诊疗中';
                break;
              case 9:
                return '追问中';
                break;
              case 10:
                return '诊疗结束';
                break;
            }
          }
        },
        methods:{

            //点击加载更多

            loadMore(){

                if(this.statusOrder){

                    this.getList()

                }

            },
            
            getList(){
                $('.visible').css('display','none')
                let self = this;
                let oLoadTip = $('.orderBOX').find('.visible');
                this.$http({url:this.$store.state.apiUrl+'clinic/lists?include=bespeak,user,doctor.diseases',method:'GET',params:{page:this.page}}).then(function (res) {
                    res.data.data.list.forEach(e =>{
                        self.lists.push(e);
                    })

                    // setTimeout(function(){

                    //     if($('.orderBOX').height()>$('.sub-page .swiper-slide').height()){

                    //         $('.visible').css('display','none')

                    //     }else{

                    //         $('.visible').css('display','block')

                    //     }

                    // },300)

                    if(res.data.data.list.length>0){
                        self.page ++;
                    }
                    if(self.page> res.data.data.totalPage){
                        oLoadTip.html('没有更多数据了');
                        self.statusOrder = false;
                    } else {
                        oLoadTip.show();
                        self.statusOrder = true;
                        oLoadTip.html('点击加载');
                    }
                    setTimeout(function(){
                      self.swiper.update();
                    },400);
                });
            },
            bg:function(url){
              if(url) {
                return 'background-image:url('+url+')'
              }
            },
            comment(id){
              this.$router.push({path: '/tickling',query: { id: id }});
            },
        }
    };
</script>
