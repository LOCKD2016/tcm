<template lang='jade'>
#wrap
    header
        .left(onclick="back()")
            i.icon-arrow-left
        .center 个人资料
        .right(onclick="$('.layer_pop').fadeIn()")
            span(style="font-size: .24rem") 保存

    .tips
        //i.icon-sound
        .icon-tit
          i
        span 请认真填写，有助于医生为您更好的服务
    .layer_pop.none
        .content
            .txt.hs 请认真核实您的个人信息，是否要保存
            .pop_btn.clearfix
                .p_btn.l(@click="save()") 确定
                .p_btn(onclick="$('.layer_pop').fadeOut()") 取消
    ul.list-group
        li
            a
                span.lab *
                | 真实姓名
            .val
                input(type="text" maxlength="6" placeholder="请输入真实姓名" v-model="info.realname")
            i.icon-arrow-right
        li
            a
                span.lab *
                | 性别
            .val {{info.sex}}
            select(v-model="info.sex")
                option(value="男") 男
                option(value="女") 女
            i.icon-arrow-right

        li( v-on:click="shengri()")
            a
                span.lab *
                | 生日
            .val(data-year="" data-month="" data-date="" id="showDate")
                //span
            i.icon-arrow-right

        li
            a
                span.lab *
                | 手机号
            .val
                input(type="tel" maxlength="11" placeholder="不可修改" v-model="info.mobile" readonly)
            //i.icon-arrow-right
        li
            a
              span.lab *
              | 身份证号
            .val
                input(type="tel" maxlength="18" placeholder="请输入身份证号" v-model="info.idNo")
            i.icon-arrow-right
        li
            a 身高
            .val.cm
                input(type="tel" maxlength="3" placeholder=0 v-model="info.height")
            i.icon-arrow-right
        li
            a 体重
            .val.kg
                input(type="tel" maxlength="3" placeholder=0 v-model="info.weight")
            i.icon-arrow-right
        li(@click="setAddr('#city')")
            a 常居住地
            .val#city {{info.province || '北京市'}} {{info.city || '北京市'}} {{info.area || '东城区'}}
            i.icon-arrow-right
        //li
            a 国籍
            .val {{info.country}}
            select(v-model="info.country")
                option(v-for="it in comCountry") {{it}}
            i.icon-arrow-right
    ul.list-group


