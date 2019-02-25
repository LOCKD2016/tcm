<template lang='jade'>
.fixbody
    header
        .left(onclick="back()")
            i.icon-arrow-left
        .center 患者评价
    #wrap.hzpj
        .swiper-container.swiper-container-list
            .orderBOX.swiper-wrapper
                .swiper-slide
                      .doc_main#dComment
                          //.comments(v-for="(it,ind) in comment")
                              .avatar(v-bind:style="bg(it.family.avatar)")
                              h2 {{it.family.name}}
                              p.lab
                                span {{it.disease}}
                              p.js {{it.content}}
                              .right
                                  span {{it.date}}
                          -for(var i=0;i<3;i++)
                            .comments
                              .avatar
                              h2 个人
                              span.time 2017-02-11
                              p.js 个人个人个人个人个人个人个人个人个人个人个人个人个人个人个人个人个人个人个人个人个人个人个人
                              .right
                                b 个人
                                p.stars_box
                                  span.td 态度
                                  span.stars
                                    i.icon-nav1.active
                                    i.icon-nav1
                                    i.icon-nav1
                                    i.icon-nav1
                                    i.icon-nav1
                          .visible 上拉加载


</template>
<script>
    export default {
        data(){
            return {
                page:1,
                comment:[],
                swiper:"",
                statusOrder:true
            }
        },
        created(){
            this.id = this.$route.query.id;
        },
        mounted(){
            this.getList();
        },
        events: {
            update(){
                this.getList();
            }
        },
        methods:{
            getList(){
                let self = this;
                let oLoadTip = $('.orderBOX').find('.visible');
                this.$http({url:this.$store.state.apiUrl+'doctors/comment/'+this.id,method:'GET',params:{page:this.page}}).then(function (res) {
                    res.data.data.list.forEach(e =>{
                        self.comment.push(e);
                    })
                    self.page ++;
                    if(self.page> res.data.data.totalPage){
                        oLoadTip.html('没有更多数据了');
                        self.statusOrder = false;
                    } else {
                        oLoadTip.html('上拉加载');
                    }
                    self.$nextTick(function(){
                        var myswiper = new Swiper('.swiper-container', {
                            direction: 'vertical',
                            slidesPerView: 'auto',
                            observer: true,
                            observeParents: true,
                            mousewheelControl: true,
                            freeMode: true,
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
                    oLoadTip.show();
                });
            },
             bg: function (url) {
                if (url) return 'background-image:url(' + url + ')'
            }
        }
    };
</script>
