<template lang='jade'>
.fixbody
    header
        .left(onclick="back()")
            i.icon-arrow-left
        .center
            span 我的医生

    #wrap
        .swiper-container.swiper-container-list
            .orderBOX.swiper-wrapper
                .swiper-slide
                  ul.myDoctor
                      li(v-for="d in doctors" @click="det(d.id)")
                        .docBox.clearfix
                          .left
                            .avatar(v-bind:style="bg(d.photoSUrl)")
                            .doc
                              p {{d.name}}
                                  span {{d.titleName}}
                              span 泰和国医
                      .visible(@click='loadMore')

</template>
<script>
    export default {
        data() {
            return{
                page: 1,
                doctors:[],
                swiper:"",
                qcode: '',
                statusOrder:true
            }
        },

        mounted(){
            //$('.visible').css('display','none')
            this.getList();
        },
        methods:{
            imgScare:function(qcode){
                this.qcode = qcode;
                $('.imgScare').show();
            },
            close:function(){
                $('.imgScare').hide();
            },

            //点击加载更多

            loadMore(){

                if(this.statusOrder){

                    this.getList()

                }

            },

            getList(){
               // $('.visible').css('display','none')
                let self = this;
                let oLoadTip = $('.orderBOX').find('.visible');
                this.$http({url:this.$store.state.apiUrl+'user/doctor',method:'GET',params:{page:this.page}}).then(function (res) {
                    res.data.data.list.forEach(e =>{
                        self.doctors.push(e);
                    })

                    // setTimeout(function(){

                    //     if($('.orderBOX').height()>$('.sub-page .swiper-slide').height()){

                    //         $('.visible').css('display','none')

                    //     }else{

                    //         $('.visible').css('display','block')

                    //     }

                    // },300)

                    self.page ++;
                    if(self.page> res.data.data.totalPage){
                        oLoadTip.html('没有更多数据了');
                        self.statusOrder = false;
                    } else {
                        oLoadTip.html('点击加载');
                        oLoadTip.show();
                    }
                    self.$nextTick(function(){
                        var myswiper = new Swiper('.swiper-container-list', {
                            direction: 'vertical',
                            slidesPerView: 'auto',
                            observer: true,
                            observeParents: true,
                            mousewheelControl: true,
                            freeMode: true,
                            resistanceRatio : 0.7,
                            preventLinksPropagation : true,
                            preventClicks : true,
                            onTouchEnd: function(s){
                                var _viewHeight = s.height;
                                var _contentHeight = s.virtualSize;
                                if(s.translate <= _viewHeight - _contentHeight - 50 && s.translate < 0) {
                                    if(self.statusOrder){
                                        $(".visible").html('正在加载...');
                                        self.getList();
                                        setTimeout(function(){
                                            s.update();
                                        },400);

                                    }
                                }
                            }
                        });
                    });
                    
                    this.total = res.data.data.perPage;
                });


            },
            det:function(id){
                this.$router.push({path:'/doctor_detail',query: { id: id }});
            },
            bg: function (url) {
                if (url) return 'background-image:url(' + url + ')'
            }
        }
    };
</script>
