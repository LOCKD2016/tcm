import Vue from 'vue'
import App from './components/Body.vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
//import marked from 'marked';
import infiniteScroll from 'vue-infinite-scroll'

import paginate from "./components/module/paginate.vue"
import PopLogdetail from "./components/module/PopLogdetail.vue"
import PopPassword from "./components/module/PopPassword.vue"
import PopUserinfo from "./components/module/PopUserinfo.vue"
import PopUseradd from "./components/module/PopUseradd.vue"
import PopUsergroup from "./components/module/PopUsergroup.vue"
import PopGroupedit from "./components/module/PopGroupedit.vue"
import PopAuth from "./components/module/PopAuth.vue"
import PopAllogistics from "./components/module/PopAllogistics.vue"//物流状态
import PopAddlogistics from "./components/module/PopAddlogistics.vue"//物流状态
import PopSendcode from "./components/module/PopSendcode.vue"//物流状态
import PopLogisticsupdate from "./components/module/PopLogisticsupdate.vue"//物流状态
import PopPoint from "./components/module/PopPoint.vue"//物流状态s
import PopAddnote from "./components/module/PopAddnote.vue"//添加备注
import PopDealnote from "./components/module/PopDealnote.vue"//添加备注
import PopAddtest from "./components/module/PopAddtest.vue"
import PopTelephone from "./components/module/PopTelephone.vue"
import express_company from "./components/module/express_company.vue"
import see_express from "./components/module/see_express.vue"


//注册全局组件
Vue.component('paginate', paginate);
Vue.component('PopAddtest', PopAddtest);
Vue.component('PopLogdetail', PopLogdetail);
Vue.component('PopPassword', PopPassword);
Vue.component('PopUserinfo', PopUserinfo);
Vue.component('PopUseradd', PopUseradd);
Vue.component('PopUsergroup', PopUsergroup);
Vue.component('PopAuth', PopAuth);
Vue.component('PopGroupedit', PopGroupedit);
Vue.component('PopAllogistics', PopAllogistics);
Vue.component('PopAddlogistics', PopAddlogistics);
Vue.component('PopSendcode', PopSendcode);
Vue.component('PopLogisticsupdate', PopLogisticsupdate);
Vue.component('PopPoint', PopPoint);
Vue.component('PopAddnote', PopAddnote);
Vue.component('PopDealnote', PopDealnote);
Vue.component('PopTelephone', PopTelephone);
Vue.component('express_company', express_company);
Vue.component('see_express', see_express);


Vue.use(VueRouter);
Vue.use(VueResource);
Vue.use(infiniteScroll);
Vue.http.options.root = '/api';
Vue.http.options.emulateJSON = true;
Vue.http.options.emulateHTTP = false;
//Vue.prototype.marked = marked;
/* eslint-disable no-new */


var router = new VueRouter({
    history: true,
    root: 'admin'
});