</template>
<script>
    export default{
        created:function(){
            this.id = this.$route.query.id;
            //this.nickname = window.localStorage.getItem('nickname');
            //this.headimgurl = window.localStorage.getItem('headimgurl');
        },
        mounted(){
            this.getInfo();
        },
        data(){
            return {
                family_id:0,
                id:0,
                info:{},
                data:{},
                me:{},
                detail:{},
                sex:'',
                date: [],
                birthday:'',
                nickname: '',
                headimgurl: '',
                comCountry:['中国','韩国','美国','日本','缅甸','越南','加拿大','法国','印度','德国','澳大利亚','新加坡','英国','意大利','西班牙','泰国','马来西亚','菲律宾','印度尼西亚','俄罗斯','以色列','巴基斯坦','新西兰','土耳其','伊拉克','伊朗','沙特阿拉伯']
            }
        },
        methods:{
            getInfo:function () {
                this.$http.get(this.$store.state.apiUrl+'user/detail').then(function (res) {
                    this.info = res.data.data;
                    if(this.info.height==0){
                        this.info.height=""
                    }
                    if(this.info.weight==0){
                        this.info.weight=""
                    }
                    if (this.info.sex == 0) {
                        this.info.sex = '';
                    } else if (this.info.sex == 1) {
                        this.info.sex = '男';
                    } else if (this.info.sex == 2) {
                        this.info.sex = '女';
                    }
                    this.date = this.info.birthday.split("-");
                    this.birthday = this.info.birthday;
                    this.$nextTick(function () {
                        if(this.date[2].indexOf(0) == 0){
                            this.date[2] = this.date[2].substr(1);
                        }
                        $('#showDate').html(this.date[0] + '年' + this.date[1] + '月' + this.date[2] + '日');
                    });
                });
            },
            bg: function (url) {
                if (url) return 'background-image:url(' + url + ')'
            },
            save:function (){
                if (this.info.realname) {
                    this.data.realname = this.info.realname;
                }
                let month = $('#showDate').attr('data-month') < 10 ? "0" + $('#showDate').attr('data-month') : $('#showDate').attr('data-month');
                let day = $('#showDate').attr('data-date') < 10 ? "0" + $('#showDate').attr('data-date') : $('#showDate').attr('data-date');
                if(month > 0 && day > 0) this.birthday = $('#showDate').attr('data-year') + "-" + month + "-" + day;
                if (this.birthday) {
                    this.data.birthday = this.birthday;
                }
                if (this.info.sex) {
                    this.data.sex = this.info.sex;
                    if (this.data.sex == '男') {
                        this.data.sex = 1;
                    } else if (this.data.sex == '女') {
                        this.data.sex = 2;
                    }
                }
                if (this.info.mobile) {
                    this.data.mobile = this.info.mobile;
                }
                if (!this.data.realname) {
                    $api.pop('姓名不能为空');
                    return false;
                }
                if (!this.data.sex) {
                    $api.pop('性别为必填项');
                    return false;
                }
                if (!this.data.mobile) {
                    $api.pop('手机号不能为空');
                    return false;
                }
                if (parseInt(this.info.height) > 0) {
                    this.data.height = this.info.height;
                }
                if (parseInt(this.info.weight) > 0) {
                    this.data.weight = this.info.weight;
                }
                
                this.data.province = this.info.province || '北京市'

                this.data.city = this.info.city || '北京市'

                this.data.area = this.info.area || '东城区'

                if (this.info.idNo) {
                    this.data.idNo = this.info.idNo
                }else{
                    $api.pop('身份证号不能为空');
                    return false;
                }
                this.$http.post(this.$store.state.apiUrl + 'user/edit', this.data).then(function (res) {
                    if (res.data.status == 1) {
                        $api.pop('保存成功');
                        this.$router.back();
                    } else {
                        $api.pop(res.data.msg);
                    }
                });
            },
            setAddr:function(obj, call) {
                var _this = this;
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
                            _this.info.province = selectOneObj.value;
                            _this.info.city = selectTwoObj.value;
                            _this.info.area = selectThreeObj.value;
                            if (call) {
                                call([selectOneObj.id, selectTwoObj.id, selectThreeObj.id]);
                            }
                        }
                    });
            },
            shengri:function(){
                var showDateDom = $('#showDate');
                // 初始化时间
                var now = new Date();
                var nowYear = now.getFullYear();
                var nowMonth = now.getMonth() + 1;
                var nowDate = now.getDate();
                showDateDom.attr('data-year', nowYear);
                showDateDom.attr('data-month', nowMonth);
                showDateDom.attr('data-date', nowDate);
                // 数据初始化
                function formatYear (nowYear) {
                    var arr = [];
                    for (var i = nowYear - 217; i <= nowYear + 25; i++) {
                        arr.push({
                            id: i + '',
                            value: i + '年'
                        });
                    }
                    return arr;
                }
                function formatMonth () {
                    var arr = [];
                    for (var i = 1; i <= 12; i++) {
                        arr.push({
                            id: i + '',
                            value: i + '月'
                        });
                    }
                    return arr;
                }
                function formatDate (count) {
                    var arr = [];
                    for (var i = 1; i <= count; i++) {
                        arr.push({
                            id: i + '',
                            value: i + '日'
                        });
                    }
                    return arr;
                }
                var yearData = function(callback) {
                    callback(formatYear(nowYear))
                }
                var monthData = function (year, callback) {
                    callback(formatMonth());
                };
                var dateData = function (year, month, callback) {
                    if (/^1|3|5|7|8|10|12$/.test(month)) {
                        callback(formatDate(31));
                    }
                    else if (/^4|6|9|11$/.test(month)) {
                        callback(formatDate(30));
                    }
                    else if (/^2$/.test(month)) {
                        if (year % 4 === 0 && year % 100 !==0 || year % 400 === 0) {
                            callback(formatDate(29));
                        }
                        else {
                            callback(formatDate(28));
                        }
                    }
                    else {
                        throw new Error('month is illegal');
                    }

                };

                var oneLevelId = showDateDom.attr('data-year');
                var twoLevelId = showDateDom.attr('data-month');
                var threeLevelId = showDateDom.attr('data-date');
                var iosSelect = new IosSelect(3,
                    [yearData, monthData, dateData],
                    {
                        title: '出生日期选择',
                        itemHeight: 35,
                        relation: [1, 1],
                        oneLevelId: oneLevelId,
                        twoLevelId: twoLevelId,
                        threeLevelId: threeLevelId,
                        showLoading: false,
                        callback: function (selectOneObj, selectTwoObj, selectThreeObj) {
                            showDateDom.attr('data-year', selectOneObj.id);
                            showDateDom.attr('data-month', selectTwoObj.id);
                            showDateDom.attr('data-date', selectThreeObj.id);
                            showDateDom.html(selectOneObj.value + ' ' + selectTwoObj.value + ' ' + selectThreeObj.value);
                        }
                    });
            }
        }
    };


</script>
