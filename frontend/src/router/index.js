import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router);
//app整体由店面页和店内页组成 暂时并没有用到嵌套路由
const routes = [{
    path: '/',
    name: "首页",
    redirect: '/index'
}, {
    path: '/index',
    name: "泰和国医",
    component: resolve => require(["../components/template/index.vue"], resolve)
}, {
    path: '/sign',
    name: "登录注册",
    component: resolve => require(["../components/template/sign.vue"], resolve)
}, {
    path: '/doctor/type/:type',
    name: "doctorlist",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/doctor.vue"], resolve)
    },
    meta: {
        keepAlive: true
    }
}, {
    path: '/doctor_aLine',
    name: "doctoraLine",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/doctor_aLine.vue"], resolve)
    }
}, {
    path: '/doctor_illness',
    name: "全部病情",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/doctor_illness.vue"], resolve)
    }
}, {
    path: '/doctor/outline',
    name: "门诊预约",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/doctor_outline.vue"], resolve)
    }
}, {
    path: '/confirmOrder',
    name: "确认订单",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/confirmOrder.vue"], resolve)
    }
}, {
    path: '/doctor/preOnline',
    name: "在线咨询",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/doctor_preOnline.vue"], resolve)
    }
}, {
    path: '/doctor/online',
    name: "在线咨询",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/doctor_online.vue"], resolve)
    }
}, {
    path: '/doctor_detail',
    name: "医生详情",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/doctor_detail.vue"], resolve)
    },
}, {
    path: '/doctor/allComment',
    name: "患者评价",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/docutor_allComment.vue"], resolve)
    }
}, {
    path: '/goods/:id',
    name: "健康商城",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/goods.vue"], resolve)
    }
}, {
    path: '/search',
    name: "搜索历史",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/search.vue"], resolve)
    }
}, {
    path: '/search_result',
    name: "搜索结果",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/search_result.vue"], resolve)
    }
}, {
    path: '/order',
    name: "我的预约",
    component: resolve => require(["../components/template/order.vue"], resolve)
}, {
    path: '/order_details',
    name: "我的预约详情",
    components: {
        "default": resolve => require(["../components/template/order.vue"], resolve),
        "subPage": resolve => require(["../components/template/order_details.vue"], resolve)
    }
}, {
    path: '/myorder_details',
    name: "我的订单详情",
    components: {
        "default": resolve => require(["../components/template/order.vue"], resolve),
        "subPage": resolve => require(["../components/template/myorder_details.vue"], resolve)
    }
}, {
    path: '/my',
    name: "个人中心",
    component: resolve => require(["../components/template/my.vue"], resolve)
}, {
    path: '/my_order/my',
    name: "我的订单",
    components: {
        "default": resolve => require(["../components/template/my.vue"], resolve),
        "subPage": resolve => require(["../components/template/my_order.vue"], resolve)
    }
}, {
    path: '/my_order_det/my/id',
    name: "订单详情2",
    components: {
        "default": resolve => require(["../components/template/my.vue"], resolve),
        "subPage": resolve => require(["../components/template/my_order_det.vue"], resolve)
    }
}, {
    path: '/prescription/my/id',
    name: "我的药方",
    components: {
        "default": resolve => require(["../components/template/my.vue"], resolve),
        "subPage": resolve => require(["../components/template/prescription.vue"], resolve)
    }
}, {
    path: '/payment/recipe',
    name: "药方确认订单",
    components: {
        "subPage": resolve => require(["../components/template/prescription_orderdetail.vue"], resolve)
    }
}, {
    path: '/my_fmld/my',
    name: "个人资料",
    components: {
        "default": resolve => require(["../components/template/my.vue"], resolve),
        "subPage": resolve => require(["../components/template/my_family_det.vue"], resolve)
    }
}, {
    path: '/my_address/my/',
    name: "收货地址列表",
    components: {
        "default": resolve => require(["../components/template/my.vue"], resolve),
        "subPage": resolve => require(["../components/template/my_address.vue"], resolve)
    }
}, {
    path: '/my_address/my/:type/id',
    name: "收货地址详情",
    components: {
        "default": resolve => require(["../components/template/my.vue"], resolve),
        "subPage": resolve => require(["../components/template/my_address_det.vue"], resolve)
    }
}, {
    path: '/chat',
    name: "聊天",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/chat.vue"], resolve)
    }
}, {
    path: '/questionnaire/:id',
    name: "填写问诊单",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/wenzhendan.vue"], resolve)
    }
}, {
    path: '/wzd_fill',
    name: "填空题",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/wzd_fill.vue"], resolve)
    }
}, {
    path: '/wzd_radio',
    name: "单选题",
    components: {

        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/wzd_radio.vue"], resolve)
    }
}, {
    path: '/wzd_check',
    name: "多选题",
    components: {

        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/wzd_check.vue"], resolve)
    }
}, {
    path: '/point',
    name: "穴位列表",
    components: {
        "default": resolve => require(["../components/template/my_order.vue"], resolve),
        "subPage": resolve => require(["../components/template/point.vue"], resolve)
    }
}, {
    path: '/tfbd',
    name: "贴敷必读",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/tfbd.vue"], resolve)
    }
}, {
    path: '/tfbd_new',
    name: "个性化贴敷必读",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/tfbd_new.vue"], resolve)
    }
}, {
    path: '/point_img',
    name: "穴位图片",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/point_img.vue"], resolve)
    }
}, {
    path: '/payment',
    name: "选择支付方式",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/paymethod.vue"], resolve)
    }
}, {
    path: '/map',
    name: "地图",
    components: {
        "subPage": resolve => require(["../components/template/map.vue"], resolve)
    }
}, {
    path: '/tickling',
    name: "疗效反馈",
    components: {
        "subPage": resolve => require(["../components/template/tickling.vue"], resolve)
    }
}, {
    path: '/exam',
    name: "个性化问诊单",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/exam.vue"], resolve)
    }
}, {
    path: '/standard',
    name: "查看标准问诊单",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/standard.vue"], resolve)
    }
}, {
    path: '/logistics',
    name: "物流信息",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/logistics.vue"], resolve)
    }
}, {
    path: '/pay_sucess',
    name: "支付完成",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/pay_sucess.vue"], resolve)
    }
}, {
    path: '/data/:titType/:timeType',
    name: "健康数据",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/data.vue"], resolve)
    }
}, {
    path: '/data_lr',
    name: "健康数据录入",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/data_lr.vue"], resolve)
    }
}, {
    path: '/data_cl',
    name: "测量结果",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/data_cl.vue"], resolve)
    }
}, {
    path: '/data_qh/:titType/:timeType',
    name: "图标切换",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/data_qh.vue"], resolve)
    }
}, {
    path: '/my_doctor',
    name: "我的医生",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/my_doctor.vue"], resolve)
    }
}, {
    path: '/my_clinic_list/my',
    name: "待评价",
    components: {
        "default": resolve => require(["../components/template/index.vue"], resolve),
        "subPage": resolve => require(["../components/template/clinic_list.vue"], resolve)
    }
},
    {
        path: '/my_contact/my',
        name: "联系人",
        components: {
            "default": resolve => require(["../components/template/index.vue"], resolve),
            "subPage": resolve => require(["../components/template/contact.vue"], resolve)
        }
    },
    {
        path: '/pdfone',
        name: "pdfone",
        component: resolve => require(["../components/template/showpdf.vue"], resolve)
    },
    {
        path: '/express/:id',
        name: "express",
        component: resolve => require(["../components/template/express.vue"], resolve)
    }
];
var ua = window.navigator.userAgent.toLowerCase();

var router = new Router({
    mode: ua.match(/TCMUser/i) == 'tcmuser' ? 'hash' : 'history',
    base: "/wechat",
    linkActiveClass: 'active',
    routes,
    // scrollBehavior(to, from, savedPosition) {
    //     if (savedPosition) {
    //         return savedPosition
    //     } else {
    //         return { x: 0, y: 0 }
    //     }
    // }
});

//前进刷新,后退缓存

// router.beforeEach((to,from,next)=>{

// const toDepth = to.fullPath.length

// const fromDepth = from.fullPath.length

// console.log(to,from)

// if(to.path.indexOf('doctor/type') > -1 || from.path.indexOf('doctor/type') > -1){

//     if (toDepth < fromDepth) {

//       from.meta.keepAlive = false

//       to.meta.keepAlive = true

//     }

// }

// next()

// if (to.path.indexOf('index') > -1 && from.path.indexOf('doctor/type') > -1) {

//     from.meta.keepAlive = false;

// }else if(to.path.indexOf('doctor/type') > -1 && from.path.indexOf('index') > -1){

//     to.meta.keepAlive = true;

// }

// console.log(to.meta.keepAlive)
// console.log(from.meta.keepAlive)

// next()

// })

export default router;
