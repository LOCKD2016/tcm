<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'pid' => 0,
                'name' => 'api.appuser.index',
                'display_name' => '用户信息',
                'description' => '用户信息',
                'created_at' => '2017-12-27 17:38:15',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'pid' => 1,
                'name' => 'api.appuser.edit',
                'display_name' => '修改用户信息',
                'description' => '修改用户信息',
                'created_at' => '2017-12-27 17:38:15',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'pid' => 1,
                'name' => 'api.appuser.detail',
                'display_name' => '获取APP用户详情',
                'description' => NULL,
                'created_at' => '2017-12-27 17:38:15',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'pid' => 1,
                'name' => 'api.appuser.clinic',
                'display_name' => '获取APP用户诊疗记录',
                'description' => NULL,
                'created_at' => '2017-12-27 17:38:15',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'pid' => 0,
                'name' => 'api.doctor.index',
                'display_name' => '医师列表',
                'description' => NULL,
                'created_at' => '2017-12-27 17:38:15',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'pid' => 5,
                'name' => 'api.doctor.show',
                'display_name' => '医师详情',
                'description' => NULL,
                'created_at' => '2017-12-27 17:38:15',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'pid' => 5,
                'name' => 'api.doctor.update',
                'display_name' => '医师修改',
                'description' => NULL,
                'created_at' => '2017-12-27 17:38:15',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'pid' => 5,
                'name' => 'api.doctor.adDisease',
                'display_name' => '医师擅长疾病/科室修改',
                'description' => NULL,
                'created_at' => '2017-12-27 17:38:15',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'pid' => 5,
                'name' => 'api.doctor.delDisease',
                'display_name' => '医生擅长删除',
                'description' => NULL,
                'created_at' => '2017-12-27 17:38:15',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'pid' => 5,
                'name' => 'api.doctor.doctorLeave',
                'display_name' => '医生休息状态审核',
                'description' => NULL,
                'created_at' => '2017-06-01 18:07:37',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'pid' => 0,
                'name' => 'api.bespeaks.index',
                'display_name' => '预约列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'pid' => 11,
                'name' => 'api.bespeaks.show',
                'display_name' => '预约详情',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'pid' => 11,
                'name' => 'api.bespeaks.update',
                'display_name' => '修改预约',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'pid' => 0,
                'name' => 'api.order.orderList',
                'display_name' => '预约/药方订单列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'pid' => 0,
                'name' => 'api.order.preSendList',
                'display_name' => '药方发货列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'pid' => 15,
                'name' => 'api.order.update',
                'display_name' => '药方发货添加物流',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'pid' => 11,
                'name' => 'api.order.refund',
                'display_name' => '申请退款',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'pid' => 0,
                'name' => 'api.count.userCount',
                'display_name' => '患者统计',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'pid' => 0,
                'name' => 'api.count.doctorCount',
                'display_name' => '医师统计',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'pid' => 0,
                'name' => 'api.count.doctorComment',
                'display_name' => '疗效统计',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'pid' => 0,
                'name' => 'api.count.doctorIncome',
                'display_name' => '医生收入统计',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'pid' => 0,
                'name' => 'api.count.deal',
                'display_name' => '经营统计总体收入',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'pid' => 0,
                'name' => 'api.count.manage',
                'display_name' => '经营统计',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'pid' => 0,
                'name' => 'api.slider.index',
                'display_name' => '轮播图列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'pid' => 24,
                'name' => 'api.slider.sliderAdd',
                'display_name' => '轮播图添加',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'pid' => 24,
                'name' => 'api.slider.sliderUpdate',
                'display_name' => '轮播图修改',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'pid' => 24,
                'name' => 'api.slider.sliderDelete',
                'display_name' => '轮播图删除',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'pid' => 0,
                'name' => 'api.clinique.index',
                'display_name' => '门店列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'pid' => 28,
                'name' => 'api.clinique.update',
                'display_name' => '门店修改',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'pid' => 0,
                'name' => 'api.section.index',
                'display_name' => '获取科室列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'pid' => 30,
                'name' => 'api.section.sectionAdd',
                'display_name' => '添加科室',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'pid' => 30,
                'name' => 'api.section.sectionUpdate',
                'display_name' => '修改科室',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
                'pid' => 30,
                'name' => 'api.section.sectionDel',
                'display_name' => '删除科室',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
                'pid' => 30,
                'name' => 'api.disease.index',
                'display_name' => '疾病列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'pid' => 30,
                'name' => 'api.disease.diseaseCreate',
                'display_name' => '添加疾病',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
                'pid' => 30,
                'name' => 'api.disease.disease',
                'display_name' => '获取科室对应的疾病',
                'description' => NULL,
                'created_at' => '2017-06-01 18:34:58',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            36 => 
            array (
                'id' => 37,
                'pid' => 30,
                'name' => 'api.disease.diseaseDel',
                'display_name' => '删除科室对应疾病',
                'description' => NULL,
                'created_at' => '2017-06-01 18:35:01',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            37 => 
            array (
                'id' => 38,
                'pid' => 0,
                'name' => 'api.prescription.priceList',
                'display_name' => '医生划价列表',
                'description' => NULL,
                'created_at' => '2017-06-01 18:35:03',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            38 => 
            array (
                'id' => 39,
                'pid' => 38,
                'name' => 'api.prescription.setPrice',
                'display_name' => '医生划价',
                'description' => NULL,
                'created_at' => '2017-06-01 18:35:06',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            39 => 
            array (
                'id' => 40,
                'pid' => 0,
                'name' => 'api.clinic.index',
                'display_name' => '诊疗列表',
                'description' => NULL,
                'created_at' => '2017-06-01 18:35:08',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            40 => 
            array (
                'id' => 41,
                'pid' => 40,
                'name' => 'api.clinic.show',
                'display_name' => '诊疗详情',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            41 => 
            array (
                'id' => 42,
                'pid' => 40,
                'name' => 'api.clinic.update',
                'display_name' => '操作诊疗',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            42 => 
            array (
                'id' => 43,
                'pid' => 0,
                'name' => 'api.comment.index',
                'display_name' => '评论列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            43 => 
            array (
                'id' => 44,
                'pid' => 43,
                'name' => 'api.comment.save',
                'display_name' => '评论审核',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            44 => 
            array (
                'id' => 45,
                'pid' => 0,
                'name' => 'api.configs.agreementIndex',
                'display_name' => '用户协议列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            45 => 
            array (
                'id' => 46,
                'pid' => 45,
                'name' => 'api.configs.agreementEdit',
                'display_name' => '用户协议修改',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            46 => 
            array (
                'id' => 47,
                'pid' => 0,
                'name' => 'api.system.exam',
                'display_name' => '系统问诊单',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            47 => 
            array (
                'id' => 48,
                'pid' => 47,
                'name' => 'api.system.exam_show',
                'display_name' => '系统问诊单详情',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            48 => 
            array (
                'id' => 49,
                'pid' => 47,
                'name' => 'api.system.exam_store',
                'display_name' => '系统问诊单添加',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            49 => 
            array (
                'id' => 50,
                'pid' => 47,
                'name' => 'api.system.exam_delete',
                'display_name' => '系统问诊单删除',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            50 => 
            array (
                'id' => 51,
                'pid' => 47,
                'name' => 'api.system.exam_save',
                'display_name' => '系统问诊单修改',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            51 => 
            array (
                'id' => 52,
                'pid' => 0,
                'name' => 'api.upload.create',
                'display_name' => '文件上传',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            52 => 
            array (
                'id' => 53,
                'pid' => 0,
                'name' => 'api.user.index',
                'display_name' => '获取系统用户列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            53 => 
            array (
                'id' => 54,
                'pid' => 53,
                'name' => 'api.user.group',
                'display_name' => '获取用户组列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            54 => 
            array (
                'id' => 55,
                'pid' => 53,
                'name' => 'api.user.store',
                'display_name' => '添加系统用户',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            55 => 
            array (
                'id' => 56,
                'pid' => 53,
                'name' => 'api.user.updatePwd',
                'display_name' => '修改自己的密码',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            56 => 
            array (
                'id' => 57,
                'pid' => 53,
                'name' => 'api.user.delete',
                'display_name' => '删除用户',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            57 => 
            array (
                'id' => 58,
                'pid' => 53,
                'name' => 'api.user.show',
                'display_name' => '获取指定用户',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            58 => 
            array (
                'id' => 59,
                'pid' => 53,
                'name' => 'api.user.update',
                'display_name' => '修改用户信息',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            59 => 
            array (
                'id' => 60,
                'pid' => 53,
                'name' => 'api.user.resetPwd',
                'display_name' => '重置密码',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            60 => 
            array (
                'id' => 61,
                'pid' => 53,
                'name' => 'api.user.forbidden',
                'display_name' => '冻结用户',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            61 => 
            array (
                'id' => 62,
                'pid' => 53,
                'name' => 'api.user.role',
                'display_name' => '获取组ID',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            62 => 
            array (
                'id' => 63,
                'pid' => 53,
                'name' => 'api.user.saverole',
                'display_name' => '修改组ID',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            63 =>
            array (
                'id' => 64,
                'pid' => 0,
                'name' => 'api.auth.index',
                'display_name' => '分页查询权限列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            64 =>
            array (
                'id' => 65,
                'pid' => 64,
                'name' => 'api.auth.destroy',
                'display_name' => '删除指定权限',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),65 =>
            array (
                'id' => 66,
                'pid' => 64,
                'name' => 'api.auth.store',
                'display_name' => '添加权限',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),66 =>
            array (
                'id' => 67,
                'pid' => 64,
                'name' => 'api.auth.show',
                'display_name' => '查询所有一级二级的权限',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),67 =>
            array (
                'id' => 68,
                'pid' => 64,
                'name' => 'api.auth.update',
                'display_name' => '修改权限',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),68 =>
            array (
                'id' => 69,
                'pid' => 0,
                'name' => 'api.role.index',
                'display_name' => '分页展示用户组',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),69 =>
            array (
                'id' => 70,
                'pid' => 69,
                'name' => 'api.role.show',
                'display_name' => '获取指定用户组',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),70 =>
            array (
                'id' => 71,
                'pid' => 69,
                'name' => 'api.role.destroy',
                'display_name' => '删除用户组',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),71 =>
            array (
                'id' => 72,
                'pid' => 69,
                'name' => 'api.role.store',
                'display_name' => '添加用户组',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),72 =>
            array (
                'id' => 73,
                'pid' => 69,
                'name' => 'api.role.update',
                'display_name' => '修改用户组',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),73 =>
            array (
                'id' => 74,
                'pid' => 0,
                'name' => 'api.logs.index',
                'display_name' => '获取所有人的登录日志',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),74 =>
            array (
                'id' => 75,
                'pid' => 74,
                'name' => 'api.logs.getLogs',
                'display_name' => '获取自己的登录日志',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),75 =>
            array (
                'id' => 76,
                'pid' => 74,
                'name' => 'api.logs.show',
                'display_name' => '查看日志详情',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),76 =>
            array (
                'id' => 77,
                'pid' => 0,
                'name' => 'api.medicine.index',
                'display_name' => '药品列表',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),77 =>
            array (
                'id' => 78,
                'pid' => 77,
                'name' => 'api.medicine.save',
                'display_name' => '药品修改',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),78 =>
            array (
                'id' => 79,
                'pid' => 77,
                'name' => 'api.medicine.del',
                'display_name' => '药品删除',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));
    }
}