router.map({
    '/card_detail/:ctype/:id/:family_id': {//问诊单详情
        name: 'card_detail',
        component: require('./components/card_detail.vue')
    },
    '/agreement': {//协议管理
        component: require('./components/agreement.vue')
    },
    '/information': {//协议管理123
        component: require('./components/information.vue')
    },
    '/exam': {//系统问诊单
        component: require('./components/exam.vue')
    },
    '/exam_save/:id': {//系统问诊单修改
        name: 'exam_save',
        component: require('./components/exam_save.vue')
    },
    '/medicinal_type/:id': {//药剂管理
        name: 'medicinal_type',
        component: require('./components/medicinal_type.vue')
    },
    '/clinique': {//诊所管理1
        component: require('./components/clinique.vue')
    },
    '/auth': {//权限列表
        component: require('./components/adm_auth.vue')
    },
    '/adm_disease': {//常见疾病11
        component: require('./components/adm_disease.vue')
    },
    '/adm_log/:id': {//日志管理
        name: 'adm_log',
        component: require('./components/adm_log.vue')
    },
    '/adm_field/:id': {
        name: 'adm_field',
        component: require('./components/adm_field.vue')
    },
    '/adm_user/:id': {//管理员列表
        name: 'adm_user',
        component: require('./components/adm_user.vue')
    },
    '/adm_pri': {//权限管理
        component: require('./components/adm_pri.vue')
    },
    '/app_users/:id': {//用户管理
        name: 'app_users',
        component: require('./components/app_users.vue')
    },
    '/operation_list': {//操作日志1231
        component: require('./components/operation_list.vue')
    },
    '/user_detail/:id': {//用户详情页
        name: 'user_detail',
        component: require('./components/user_detail.vue')
    },
    '/family_detail/:id': {//患者详情页
        name: 'family_detail',
        component: require('./components/family_detail.vue')
    },
    '/user_clinic/:user_id/:family_id': {//用户诊疗
        name: 'user_clinic',
        component: require('./components/user_clinic.vue')
    },
    '/user_center': {//个人信息1
        component: require('./components/user_center.vue')
    },
    '/shop_deal/:id': {//财务管理--商城订单
        name: 'shop_deal',
        component: require('./components/shop_deal.vue')
    },
    '/send_list/:id': {//发货列表
        name: 'send_list',
        component: require('./components/send_list.vue')
    },
    '/send_recipe/:id': {//药品发货列表1
        name: 'send_recipe',
        component: require('./components/send_recipe.vue')
    },
    '/promocode_list/:id': {//优惠码列表
        name: 'promocode_list',
        component: require('./components/promocode_list.vue')
    },
    '/promocode_add': {//优惠码添加
        component: require('./components/promocode_add.vue')
    },
    '/promocode_mobile/:id': {//优惠码添加
        name: 'promocode_mobile',
        component: require('./components/promocode_mobile.vue')
    },
    '/promocode_send/:id': {//优惠码发放
        name: 'promocode_send',
        component: require('./components/promocode_send.vue')
    },
    '/lnquiry_list/:id': {//问诊单管理
        name: 'lnquiry_list',
        component: require('./components/lnquiry_list.vue')
    },
    '/lnquiry_add': {//问诊单添加
        component: require('./components/lnquiry_add.vue')
    },
    '/lnquiry_detail/:id': {//问诊单详情页
        name: 'lnquiry_detail',
        component: require('./components/lnquiry_detail.vue')
    },
    '/question_add': {//问题添加
        component: require('./components/question_add.vue')
    },
    '/question_answer/:id': {//问题详情页
        name: 'question_answer',
        component: require('./components/question_answer.vue')
    },
    '/question_list/:id': {//问题列表
        name: 'question_list',
        component: require('./components/question_list.vue')
    },
    '/proposed_law/:id': {//内容管理--方案处理
        name: 'proposed_law',
        component: require('./components/proposed_law.vue')
    },
    '/proposed_detail/:id': {//内容管理--方案处理详情
        name: 'proposed_detail',
        component: require('./components/proposed_detail.vue')
    },
    '/doctor/:id': {//医生管理
        name: 'doctor',
        component: require('./components/doctor.vue')
    },
    '/doc_detail/:id': {//医生管理
        name: 'doc_detail',
        component: require('./components/doc_detail.vue')
    },
    '/service/:id': {//客服
        name: 'service',
        component: require('./components/service.vue')
    },
    '/chat_admin/:id': {//网诊
        name: 'chat_admin',
        component: require('./components/chat_admin.vue')
    },
    '/charge_price/:id': {//划价收费
        name: 'charge_price',
        component: require('./components/charge_price.vue')
    },
    '/drug_manage/:id': {//y预约订单
        name: 'drug_manage',
        component: require('./components/drug_manage.vue')
    },
    '/drug_medicinal/:id': {//药品订单
        name: 'drug_medicinal',
        component: require('./components/drug_medicinal.vue')
    },
    '/drug_pay/:id': {//会员卡订单
        name: 'drug_pay',
        component: require('./components/drug_pay.vue')
    },
    '/count_manage/:id': {//经营统计
        name: 'count_manage',
        component: require('./components/count_manage.vue')
    },
    '/count_family': {//患者统计123
        component: require('./components/count_family.vue')
    },
    '/count_doc/:id': {//医师统计
        name: 'count_doc',
        component: require('./components/count_doc.vue')
    },
    '/count_mall': {//商城统计
        component: require('./components/count_mall.vue')
    },
    '/count_income/:id': {//商城统计
        name: 'count_income',
        component: require('./components/count_income.vue')
    },
    '/count_lnquiry': {// 方案统计
        component: require('./components/count_lnquiry.vue')
    },
    '/comment_admin/:id': {//评价管理
        name: 'comment_admin',
        component: require('./components/comment_admin.vue')
    },
    '/count_curative/:id': {//疗效管理
        name: 'count_curative',
        component: require('./components/count_curative.vue')
    },
    // '/count_curative_detail/:id/:page': {//疗效管理详情
    //     name:'count_curative_detail',
    //     component: require('./components/count_curative_detail.vue')
    // }
    '/count_curative_detail/:id': {//疗效管理详情
        name: 'count_curative_detail',
        component: require('./components/count_curative_detail.vue')
    },
    '/adm_sync': {//数据同步
        component: require('./components/adm_sync.vue')
    },
    '/section_admin/:id': {//科室管理
        name: 'section_admin',
        component: require('./components/section_admin.vue')
    },
    '/disease_admin/:id': {//疾病管理
        name: 'disease_admin',
        component: require('./components/disease_admin.vue')
    },
    '/slider': {//轮播
        component: require('./components/slider.vue')
    },
    '/telephone': {
        component: require('./components/adm_telephone.vue')
    },
    '/message_list': {
        component: require('./components/message_list.vue')
    },
    '/message_detail': {
        component: require('./components/message_detail.vue')
    }
});

router.beforeEach(function (transition) {
    layer.load();
    transition.next();
});

router.afterEach(function (transition) {
    setTimeout(function () {
        layer.closeAll('loading');
    }, 1000);
});

router.start(App, 'body');