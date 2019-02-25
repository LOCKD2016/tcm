<template lang='jade'>
.fixbody
    header.search_header
        .left(onclick="back()")
            i.icon-arrow-left
        .center
            form(action="" class="input-kw-form")
                input(type="search" autocomplete="off" name="keyword" v-model="keyword")
            i.icon-search(@click="search()")
    #wrap
        .shuju 正在刷新...
        .swiper-container.swiper-container-list
            .orderBOX.swiper-wrapper
                .swiper-slide
                    .panel(style='background:none;')
                        h4.tit_illness.no.none 没有找到您要找的医师
                        h4.tit_illness.yes.none 我们为您精心查找了
                            span {{keyword}}
                            | 的{{total}}位医师
                        .doctor_list(v-for="list in lists")
                            .link(@click="det(list.id)")
                                .avatar(v-bind:style="bg(list.photoSUrl)")
                                h3
                                  span {{list.name}}
                                  sub {{list.titleName}}
                                  label(v-if="list.web == 1" v-bind:class="'active'") 在线
                                  label(v-if="list.clinic == 1" v-bind:class="'active'") 门诊
                                p.labBox 擅长：
                                  span(v-for="(e,index) in list.diseases.data" v-if="index<3") {{e.name}}

                                //h5(v-if="list.clinique[0] && !list.clinique[1]") {{list.clinique[0].store}}
                                //h5(v-if="list.clinique[1]") {{list.clinique[0].store}}/{{list.clinique[1].store}}
                                //p.labBox
                                    //span(v-for="(e,index) in list.expert" v-if="index<3") {{e}}
                                //.price(v-if="type==2") {{list | netMoney(list)}}
                                //.price(v-else) {{list | clinicMoney(list)}}
                                h6
                                    span 患者推荐指数
                                    .stars(v-bind:show="list.level")
                                        i.icon-nav1
                                        i.icon-nav1
                                        i.icon-nav1
                                        i.icon-nav1
                                        i.icon-nav1
                            .btn.btn-jv(v-if="type==2" @click="order(list.id)") 在线咨询
                            .btn.btn-jv(v-else @click="order(list.id)") 预约
                    .visible


</template>
<script>
    export default{
        created(){
            this.keyword = this.$route.query.keyword;
            $(".shuju").css({"display":"none","z-index":100})
        },
        data(){
            return{
                lists:[],
                page: 1,
                total: 0,
                keyword: '',
                type: 0,
                result: '', // 搜索结果医生
                recommend: 0, // 推荐医生
                swiper:"",
                statusOrder:true
            }
        },
        filters:{
            netMoney(value){
                return parseInt(value.net_money) ? "￥"+parseInt(value.net_money) : '免费';
            },
            clinicMoney(value){
                return parseInt(value.clinic_money) ? "￥" + parseInt(value.clinic_money) : '免费';
            }
        },
        mounted(){
            // $('.searchInp').bind('keypress', function(event) {
            //   if (event.keyCode == "13") {  //js监测到为为回车事件时 触发
            //     _this.getList();
            //     event.preventDefault();   //阻止页面自动刷新，重复加载
            //   }
            // });
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
                        //下拉刷新
                        // if(s.translate>50){
                        //     window.location.reload();
                        // }
                        // //上拉加载
                        // if (s.translate<0) {
                        //     setTimeout(function () {
                        //     },500)
                        // }
                    },
                      // onTouchMove:function (s) {
                      //     if(parseInt(s.translate)<0){
                      //         $(".shuju").css({"display":"none"})
                      //     }else{
                      //         $(".shuju").css({"display":"block"})
                      //     }
                      //     if(parseInt($(".swiper-slide").css("height"))>parseInt($(".swiper-wrapper").css("height"))){
                      //         if(-(parseInt(s.translate))>parseInt($(".swiper-container-list").css("height"))){
                      //             $(".visible").css({"display":"block"})
                      //         }else{
                      //             $(".visible").css({"display":"block"})
                      //         }
                      //     }
                      // }
                });
            },400);
        },
        methods:{
            getList(){
                let self = this;
                let params = 'search';
                if(this.recommend > 0){
                    params = 'recommend';
                }
                if(!this.keyword){
                    this.page = 1;
                    this.recommend = 0;
                    this.lists = [];
                    $api.pop('关键词不能为空');return false;
                }

                console.log(this.keyword)

                let oLoadTip = $('.orderBOX').find('.visible');
                this.$http({url:this.$store.state.apiUrl+'doctor/'+params+'?include=diseases',method:'GET',params:{page:this.page,name:this.keyword}}).then(function (res) {

                    if(!res.data.data.count){

                        $('.no').removeClass('none');
                        $('.yes').addClass('none');
                        return false

                    }else{

                        res.data.data.list.forEach(e =>{
                            self.lists.push(e);
                        })
                        if(res.data.data.list.length>0){
                            self.page ++;
                        }
                        if(self.page> res.data.data.totalPage){
                            oLoadTip.html('没有更多数据了');
                            self.statusOrder = false;
                        } else {
                            oLoadTip.show();
                            self.statusOrder = true;
                            oLoadTip.html('上拉加载');
                        }
                        setTimeout(function(){
                            self.swiper.update();
                        },400);
                        if(!res.data.data.totalPage){
                            $(".shuju").css({"display":"none"})
                        }
                        this.total = res.data.data.total;

                        $('.yes').removeClass('none');
                        $('.no').addClass('none');

                    }
                    
                    // this.set_show(res.data.data.count);



                });
            },
            // set_show(count){
            //     if(!count){
            //         this.recommend = 1;
            //         this.page = 1;
            //         this.lists = [];
            //         this.getList();
            //     }
            //     if(this.recommend > 0){
            //         $('.no').removeClass('none');
            //         $('.yes').addClass('none');
            //     }else{
            //         $('.yes').removeClass('none');
            //         $('.no').addClass('none');
            //     }
            // },
            order:function(id){
                this.$http.get(this.$store.state.apiUrl+'user/complete').then(function (res) {
                    if(res.data.status){
                        this.$router.push({path:'/doctor_detail',query: { id: id}});
                    }else{
                        $api.pop(res.data.msg);
                    }
                });

            },
            bg:function(url){
                if(url) {
                    return 'background-image:url('+url+')';
                }
            },
            search(){
              this.recommend = 0;
              this.getList()
            },
            det:function(id){
              this.$router.push({path:'/doctor_detail',query: { id: id ,clinicId:this.clinicid, type:this.type}});
            },
        },
        events: {
            update(){
                this.getList();
            }
        },
        watch:{
            keyword(){
                this.recommend = 0;
                this.page = 1;
                this.lists = [];
            }
        }
    }
</script>
