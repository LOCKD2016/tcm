<template lang='jade'>
.fixbody
    header
        .left(onclick="back()")
            i.icon-arrow-left
        .center 填写收货地址
        .right(@click="confirm()") 保存
    #wrap

        ul.list-group
            li
                span 收货人
                .val
                    input(type="text" maxlength="6" v-model="user_name" placeholder="请输入收货人")
                i.icon-arrow-right
            li
                span 联系电话
                .val
                    input(type="tel" maxlength="11" v-model="mobile" placeholder="请输入手机号")
                i.icon-arrow-right
            li(@click="setAddr('#city')")
                span 所在地区
                .val#city {{province}} {{city}} {{district}}
                i.icon-arrow-right
            li
                span 街道
                .val
                    input(type="text" maxlength="30" v-model="address" placeholder="详细地址")
                i.icon-arrow-right

</template>
<script>
    import {errorMsg} from '../../vuex/store';
    export default{
        data(){
            return{
                //id: 344,
                user_name: '',
                mobile: '',
                address: '',
                province: null,
                city: null,
                district: null,
                id: 0,
                route:'',
                medicineid:0
            }
        },
        created:function(){
            if(typeof (this.$route.query.id) != 'undefined'){
                this.id = this.$route.query.id;
                this.getAddr(this.id);
            }

        },
        methods:{

            //点击保存弹出提示框

            confirm(){

                var phoneReg = /^[1][3,4,5,6,7,8][0-9]{9}$/;

                if(!this.user_name){

                    $api.pop('姓名不许为空');

                    return false

                }else if(!this.mobile){

                    $api.pop('手机号不能为空');

                    return false

                }else if(!phoneReg.test(this.mobile)){

                    $api.pop('手机号码格式不正确');

                    return false

                }else if(!this.province||!this.city){

                    $api.pop('省份不能为空');

                    return false

                }else if(!this.address){

                    $api.pop('详细地址不能为空');

                    return false

                }

                //保存后跳转地址

                this.medicineid=this.$route.query.medicineid

                this.tisane=this.$route.query.tisane

                if(this.$route.path.indexOf('myinfo')>-1){

                    this.route='/my_address/my'

                }else{

                    this.route='/prescription/my/id?id='+this.medicineid+'&express=1&tisane='+this.tisane

                }

                console.log(this.route)

                this.save()

                //显示提示框

                // if(this.route.indexOf('prescription')>-1){

                //     $('.layer_pop').removeClass('none');

                // }else{

                //     this.save()

                // }

            },

            //确认与取消事件

            // dodel(){

            //     this.save()

            //     $('.layer_pop').addClass('none');

            // },

            // canceldel(){

            //     $('.layer_pop').addClass('none');

            // },

            save:function (){
                if(this.city==''){
                    var city = this.province;
                }else{
                    var city = this.city;
                }
                if(this.id > 0){
                    this.$http({url:this.$store.state.apiUrl+'address/edit/'+this.id, method:'PUT',
                        params:{user_name:this.user_name,mobile:this.mobile,province:this.province,city:city,district:this.district,address:this.address,is_default:0,}
                    }).then(function(res){
                        if(res.data.status){
                            this.$router.back();
                        }

                    },function (res) {
                        errorMsg(res.data.data.errors);
                    });
                }else{
                    this.$http({url:this.$store.state.apiUrl+'address/save', method:'POST',
                        params:{user_name:this.user_name,mobile:this.mobile,province:this.province,city:city,district:this.district,address:this.address,is_default:0,}
                    }).then(function(res){
                        if(res.data.status){
                            this.$router.push({path:this.route});
                        }
                    },function (res) {
                        errorMsg(res.data.data.errors);
                    });
                }

            },
            getAddr(id){
                if(id){
                    this.$http.get(this.$store.state.apiUrl+'address/detail/'+id).then(function (res) {
                        this.user_name =res.data.data.user_name;
                        this.mobile =res.data.data.mobile;
                        this.address =res.data.data.address;
                        this.province =res.data.data.province;
                        this.city =res.data.data.city;
                        this.district =res.data.data.district;
                    })
                }
            },
            setAddr:function (obj, call) {
                var _self=this;
                $('input').blur();
                var levelId1 = $(obj).attr('data-province-code');
                var levelId2 = $(obj).attr('data-city-code');
                var levelId3 = $(obj).attr('data-district-code');
                var iosSelect = new IosSelect(3, [iosProvinces, iosCitys, iosCountys],
                    {
                        title: '地址选择',
                        itemHeight: 40,
                        relation: [1, 1, 0, 0],
                        oneLevelId: levelId1,
                        twoLevelId: levelId2,
                        threeLevelId: levelId3,
                        callback: function (selectOneObj, selectTwoObj, selectThreeObj) {
                            $(obj).attr('data-province-code', selectOneObj.id)
                                    .attr('data-city-code', selectTwoObj.id)
                                    .attr('data-district-code', selectThreeObj.id);
                            //vCont.info.add_city = selectOneObj.value + ' ' + selectTwoObj.value + ' ' + selectThreeObj.value;
                            _self.province = selectOneObj.value;
                            var vCs = selectTwoObj.value;
                            _self.city = vCs;
//                            if(vCs=="北京市" || vCs=="天津市" || vCs=="上海市" || vCs=="重庆市"){
//                                _self.city = ''
//                            }
                            _self.district = selectThreeObj.value;
                            if (call) {
                                call([selectOneObj.id, selectTwoObj.id, selectThreeObj.id]);
                            }
                        }
                    });
            }
        }
    };

</script>
