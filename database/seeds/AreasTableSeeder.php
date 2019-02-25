<?php

use Illuminate\Database\Seeder;

class AreasTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('areas')->delete();
        
        \DB::table('areas')->insert(array (
            0 => 
            array (
                'id' => 1,
                'area_id' => 110000,
                'name' => '北京市',
            ),
            1 => 
            array (
                'id' => 2,
                'area_id' => 120000,
                'name' => '天津市',
            ),
            2 => 
            array (
                'id' => 3,
                'area_id' => 130000,
                'name' => '河北省',
            ),
            3 => 
            array (
                'id' => 4,
                'area_id' => 140000,
                'name' => '山西省',
            ),
            4 => 
            array (
                'id' => 5,
                'area_id' => 150000,
                'name' => '内蒙古',
            ),
            5 => 
            array (
                'id' => 6,
                'area_id' => 210000,
                'name' => '辽宁省',
            ),
            6 => 
            array (
                'id' => 7,
                'area_id' => 220000,
                'name' => '吉林省',
            ),
            7 => 
            array (
                'id' => 8,
                'area_id' => 230000,
                'name' => '黑龙江',
            ),
            8 => 
            array (
                'id' => 9,
                'area_id' => 310000,
                'name' => '上海市',
            ),
            9 => 
            array (
                'id' => 10,
                'area_id' => 320000,
                'name' => '江苏省',
            ),
            10 => 
            array (
                'id' => 11,
                'area_id' => 330000,
                'name' => '浙江省',
            ),
            11 => 
            array (
                'id' => 12,
                'area_id' => 340000,
                'name' => '安徽省',
            ),
            12 => 
            array (
                'id' => 13,
                'area_id' => 350000,
                'name' => '福建省',
            ),
            13 => 
            array (
                'id' => 14,
                'area_id' => 360000,
                'name' => '江西省',
            ),
            14 => 
            array (
                'id' => 15,
                'area_id' => 370000,
                'name' => '山东省',
            ),
            15 => 
            array (
                'id' => 16,
                'area_id' => 410000,
                'name' => '河南省',
            ),
            16 => 
            array (
                'id' => 17,
                'area_id' => 420000,
                'name' => '湖北省',
            ),
            17 => 
            array (
                'id' => 18,
                'area_id' => 430000,
                'name' => '湖南省',
            ),
            18 => 
            array (
                'id' => 19,
                'area_id' => 440000,
                'name' => '广东省',
            ),
            19 => 
            array (
                'id' => 20,
                'area_id' => 450000,
                'name' => '广西省',
            ),
            20 => 
            array (
                'id' => 21,
                'area_id' => 460000,
                'name' => '海南省',
            ),
            21 => 
            array (
                'id' => 22,
                'area_id' => 500000,
                'name' => '重庆市',
            ),
            22 => 
            array (
                'id' => 23,
                'area_id' => 510000,
                'name' => '四川省',
            ),
            23 => 
            array (
                'id' => 24,
                'area_id' => 520000,
                'name' => '贵州省',
            ),
            24 => 
            array (
                'id' => 25,
                'area_id' => 530000,
                'name' => '云南省',
            ),
            25 => 
            array (
                'id' => 26,
                'area_id' => 540000,
                'name' => '西　藏',
            ),
            26 => 
            array (
                'id' => 27,
                'area_id' => 610000,
                'name' => '陕西省',
            ),
            27 => 
            array (
                'id' => 28,
                'area_id' => 620000,
                'name' => '甘肃省',
            ),
            28 => 
            array (
                'id' => 29,
                'area_id' => 630000,
                'name' => '青海省',
            ),
            29 => 
            array (
                'id' => 30,
                'area_id' => 640000,
                'name' => '宁　夏',
            ),
            30 => 
            array (
                'id' => 31,
                'area_id' => 650000,
                'name' => '新　疆',
            ),
            31 => 
            array (
                'id' => 32,
                'area_id' => 710000,
                'name' => '台湾省',
            ),
            32 => 
            array (
                'id' => 33,
                'area_id' => 810000,
                'name' => '香　港',
            ),
            33 => 
            array (
                'id' => 34,
                'area_id' => 820000,
                'name' => '澳　门',
            ),
            34 => 
            array (
                'id' => 35,
                'area_id' => 110100,
                'name' => '北京市',
            ),
            35 => 
            array (
                'id' => 37,
                'area_id' => 110200,
                'name' => '县',
            ),
            36 => 
            array (
                'id' => 38,
                'area_id' => 120100,
                'name' => '市辖区',
            ),
            37 => 
            array (
                'id' => 41,
                'area_id' => 120200,
                'name' => '县',
            ),
            38 => 
            array (
                'id' => 42,
                'area_id' => 130100,
                'name' => '石家庄市',
            ),
            39 => 
            array (
                'id' => 45,
                'area_id' => 130200,
                'name' => '唐山市',
            ),
            40 => 
            array (
                'id' => 48,
                'area_id' => 130300,
                'name' => '秦皇岛市',
            ),
            41 => 
            array (
                'id' => 52,
                'area_id' => 130400,
                'name' => '邯郸市',
            ),
            42 => 
            array (
                'id' => 57,
                'area_id' => 130500,
                'name' => '邢台市',
            ),
            43 => 
            array (
                'id' => 63,
                'area_id' => 130600,
                'name' => '保定市',
            ),
            44 => 
            array (
                'id' => 70,
                'area_id' => 130700,
                'name' => '张家口市',
            ),
            45 => 
            array (
                'id' => 78,
                'area_id' => 130800,
                'name' => '承德市',
            ),
            46 => 
            array (
                'id' => 87,
                'area_id' => 130900,
                'name' => '沧州市',
            ),
            47 => 
            array (
                'id' => 97,
                'area_id' => 131000,
                'name' => '廊坊市',
            ),
            48 => 
            array (
                'id' => 108,
                'area_id' => 131100,
                'name' => '衡水市',
            ),
            49 => 
            array (
                'id' => 109,
                'area_id' => 140100,
                'name' => '太原市',
            ),
            50 => 
            array (
                'id' => 121,
                'area_id' => 140200,
                'name' => '大同市',
            ),
            51 => 
            array (
                'id' => 133,
                'area_id' => 140300,
                'name' => '阳泉市',
            ),
            52 => 
            array (
                'id' => 145,
                'area_id' => 140400,
                'name' => '长治市',
            ),
            53 => 
            array (
                'id' => 157,
                'area_id' => 140500,
                'name' => '晋城市',
            ),
            54 => 
            array (
                'id' => 169,
                'area_id' => 140600,
                'name' => '朔州市',
            ),
            55 => 
            array (
                'id' => 181,
                'area_id' => 140700,
                'name' => '晋中市',
            ),
            56 => 
            array (
                'id' => 193,
                'area_id' => 140800,
                'name' => '运城市',
            ),
            57 => 
            array (
                'id' => 205,
                'area_id' => 140900,
                'name' => '忻州市',
            ),
            58 => 
            array (
                'id' => 217,
                'area_id' => 141000,
                'name' => '临汾市',
            ),
            59 => 
            array (
                'id' => 229,
                'area_id' => 141100,
                'name' => '吕梁市',
            ),
            60 => 
            array (
                'id' => 230,
                'area_id' => 150100,
                'name' => '呼和浩特市',
            ),
            61 => 
            array (
                'id' => 242,
                'area_id' => 150200,
                'name' => '包头市',
            ),
            62 => 
            array (
                'id' => 254,
                'area_id' => 150300,
                'name' => '乌海市',
            ),
            63 => 
            array (
                'id' => 266,
                'area_id' => 150400,
                'name' => '赤峰市',
            ),
            64 => 
            array (
                'id' => 278,
                'area_id' => 150500,
                'name' => '通辽市',
            ),
            65 => 
            array (
                'id' => 290,
                'area_id' => 150600,
                'name' => '鄂尔多斯市',
            ),
            66 => 
            array (
                'id' => 302,
                'area_id' => 150700,
                'name' => '呼伦贝尔市',
            ),
            67 => 
            array (
                'id' => 314,
                'area_id' => 150800,
                'name' => '巴彦淖尔市',
            ),
            68 => 
            array (
                'id' => 326,
                'area_id' => 150900,
                'name' => '乌兰察布市',
            ),
            69 => 
            array (
                'id' => 338,
                'area_id' => 152200,
                'name' => '兴安盟',
            ),
            70 => 
            array (
                'id' => 350,
                'area_id' => 152500,
                'name' => '锡林郭勒盟',
            ),
            71 => 
            array (
                'id' => 362,
                'area_id' => 152900,
                'name' => '阿拉善盟',
            ),
            72 => 
            array (
                'id' => 363,
                'area_id' => 210100,
                'name' => '沈阳市',
            ),
            73 => 
            array (
                'id' => 376,
                'area_id' => 210200,
                'name' => '大连市',
            ),
            74 => 
            array (
                'id' => 389,
                'area_id' => 210300,
                'name' => '鞍山市',
            ),
            75 => 
            array (
                'id' => 402,
                'area_id' => 210400,
                'name' => '抚顺市',
            ),
            76 => 
            array (
                'id' => 415,
                'area_id' => 210500,
                'name' => '本溪市',
            ),
            77 => 
            array (
                'id' => 428,
                'area_id' => 210600,
                'name' => '丹东市',
            ),
            78 => 
            array (
                'id' => 441,
                'area_id' => 210700,
                'name' => '锦州市',
            ),
            79 => 
            array (
                'id' => 454,
                'area_id' => 210800,
                'name' => '营口市',
            ),
            80 => 
            array (
                'id' => 467,
                'area_id' => 210900,
                'name' => '阜新市',
            ),
            81 => 
            array (
                'id' => 480,
                'area_id' => 211000,
                'name' => '辽阳市',
            ),
            82 => 
            array (
                'id' => 493,
                'area_id' => 211100,
                'name' => '盘锦市',
            ),
            83 => 
            array (
                'id' => 506,
                'area_id' => 211200,
                'name' => '铁岭市',
            ),
            84 => 
            array (
                'id' => 519,
                'area_id' => 211300,
                'name' => '朝阳市',
            ),
            85 => 
            array (
                'id' => 533,
                'area_id' => 211400,
                'name' => '葫芦岛市',
            ),
            86 => 
            array (
                'id' => 534,
                'area_id' => 220100,
                'name' => '长春市',
            ),
            87 => 
            array (
                'id' => 549,
                'area_id' => 220200,
                'name' => '吉林市',
            ),
            88 => 
            array (
                'id' => 564,
                'area_id' => 220300,
                'name' => '四平市',
            ),
            89 => 
            array (
                'id' => 579,
                'area_id' => 220400,
                'name' => '辽源市',
            ),
            90 => 
            array (
                'id' => 594,
                'area_id' => 220500,
                'name' => '通化市',
            ),
            91 => 
            array (
                'id' => 609,
                'area_id' => 220600,
                'name' => '白山市',
            ),
            92 => 
            array (
                'id' => 624,
                'area_id' => 220700,
                'name' => '松原市',
            ),
            93 => 
            array (
                'id' => 639,
                'area_id' => 220800,
                'name' => '白城市',
            ),
            94 => 
            array (
                'id' => 654,
                'area_id' => 222400,
                'name' => '延边朝鲜族自治州',
            ),
            95 => 
            array (
                'id' => 660,
                'area_id' => 230100,
                'name' => '哈尔滨市',
            ),
            96 => 
            array (
                'id' => 675,
                'area_id' => 230200,
                'name' => '齐齐哈尔市',
            ),
            97 => 
            array (
                'id' => 690,
                'area_id' => 230300,
                'name' => '鸡西市',
            ),
            98 => 
            array (
                'id' => 705,
                'area_id' => 230400,
                'name' => '鹤岗市',
            ),
            99 => 
            array (
                'id' => 720,
                'area_id' => 230500,
                'name' => '双鸭山市',
            ),
            100 => 
            array (
                'id' => 735,
                'area_id' => 230600,
                'name' => '大庆市',
            ),
            101 => 
            array (
                'id' => 750,
                'area_id' => 230700,
                'name' => '伊春市',
            ),
            102 => 
            array (
                'id' => 765,
                'area_id' => 230800,
                'name' => '佳木斯市',
            ),
            103 => 
            array (
                'id' => 780,
                'area_id' => 230900,
                'name' => '七台河市',
            ),
            104 => 
            array (
                'id' => 795,
                'area_id' => 231000,
                'name' => '牡丹江市',
            ),
            105 => 
            array (
                'id' => 810,
                'area_id' => 231100,
                'name' => '黑河市',
            ),
            106 => 
            array (
                'id' => 825,
                'area_id' => 231200,
                'name' => '绥化市',
            ),
            107 => 
            array (
                'id' => 840,
                'area_id' => 232700,
                'name' => '大兴安岭地区',
            ),
            108 => 
            array (
                'id' => 842,
                'area_id' => 310100,
                'name' => '市辖区',
            ),
            109 => 
            array (
                'id' => 857,
                'area_id' => 310200,
                'name' => '县',
            ),
            110 => 
            array (
                'id' => 870,
                'area_id' => 320100,
                'name' => '南京市',
            ),
            111 => 
            array (
                'id' => 885,
                'area_id' => 320200,
                'name' => '无锡市',
            ),
            112 => 
            array (
                'id' => 900,
                'area_id' => 320300,
                'name' => '徐州市',
            ),
            113 => 
            array (
                'id' => 915,
                'area_id' => 320400,
                'name' => '常州市',
            ),
            114 => 
            array (
                'id' => 930,
                'area_id' => 320500,
                'name' => '苏州市',
            ),
            115 => 
            array (
                'id' => 945,
                'area_id' => 320600,
                'name' => '南通市',
            ),
            116 => 
            array (
                'id' => 960,
                'area_id' => 320700,
                'name' => '连云港市',
            ),
            117 => 
            array (
                'id' => 975,
                'area_id' => 320800,
                'name' => '淮安市',
            ),
            118 => 
            array (
                'id' => 990,
                'area_id' => 320900,
                'name' => '盐城市',
            ),
            119 => 
            array (
                'id' => 1005,
                'area_id' => 321000,
                'name' => '扬州市',
            ),
            120 => 
            array (
                'id' => 1020,
                'area_id' => 321100,
                'name' => '镇江市',
            ),
            121 => 
            array (
                'id' => 1035,
                'area_id' => 321200,
                'name' => '泰州市',
            ),
            122 => 
            array (
                'id' => 1050,
                'area_id' => 321300,
                'name' => '宿迁市',
            ),
            123 => 
            array (
                'id' => 1052,
                'area_id' => 330100,
                'name' => '杭州市',
            ),
            124 => 
            array (
                'id' => 1067,
                'area_id' => 330200,
                'name' => '宁波市',
            ),
            125 => 
            array (
                'id' => 1082,
                'area_id' => 330300,
                'name' => '温州市',
            ),
            126 => 
            array (
                'id' => 1097,
                'area_id' => 330400,
                'name' => '嘉兴市',
            ),
            127 => 
            array (
                'id' => 1112,
                'area_id' => 330500,
                'name' => '湖州市',
            ),
            128 => 
            array (
                'id' => 1127,
                'area_id' => 330600,
                'name' => '绍兴市',
            ),
            129 => 
            array (
                'id' => 1142,
                'area_id' => 330700,
                'name' => '金华市',
            ),
            130 => 
            array (
                'id' => 1157,
                'area_id' => 330800,
                'name' => '衢州市',
            ),
            131 => 
            array (
                'id' => 1172,
                'area_id' => 330900,
                'name' => '舟山市',
            ),
            132 => 
            array (
                'id' => 1187,
                'area_id' => 331000,
                'name' => '台州市',
            ),
            133 => 
            array (
                'id' => 1202,
                'area_id' => 331100,
                'name' => '丽水市',
            ),
            134 => 
            array (
                'id' => 1206,
                'area_id' => 340100,
                'name' => '合肥市',
            ),
            135 => 
            array (
                'id' => 1221,
                'area_id' => 340200,
                'name' => '芜湖市',
            ),
            136 => 
            array (
                'id' => 1236,
                'area_id' => 340300,
                'name' => '蚌埠市',
            ),
            137 => 
            array (
                'id' => 1251,
                'area_id' => 340400,
                'name' => '淮南市',
            ),
            138 => 
            array (
                'id' => 1266,
                'area_id' => 340500,
                'name' => '马鞍山市',
            ),
            139 => 
            array (
                'id' => 1281,
                'area_id' => 340600,
                'name' => '淮北市',
            ),
            140 => 
            array (
                'id' => 1296,
                'area_id' => 340700,
                'name' => '铜陵市',
            ),
            141 => 
            array (
                'id' => 1311,
                'area_id' => 340800,
                'name' => '安庆市',
            ),
            142 => 
            array (
                'id' => 1326,
                'area_id' => 341000,
                'name' => '黄山市',
            ),
            143 => 
            array (
                'id' => 1341,
                'area_id' => 341100,
                'name' => '滁州市',
            ),
            144 => 
            array (
                'id' => 1356,
                'area_id' => 341200,
                'name' => '阜阳市',
            ),
            145 => 
            array (
                'id' => 1371,
                'area_id' => 341300,
                'name' => '宿州市',
            ),
            146 => 
            array (
                'id' => 1386,
                'area_id' => 341500,
                'name' => '六安市',
            ),
            147 => 
            array (
                'id' => 1401,
                'area_id' => 341600,
                'name' => '亳州市',
            ),
            148 => 
            array (
                'id' => 1416,
                'area_id' => 341700,
                'name' => '池州市',
            ),
            149 => 
            array (
                'id' => 1432,
                'area_id' => 341800,
                'name' => '宣城市',
            ),
            150 => 
            array (
                'id' => 1433,
                'area_id' => 350100,
                'name' => '福州市',
            ),
            151 => 
            array (
                'id' => 1450,
                'area_id' => 350200,
                'name' => '厦门市',
            ),
            152 => 
            array (
                'id' => 1467,
                'area_id' => 350300,
                'name' => '莆田市',
            ),
            153 => 
            array (
                'id' => 1484,
                'area_id' => 350400,
                'name' => '三明市',
            ),
            154 => 
            array (
                'id' => 1501,
                'area_id' => 350500,
                'name' => '泉州市',
            ),
            155 => 
            array (
                'id' => 1518,
                'area_id' => 350600,
                'name' => '漳州市',
            ),
            156 => 
            array (
                'id' => 1535,
                'area_id' => 350700,
                'name' => '南平市',
            ),
            157 => 
            array (
                'id' => 1552,
                'area_id' => 350800,
                'name' => '龙岩市',
            ),
            158 => 
            array (
                'id' => 1569,
                'area_id' => 350900,
                'name' => '宁德市',
            ),
            159 => 
            array (
                'id' => 1577,
                'area_id' => 360100,
                'name' => '南昌市',
            ),
            160 => 
            array (
                'id' => 1594,
                'area_id' => 360200,
                'name' => '景德镇市',
            ),
            161 => 
            array (
                'id' => 1611,
                'area_id' => 360300,
                'name' => '萍乡市',
            ),
            162 => 
            array (
                'id' => 1628,
                'area_id' => 360400,
                'name' => '九江市',
            ),
            163 => 
            array (
                'id' => 1645,
                'area_id' => 360500,
                'name' => '新余市',
            ),
            164 => 
            array (
                'id' => 1662,
                'area_id' => 360600,
                'name' => '鹰潭市',
            ),
            165 => 
            array (
                'id' => 1679,
                'area_id' => 360700,
                'name' => '赣州市',
            ),
            166 => 
            array (
                'id' => 1696,
                'area_id' => 360800,
                'name' => '吉安市',
            ),
            167 => 
            array (
                'id' => 1713,
                'area_id' => 360900,
                'name' => '宜春市',
            ),
            168 => 
            array (
                'id' => 1730,
                'area_id' => 361000,
                'name' => '抚州市',
            ),
            169 => 
            array (
                'id' => 1747,
                'area_id' => 361100,
                'name' => '上饶市',
            ),
            170 => 
            array (
                'id' => 1753,
                'area_id' => 370100,
                'name' => '济南市',
            ),
            171 => 
            array (
                'id' => 1770,
                'area_id' => 370200,
                'name' => '青岛市',
            ),
            172 => 
            array (
                'id' => 1787,
                'area_id' => 370300,
                'name' => '淄博市',
            ),
            173 => 
            array (
                'id' => 1804,
                'area_id' => 370400,
                'name' => '枣庄市',
            ),
            174 => 
            array (
                'id' => 1821,
                'area_id' => 370500,
                'name' => '东营市',
            ),
            175 => 
            array (
                'id' => 1838,
                'area_id' => 370600,
                'name' => '烟台市',
            ),
            176 => 
            array (
                'id' => 1855,
                'area_id' => 370700,
                'name' => '潍坊市',
            ),
            177 => 
            array (
                'id' => 1872,
                'area_id' => 370800,
                'name' => '济宁市',
            ),
            178 => 
            array (
                'id' => 1889,
                'area_id' => 370900,
                'name' => '泰安市',
            ),
            179 => 
            array (
                'id' => 1906,
                'area_id' => 371000,
                'name' => '威海市',
            ),
            180 => 
            array (
                'id' => 1923,
                'area_id' => 371100,
                'name' => '日照市',
            ),
            181 => 
            array (
                'id' => 1940,
                'area_id' => 371200,
                'name' => '莱芜市',
            ),
            182 => 
            array (
                'id' => 1957,
                'area_id' => 371300,
                'name' => '临沂市',
            ),
            183 => 
            array (
                'id' => 1974,
                'area_id' => 371400,
                'name' => '德州市',
            ),
            184 => 
            array (
                'id' => 1991,
                'area_id' => 371500,
                'name' => '聊城市',
            ),
            185 => 
            array (
                'id' => 2008,
                'area_id' => 371600,
                'name' => '滨州市',
            ),
            186 => 
            array (
                'id' => 2025,
                'area_id' => 371700,
                'name' => '菏泽市',
            ),
            187 => 
            array (
                'id' => 2026,
                'area_id' => 410100,
                'name' => '郑州市',
            ),
            188 => 
            array (
                'id' => 2044,
                'area_id' => 410200,
                'name' => '开封市',
            ),
            189 => 
            array (
                'id' => 2062,
                'area_id' => 410300,
                'name' => '洛阳市',
            ),
            190 => 
            array (
                'id' => 2080,
                'area_id' => 410400,
                'name' => '平顶山市',
            ),
            191 => 
            array (
                'id' => 2098,
                'area_id' => 410500,
                'name' => '安阳市',
            ),
            192 => 
            array (
                'id' => 2116,
                'area_id' => 410600,
                'name' => '鹤壁市',
            ),
            193 => 
            array (
                'id' => 2134,
                'area_id' => 410700,
                'name' => '新乡市',
            ),
            194 => 
            array (
                'id' => 2152,
                'area_id' => 410800,
                'name' => '焦作市',
            ),
            195 => 
            array (
                'id' => 2170,
                'area_id' => 410900,
                'name' => '濮阳市',
            ),
            196 => 
            array (
                'id' => 2188,
                'area_id' => 411000,
                'name' => '许昌市',
            ),
            197 => 
            array (
                'id' => 2206,
                'area_id' => 411100,
                'name' => '漯河市',
            ),
            198 => 
            array (
                'id' => 2224,
                'area_id' => 411200,
                'name' => '三门峡市',
            ),
            199 => 
            array (
                'id' => 2242,
                'area_id' => 411300,
                'name' => '南阳市',
            ),
            200 => 
            array (
                'id' => 2260,
                'area_id' => 411400,
                'name' => '商丘市',
            ),
            201 => 
            array (
                'id' => 2278,
                'area_id' => 411500,
                'name' => '信阳市',
            ),
            202 => 
            array (
                'id' => 2296,
                'area_id' => 411600,
                'name' => '周口市',
            ),
            203 => 
            array (
                'id' => 2314,
                'area_id' => 411700,
                'name' => '驻马店市',
            ),
            204 => 
            array (
                'id' => 2315,
                'area_id' => 420100,
                'name' => '武汉市',
            ),
            205 => 
            array (
                'id' => 2333,
                'area_id' => 420200,
                'name' => '黄石市',
            ),
            206 => 
            array (
                'id' => 2351,
                'area_id' => 420300,
                'name' => '十堰市',
            ),
            207 => 
            array (
                'id' => 2369,
                'area_id' => 420500,
                'name' => '宜昌市',
            ),
            208 => 
            array (
                'id' => 2387,
                'area_id' => 420600,
                'name' => '襄樊市',
            ),
            209 => 
            array (
                'id' => 2405,
                'area_id' => 420700,
                'name' => '鄂州市',
            ),
            210 => 
            array (
                'id' => 2423,
                'area_id' => 420800,
                'name' => '荆门市',
            ),
            211 => 
            array (
                'id' => 2441,
                'area_id' => 420900,
                'name' => '孝感市',
            ),
            212 => 
            array (
                'id' => 2459,
                'area_id' => 421000,
                'name' => '荆州市',
            ),
            213 => 
            array (
                'id' => 2477,
                'area_id' => 421100,
                'name' => '黄冈市',
            ),
            214 => 
            array (
                'id' => 2495,
                'area_id' => 421200,
                'name' => '咸宁市',
            ),
            215 => 
            array (
                'id' => 2513,
                'area_id' => 421300,
                'name' => '随州市',
            ),
            216 => 
            array (
                'id' => 2531,
                'area_id' => 422800,
                'name' => '恩施土家族苗族自治州',
            ),
            217 => 
            array (
                'id' => 2549,
                'area_id' => 429000,
                'name' => '省直辖行政单位',
            ),
            218 => 
            array (
                'id' => 2553,
                'area_id' => 430100,
                'name' => '长沙市',
            ),
            219 => 
            array (
                'id' => 2571,
                'area_id' => 430200,
                'name' => '株洲市',
            ),
            220 => 
            array (
                'id' => 2589,
                'area_id' => 430300,
                'name' => '湘潭市',
            ),
            221 => 
            array (
                'id' => 2607,
                'area_id' => 430400,
                'name' => '衡阳市',
            ),
            222 => 
            array (
                'id' => 2625,
                'area_id' => 430500,
                'name' => '邵阳市',
            ),
            223 => 
            array (
                'id' => 2643,
                'area_id' => 430600,
                'name' => '岳阳市',
            ),
            224 => 
            array (
                'id' => 2661,
                'area_id' => 430700,
                'name' => '常德市',
            ),
            225 => 
            array (
                'id' => 2679,
                'area_id' => 430800,
                'name' => '张家界市',
            ),
            226 => 
            array (
                'id' => 2697,
                'area_id' => 430900,
                'name' => '益阳市',
            ),
            227 => 
            array (
                'id' => 2715,
                'area_id' => 431000,
                'name' => '郴州市',
            ),
            228 => 
            array (
                'id' => 2733,
                'area_id' => 431100,
                'name' => '永州市',
            ),
            229 => 
            array (
                'id' => 2751,
                'area_id' => 431200,
                'name' => '怀化市',
            ),
            230 => 
            array (
                'id' => 2769,
                'area_id' => 431300,
                'name' => '娄底市',
            ),
            231 => 
            array (
                'id' => 2787,
                'area_id' => 433100,
                'name' => '湘西土家族苗族自治州',
            ),
            232 => 
            array (
                'id' => 2791,
                'area_id' => 440100,
                'name' => '广州市',
            ),
            233 => 
            array (
                'id' => 2809,
                'area_id' => 440200,
                'name' => '韶关市',
            ),
            234 => 
            array (
                'id' => 2827,
                'area_id' => 440300,
                'name' => '深圳市',
            ),
            235 => 
            array (
                'id' => 2845,
                'area_id' => 440400,
                'name' => '珠海市',
            ),
            236 => 
            array (
                'id' => 2863,
                'area_id' => 440500,
                'name' => '汕头市',
            ),
            237 => 
            array (
                'id' => 2881,
                'area_id' => 440600,
                'name' => '佛山市',
            ),
            238 => 
            array (
                'id' => 2899,
                'area_id' => 440700,
                'name' => '江门市',
            ),
            239 => 
            array (
                'id' => 2917,
                'area_id' => 440800,
                'name' => '湛江市',
            ),
            240 => 
            array (
                'id' => 2935,
                'area_id' => 440900,
                'name' => '茂名市',
            ),
            241 => 
            array (
                'id' => 2953,
                'area_id' => 441200,
                'name' => '肇庆市',
            ),
            242 => 
            array (
                'id' => 2971,
                'area_id' => 441300,
                'name' => '惠州市',
            ),
            243 => 
            array (
                'id' => 2989,
                'area_id' => 441400,
                'name' => '梅州市',
            ),
            244 => 
            array (
                'id' => 3007,
                'area_id' => 441500,
                'name' => '汕尾市',
            ),
            245 => 
            array (
                'id' => 3025,
                'area_id' => 441600,
                'name' => '河源市',
            ),
            246 => 
            array (
                'id' => 3043,
                'area_id' => 441700,
                'name' => '阳江市',
            ),
            247 => 
            array (
                'id' => 3061,
                'area_id' => 441800,
                'name' => '清远市',
            ),
            248 => 
            array (
                'id' => 3079,
                'area_id' => 441900,
                'name' => '东莞市',
            ),
            249 => 
            array (
                'id' => 3097,
                'area_id' => 442000,
                'name' => '中山市',
            ),
            250 => 
            array (
                'id' => 3116,
                'area_id' => 445100,
                'name' => '潮州市',
            ),
            251 => 
            array (
                'id' => 3136,
                'area_id' => 445200,
                'name' => '揭阳市',
            ),
            252 => 
            array (
                'id' => 3157,
                'area_id' => 445300,
                'name' => '云浮市',
            ),
            253 => 
            array (
                'id' => 3158,
                'area_id' => 450100,
                'name' => '南宁市',
            ),
            254 => 
            array (
                'id' => 3180,
                'area_id' => 450200,
                'name' => '柳州市',
            ),
            255 => 
            array (
                'id' => 3202,
                'area_id' => 450300,
                'name' => '桂林市',
            ),
            256 => 
            array (
                'id' => 3224,
                'area_id' => 450400,
                'name' => '梧州市',
            ),
            257 => 
            array (
                'id' => 3246,
                'area_id' => 450500,
                'name' => '北海市',
            ),
            258 => 
            array (
                'id' => 3268,
                'area_id' => 450600,
                'name' => '防城港市',
            ),
            259 => 
            array (
                'id' => 3290,
                'area_id' => 450700,
                'name' => '钦州市',
            ),
            260 => 
            array (
                'id' => 3312,
                'area_id' => 450800,
                'name' => '贵港市',
            ),
            261 => 
            array (
                'id' => 3334,
                'area_id' => 450900,
                'name' => '玉林市',
            ),
            262 => 
            array (
                'id' => 3356,
                'area_id' => 451000,
                'name' => '百色市',
            ),
            263 => 
            array (
                'id' => 3378,
                'area_id' => 451100,
                'name' => '贺州市',
            ),
            264 => 
            array (
                'id' => 3400,
                'area_id' => 451200,
                'name' => '河池市',
            ),
            265 => 
            array (
                'id' => 3422,
                'area_id' => 451300,
                'name' => '来宾市',
            ),
            266 => 
            array (
                'id' => 3444,
                'area_id' => 451400,
                'name' => '崇左市',
            ),
            267 => 
            array (
                'id' => 3452,
                'area_id' => 460100,
                'name' => '海口市',
            ),
            268 => 
            array (
                'id' => 3474,
                'area_id' => 460200,
                'name' => '三亚市',
            ),
            269 => 
            array (
                'id' => 3496,
                'area_id' => 469000,
                'name' => '省直辖县级行政单位',
            ),
            270 => 
            array (
                'id' => 3515,
                'area_id' => 500100,
                'name' => '市辖区',
            ),
            271 => 
            array (
                'id' => 3537,
                'area_id' => 500200,
                'name' => '县',
            ),
            272 => 
            array (
                'id' => 3559,
                'area_id' => 500300,
                'name' => '市',
            ),
            273 => 
            array (
                'id' => 3578,
                'area_id' => 510100,
                'name' => '成都市',
            ),
            274 => 
            array (
                'id' => 3600,
                'area_id' => 510300,
                'name' => '自贡市',
            ),
            275 => 
            array (
                'id' => 3622,
                'area_id' => 510400,
                'name' => '攀枝花市',
            ),
            276 => 
            array (
                'id' => 3644,
                'area_id' => 510500,
                'name' => '泸州市',
            ),
            277 => 
            array (
                'id' => 3666,
                'area_id' => 510600,
                'name' => '德阳市',
            ),
            278 => 
            array (
                'id' => 3688,
                'area_id' => 510700,
                'name' => '绵阳市',
            ),
            279 => 
            array (
                'id' => 3710,
                'area_id' => 510800,
                'name' => '广元市',
            ),
            280 => 
            array (
                'id' => 3732,
                'area_id' => 510900,
                'name' => '遂宁市',
            ),
            281 => 
            array (
                'id' => 3754,
                'area_id' => 511000,
                'name' => '内江市',
            ),
            282 => 
            array (
                'id' => 3776,
                'area_id' => 511100,
                'name' => '乐山市',
            ),
            283 => 
            array (
                'id' => 3798,
                'area_id' => 511300,
                'name' => '南充市',
            ),
            284 => 
            array (
                'id' => 3820,
                'area_id' => 511400,
                'name' => '眉山市',
            ),
            285 => 
            array (
                'id' => 3842,
                'area_id' => 511500,
                'name' => '宜宾市',
            ),
            286 => 
            array (
                'id' => 3864,
                'area_id' => 511600,
                'name' => '广安市',
            ),
            287 => 
            array (
                'id' => 3886,
                'area_id' => 511700,
                'name' => '达州市',
            ),
            288 => 
            array (
                'id' => 3908,
                'area_id' => 511800,
                'name' => '雅安市',
            ),
            289 => 
            array (
                'id' => 3930,
                'area_id' => 511900,
                'name' => '巴中市',
            ),
            290 => 
            array (
                'id' => 3952,
                'area_id' => 512000,
                'name' => '资阳市',
            ),
            291 => 
            array (
                'id' => 3974,
                'area_id' => 513200,
                'name' => '阿坝藏族羌族自治州',
            ),
            292 => 
            array (
                'id' => 3996,
                'area_id' => 513300,
                'name' => '甘孜藏族自治州',
            ),
            293 => 
            array (
                'id' => 4018,
                'area_id' => 513400,
                'name' => '凉山彝族自治州',
            ),
            294 => 
            array (
                'id' => 4019,
                'area_id' => 520100,
                'name' => '贵阳市',
            ),
            295 => 
            array (
                'id' => 4041,
                'area_id' => 520200,
                'name' => '六盘水市',
            ),
            296 => 
            array (
                'id' => 4063,
                'area_id' => 520300,
                'name' => '遵义市',
            ),
            297 => 
            array (
                'id' => 4085,
                'area_id' => 520400,
                'name' => '安顺市',
            ),
            298 => 
            array (
                'id' => 4107,
                'area_id' => 522200,
                'name' => '铜仁地区',
            ),
            299 => 
            array (
                'id' => 4129,
                'area_id' => 522300,
                'name' => '黔西南布依族苗族自治州',
            ),
            300 => 
            array (
                'id' => 4151,
                'area_id' => 522400,
                'name' => '毕节地区',
            ),
            301 => 
            array (
                'id' => 4173,
                'area_id' => 522600,
                'name' => '黔东南苗族侗族自治州',
            ),
            302 => 
            array (
                'id' => 4195,
                'area_id' => 522700,
                'name' => '黔南布依族苗族自治州',
            ),
            303 => 
            array (
                'id' => 4208,
                'area_id' => 530100,
                'name' => '昆明市',
            ),
            304 => 
            array (
                'id' => 4230,
                'area_id' => 530300,
                'name' => '曲靖市',
            ),
            305 => 
            array (
                'id' => 4252,
                'area_id' => 530400,
                'name' => '玉溪市',
            ),
            306 => 
            array (
                'id' => 4274,
                'area_id' => 530500,
                'name' => '保山市',
            ),
            307 => 
            array (
                'id' => 4296,
                'area_id' => 530600,
                'name' => '昭通市',
            ),
            308 => 
            array (
                'id' => 4318,
                'area_id' => 530700,
                'name' => '丽江市',
            ),
            309 => 
            array (
                'id' => 4340,
                'area_id' => 530800,
                'name' => '思茅市',
            ),
            310 => 
            array (
                'id' => 4362,
                'area_id' => 530900,
                'name' => '临沧市',
            ),
            311 => 
            array (
                'id' => 4384,
                'area_id' => 532300,
                'name' => '楚雄彝族自治州',
            ),
            312 => 
            array (
                'id' => 4406,
                'area_id' => 532500,
                'name' => '红河哈尼族彝族自治州',
            ),
            313 => 
            array (
                'id' => 4428,
                'area_id' => 532600,
                'name' => '文山壮族苗族自治州',
            ),
            314 => 
            array (
                'id' => 4450,
                'area_id' => 532800,
                'name' => '西双版纳傣族自治州',
            ),
            315 => 
            array (
                'id' => 4472,
                'area_id' => 532900,
                'name' => '大理白族自治州',
            ),
            316 => 
            array (
                'id' => 4494,
                'area_id' => 533100,
                'name' => '德宏傣族景颇族自治州',
            ),
            317 => 
            array (
                'id' => 4516,
                'area_id' => 533300,
                'name' => '怒江傈僳族自治州',
            ),
            318 => 
            array (
                'id' => 4538,
                'area_id' => 533400,
                'name' => '迪庆藏族自治州',
            ),
            319 => 
            array (
                'id' => 4544,
                'area_id' => 540100,
                'name' => '拉萨市',
            ),
            320 => 
            array (
                'id' => 4566,
                'area_id' => 542100,
                'name' => '昌都地区',
            ),
            321 => 
            array (
                'id' => 4588,
                'area_id' => 542200,
                'name' => '山南地区',
            ),
            322 => 
            array (
                'id' => 4610,
                'area_id' => 542300,
                'name' => '日喀则地区',
            ),
            323 => 
            array (
                'id' => 4632,
                'area_id' => 542400,
                'name' => '那曲地区',
            ),
            324 => 
            array (
                'id' => 4654,
                'area_id' => 542500,
                'name' => '阿里地区',
            ),
            325 => 
            array (
                'id' => 4676,
                'area_id' => 542600,
                'name' => '林芝地区',
            ),
            326 => 
            array (
                'id' => 4691,
                'area_id' => 610100,
                'name' => '西安市',
            ),
            327 => 
            array (
                'id' => 4713,
                'area_id' => 610200,
                'name' => '铜川市',
            ),
            328 => 
            array (
                'id' => 4735,
                'area_id' => 610300,
                'name' => '宝鸡市',
            ),
            329 => 
            array (
                'id' => 4757,
                'area_id' => 610400,
                'name' => '咸阳市',
            ),
            330 => 
            array (
                'id' => 4779,
                'area_id' => 610500,
                'name' => '渭南市',
            ),
            331 => 
            array (
                'id' => 4801,
                'area_id' => 610600,
                'name' => '延安市',
            ),
            332 => 
            array (
                'id' => 4823,
                'area_id' => 610700,
                'name' => '汉中市',
            ),
            333 => 
            array (
                'id' => 4845,
                'area_id' => 610800,
                'name' => '榆林市',
            ),
            334 => 
            array (
                'id' => 4867,
                'area_id' => 610900,
                'name' => '安康市',
            ),
            335 => 
            array (
                'id' => 4889,
                'area_id' => 611000,
                'name' => '商洛市',
            ),
            336 => 
            array (
                'id' => 4901,
                'area_id' => 620100,
                'name' => '兰州市',
            ),
            337 => 
            array (
                'id' => 4923,
                'area_id' => 620200,
                'name' => '嘉峪关市',
            ),
            338 => 
            array (
                'id' => 4945,
                'area_id' => 620300,
                'name' => '金昌市',
            ),
            339 => 
            array (
                'id' => 4967,
                'area_id' => 620400,
                'name' => '白银市',
            ),
            340 => 
            array (
                'id' => 4989,
                'area_id' => 620500,
                'name' => '天水市',
            ),
            341 => 
            array (
                'id' => 5011,
                'area_id' => 620600,
                'name' => '武威市',
            ),
            342 => 
            array (
                'id' => 5033,
                'area_id' => 620700,
                'name' => '张掖市',
            ),
            343 => 
            array (
                'id' => 5055,
                'area_id' => 620800,
                'name' => '平凉市',
            ),
            344 => 
            array (
                'id' => 5077,
                'area_id' => 620900,
                'name' => '酒泉市',
            ),
            345 => 
            array (
                'id' => 5099,
                'area_id' => 621000,
                'name' => '庆阳市',
            ),
            346 => 
            array (
                'id' => 5121,
                'area_id' => 621100,
                'name' => '定西市',
            ),
            347 => 
            array (
                'id' => 5143,
                'area_id' => 621200,
                'name' => '陇南市',
            ),
            348 => 
            array (
                'id' => 5165,
                'area_id' => 622900,
                'name' => '临夏回族自治州',
            ),
            349 => 
            array (
                'id' => 5187,
                'area_id' => 623000,
                'name' => '甘南藏族自治州',
            ),
            350 => 
            array (
                'id' => 5195,
                'area_id' => 630100,
                'name' => '西宁市',
            ),
            351 => 
            array (
                'id' => 5217,
                'area_id' => 632100,
                'name' => '海东地区',
            ),
            352 => 
            array (
                'id' => 5239,
                'area_id' => 632200,
                'name' => '海北藏族自治州',
            ),
            353 => 
            array (
                'id' => 5261,
                'area_id' => 632300,
                'name' => '黄南藏族自治州',
            ),
            354 => 
            array (
                'id' => 5283,
                'area_id' => 632500,
                'name' => '海南藏族自治州',
            ),
            355 => 
            array (
                'id' => 5305,
                'area_id' => 632600,
                'name' => '果洛藏族自治州',
            ),
            356 => 
            array (
                'id' => 5327,
                'area_id' => 632700,
                'name' => '玉树藏族自治州',
            ),
            357 => 
            array (
                'id' => 5349,
                'area_id' => 632800,
                'name' => '海西蒙古族藏族自治州',
            ),
            358 => 
            array (
                'id' => 5363,
                'area_id' => 640100,
                'name' => '银川市',
            ),
            359 => 
            array (
                'id' => 5385,
                'area_id' => 640200,
                'name' => '石嘴山市',
            ),
            360 => 
            array (
                'id' => 5407,
                'area_id' => 640300,
                'name' => '吴忠市',
            ),
            361 => 
            array (
                'id' => 5429,
                'area_id' => 640400,
                'name' => '固原市',
            ),
            362 => 
            array (
                'id' => 5451,
                'area_id' => 640500,
                'name' => '中卫市',
            ),
            363 => 
            array (
                'id' => 5468,
                'area_id' => 650100,
                'name' => '乌鲁木齐市',
            ),
            364 => 
            array (
                'id' => 5490,
                'area_id' => 650200,
                'name' => '克拉玛依市',
            ),
            365 => 
            array (
                'id' => 5512,
                'area_id' => 652100,
                'name' => '吐鲁番地区',
            ),
            366 => 
            array (
                'id' => 5534,
                'area_id' => 652200,
                'name' => '哈密地区',
            ),
            367 => 
            array (
                'id' => 5556,
                'area_id' => 652300,
                'name' => '昌吉回族自治州',
            ),
            368 => 
            array (
                'id' => 5578,
                'area_id' => 652700,
                'name' => '博尔塔拉蒙古自治州',
            ),
            369 => 
            array (
                'id' => 5600,
                'area_id' => 652800,
                'name' => '巴音郭楞蒙古自治州',
            ),
            370 => 
            array (
                'id' => 5622,
                'area_id' => 652900,
                'name' => '阿克苏地区',
            ),
            371 => 
            array (
                'id' => 5644,
                'area_id' => 653000,
                'name' => '克孜勒苏柯尔克孜自治州',
            ),
            372 => 
            array (
                'id' => 5666,
                'area_id' => 653100,
                'name' => '喀什地区',
            ),
            373 => 
            array (
                'id' => 5688,
                'area_id' => 653200,
                'name' => '和田地区',
            ),
            374 => 
            array (
                'id' => 5710,
                'area_id' => 654000,
                'name' => '伊犁哈萨克自治州',
            ),
            375 => 
            array (
                'id' => 5732,
                'area_id' => 654200,
                'name' => '塔城地区',
            ),
            376 => 
            array (
                'id' => 5754,
                'area_id' => 654300,
                'name' => '阿勒泰地区',
            ),
            377 => 
            array (
                'id' => 5776,
                'area_id' => 659000,
                'name' => '省直辖行政单位',
            ),
            378 => 
            array (
                'id' => 5783,
                'area_id' => 710001,
                'name' => '台北市',
            ),
            379 => 
            array (
                'id' => 5805,
                'area_id' => 710003,
                'name' => '基隆市',
            ),
            380 => 
            array (
                'id' => 5825,
                'area_id' => 810001,
                'name' => '香港',
            ),
            381 => 
            array (
                'id' => 5846,
                'area_id' => 820001,
                'name' => '澳门',
            ),
            382 => 
            array (
                'id' => 5867,
                'area_id' => 110101,
                'name' => '东城区',
            ),
            383 => 
            array (
                'id' => 5869,
                'area_id' => 110102,
                'name' => '西城区',
            ),
            384 => 
            array (
                'id' => 5872,
                'area_id' => 110103,
                'name' => '崇文区',
            ),
            385 => 
            array (
                'id' => 5876,
                'area_id' => 110104,
                'name' => '宣武区',
            ),
            386 => 
            array (
                'id' => 5881,
                'area_id' => 110105,
                'name' => '朝阳区',
            ),
            387 => 
            array (
                'id' => 5887,
                'area_id' => 110106,
                'name' => '丰台区',
            ),
            388 => 
            array (
                'id' => 5894,
                'area_id' => 110107,
                'name' => '石景山区',
            ),
            389 => 
            array (
                'id' => 5902,
                'area_id' => 110108,
                'name' => '海淀区',
            ),
            390 => 
            array (
                'id' => 5911,
                'area_id' => 110109,
                'name' => '门头沟区',
            ),
            391 => 
            array (
                'id' => 5921,
                'area_id' => 110111,
                'name' => '房山区',
            ),
            392 => 
            array (
                'id' => 5932,
                'area_id' => 110112,
                'name' => '通州区',
            ),
            393 => 
            array (
                'id' => 5944,
                'area_id' => 110113,
                'name' => '顺义区',
            ),
            394 => 
            array (
                'id' => 5957,
                'area_id' => 110114,
                'name' => '昌平区',
            ),
            395 => 
            array (
                'id' => 5971,
                'area_id' => 110115,
                'name' => '大兴区',
            ),
            396 => 
            array (
                'id' => 5986,
                'area_id' => 110116,
                'name' => '怀柔区',
            ),
            397 => 
            array (
                'id' => 6002,
                'area_id' => 110117,
                'name' => '平谷区',
            ),
            398 => 
            array (
                'id' => 6003,
                'area_id' => 110228,
                'name' => '密云县',
            ),
            399 => 
            array (
                'id' => 6020,
                'area_id' => 110229,
                'name' => '延庆县',
            ),
            400 => 
            array (
                'id' => 6035,
                'area_id' => 120101,
                'name' => '和平区',
            ),
            401 => 
            array (
                'id' => 6052,
                'area_id' => 120102,
                'name' => '河东区',
            ),
            402 => 
            array (
                'id' => 6069,
                'area_id' => 120103,
                'name' => '河西区',
            ),
            403 => 
            array (
                'id' => 6086,
                'area_id' => 120104,
                'name' => '南开区',
            ),
            404 => 
            array (
                'id' => 6103,
                'area_id' => 120105,
                'name' => '河北区',
            ),
            405 => 
            array (
                'id' => 6120,
                'area_id' => 120106,
                'name' => '红桥区',
            ),
            406 => 
            array (
                'id' => 6137,
                'area_id' => 120107,
                'name' => '塘沽区',
            ),
            407 => 
            array (
                'id' => 6154,
                'area_id' => 120108,
                'name' => '汉沽区',
            ),
            408 => 
            array (
                'id' => 6171,
                'area_id' => 120109,
                'name' => '大港区',
            ),
            409 => 
            array (
                'id' => 6188,
                'area_id' => 120110,
                'name' => '东丽区',
            ),
            410 => 
            array (
                'id' => 6205,
                'area_id' => 120111,
                'name' => '西青区',
            ),
            411 => 
            array (
                'id' => 6222,
                'area_id' => 120112,
                'name' => '津南区',
            ),
            412 => 
            array (
                'id' => 6239,
                'area_id' => 120113,
                'name' => '北辰区',
            ),
            413 => 
            array (
                'id' => 6256,
                'area_id' => 120114,
                'name' => '武清区',
            ),
            414 => 
            array (
                'id' => 6273,
                'area_id' => 120115,
                'name' => '宝坻区',
            ),
            415 => 
            array (
                'id' => 6275,
                'area_id' => 120221,
                'name' => '宁河县',
            ),
            416 => 
            array (
                'id' => 6292,
                'area_id' => 120223,
                'name' => '静海县',
            ),
            417 => 
            array (
                'id' => 6309,
                'area_id' => 120225,
                'name' => '蓟　县',
            ),
            418 => 
            array (
                'id' => 6323,
                'area_id' => 130101,
                'name' => '市辖区',
            ),
            419 => 
            array (
                'id' => 6340,
                'area_id' => 130102,
                'name' => '长安区',
            ),
            420 => 
            array (
                'id' => 6357,
                'area_id' => 130103,
                'name' => '桥东区',
            ),
            421 => 
            array (
                'id' => 6374,
                'area_id' => 130104,
                'name' => '桥西区',
            ),
            422 => 
            array (
                'id' => 6391,
                'area_id' => 130105,
                'name' => '新华区',
            ),
            423 => 
            array (
                'id' => 6408,
                'area_id' => 130107,
                'name' => '井陉矿区',
            ),
            424 => 
            array (
                'id' => 6425,
                'area_id' => 130108,
                'name' => '裕华区',
            ),
            425 => 
            array (
                'id' => 6442,
                'area_id' => 130121,
                'name' => '井陉县',
            ),
            426 => 
            array (
                'id' => 6459,
                'area_id' => 130123,
                'name' => '正定县',
            ),
            427 => 
            array (
                'id' => 6476,
                'area_id' => 130124,
                'name' => '栾城县',
            ),
            428 => 
            array (
                'id' => 6493,
                'area_id' => 130125,
                'name' => '行唐县',
            ),
            429 => 
            array (
                'id' => 6510,
                'area_id' => 130126,
                'name' => '灵寿县',
            ),
            430 => 
            array (
                'id' => 6527,
                'area_id' => 130127,
                'name' => '高邑县',
            ),
            431 => 
            array (
                'id' => 6544,
                'area_id' => 130128,
                'name' => '深泽县',
            ),
            432 => 
            array (
                'id' => 6561,
                'area_id' => 130129,
                'name' => '赞皇县',
            ),
            433 => 
            array (
                'id' => 6578,
                'area_id' => 130130,
                'name' => '无极县',
            ),
            434 => 
            array (
                'id' => 6595,
                'area_id' => 130131,
                'name' => '平山县',
            ),
            435 => 
            array (
                'id' => 6613,
                'area_id' => 130132,
                'name' => '元氏县',
            ),
            436 => 
            array (
                'id' => 6632,
                'area_id' => 130133,
                'name' => '赵　县',
            ),
            437 => 
            array (
                'id' => 6652,
                'area_id' => 130181,
                'name' => '辛集市',
            ),
            438 => 
            array (
                'id' => 6673,
                'area_id' => 130182,
                'name' => '藁城市',
            ),
            439 => 
            array (
                'id' => 6695,
                'area_id' => 130183,
                'name' => '晋州市',
            ),
            440 => 
            array (
                'id' => 6718,
                'area_id' => 130184,
                'name' => '新乐市',
            ),
            441 => 
            array (
                'id' => 6742,
                'area_id' => 130185,
                'name' => '鹿泉市',
            ),
            442 => 
            array (
                'id' => 6743,
                'area_id' => 130201,
                'name' => '市辖区',
            ),
            443 => 
            array (
                'id' => 6768,
                'area_id' => 130202,
                'name' => '路南区',
            ),
            444 => 
            array (
                'id' => 6793,
                'area_id' => 130203,
                'name' => '路北区',
            ),
            445 => 
            array (
                'id' => 6818,
                'area_id' => 130204,
                'name' => '古冶区',
            ),
            446 => 
            array (
                'id' => 6843,
                'area_id' => 130205,
                'name' => '开平区',
            ),
            447 => 
            array (
                'id' => 6868,
                'area_id' => 130207,
                'name' => '丰南区',
            ),
            448 => 
            array (
                'id' => 6893,
                'area_id' => 130208,
                'name' => '丰润区',
            ),
            449 => 
            array (
                'id' => 6918,
                'area_id' => 130223,
                'name' => '滦　县',
            ),
            450 => 
            array (
                'id' => 6943,
                'area_id' => 130224,
                'name' => '滦南县',
            ),
            451 => 
            array (
                'id' => 6968,
                'area_id' => 130225,
                'name' => '乐亭县',
            ),
            452 => 
            array (
                'id' => 6993,
                'area_id' => 130227,
                'name' => '迁西县',
            ),
            453 => 
            array (
                'id' => 7018,
                'area_id' => 130229,
                'name' => '玉田县',
            ),
            454 => 
            array (
                'id' => 7043,
                'area_id' => 130230,
                'name' => '唐海县',
            ),
            455 => 
            array (
                'id' => 7068,
                'area_id' => 130281,
                'name' => '遵化市',
            ),
            456 => 
            array (
                'id' => 7093,
                'area_id' => 130283,
                'name' => '迁安市',
            ),
            457 => 
            array (
                'id' => 7103,
                'area_id' => 130301,
                'name' => '市辖区',
            ),
            458 => 
            array (
                'id' => 7128,
                'area_id' => 130302,
                'name' => '海港区',
            ),
            459 => 
            array (
                'id' => 7153,
                'area_id' => 130303,
                'name' => '山海关区',
            ),
            460 => 
            array (
                'id' => 7178,
                'area_id' => 130304,
                'name' => '北戴河区',
            ),
            461 => 
            array (
                'id' => 7203,
                'area_id' => 130321,
                'name' => '青龙满族自治县',
            ),
            462 => 
            array (
                'id' => 7228,
                'area_id' => 130322,
                'name' => '昌黎县',
            ),
            463 => 
            array (
                'id' => 7253,
                'area_id' => 130323,
                'name' => '抚宁县',
            ),
            464 => 
            array (
                'id' => 7278,
                'area_id' => 130324,
                'name' => '卢龙县',
            ),
            465 => 
            array (
                'id' => 7295,
                'area_id' => 130401,
                'name' => '市辖区',
            ),
            466 => 
            array (
                'id' => 7320,
                'area_id' => 130402,
                'name' => '邯山区',
            ),
            467 => 
            array (
                'id' => 7345,
                'area_id' => 130403,
                'name' => '丛台区',
            ),
            468 => 
            array (
                'id' => 7370,
                'area_id' => 130404,
                'name' => '复兴区',
            ),
            469 => 
            array (
                'id' => 7395,
                'area_id' => 130406,
                'name' => '峰峰矿区',
            ),
            470 => 
            array (
                'id' => 7420,
                'area_id' => 130421,
                'name' => '邯郸县',
            ),
            471 => 
            array (
                'id' => 7445,
                'area_id' => 130423,
                'name' => '临漳县',
            ),
            472 => 
            array (
                'id' => 7470,
                'area_id' => 130424,
                'name' => '成安县',
            ),
            473 => 
            array (
                'id' => 7495,
                'area_id' => 130425,
                'name' => '大名县',
            ),
            474 => 
            array (
                'id' => 7520,
                'area_id' => 130426,
                'name' => '涉　县',
            ),
            475 => 
            array (
                'id' => 7545,
                'area_id' => 130427,
                'name' => '磁　县',
            ),
            476 => 
            array (
                'id' => 7570,
                'area_id' => 130428,
                'name' => '肥乡县',
            ),
            477 => 
            array (
                'id' => 7595,
                'area_id' => 130429,
                'name' => '永年县',
            ),
            478 => 
            array (
                'id' => 7620,
                'area_id' => 130430,
                'name' => '邱　县',
            ),
            479 => 
            array (
                'id' => 7645,
                'area_id' => 130431,
                'name' => '鸡泽县',
            ),
            480 => 
            array (
                'id' => 7670,
                'area_id' => 130432,
                'name' => '广平县',
            ),
            481 => 
            array (
                'id' => 7695,
                'area_id' => 130433,
                'name' => '馆陶县',
            ),
            482 => 
            array (
                'id' => 7720,
                'area_id' => 130434,
                'name' => '魏　县',
            ),
            483 => 
            array (
                'id' => 7745,
                'area_id' => 130435,
                'name' => '曲周县',
            ),
            484 => 
            array (
                'id' => 7770,
                'area_id' => 130481,
                'name' => '武安市',
            ),
            485 => 
            array (
                'id' => 7775,
                'area_id' => 130501,
                'name' => '市辖区',
            ),
            486 => 
            array (
                'id' => 7800,
                'area_id' => 130502,
                'name' => '桥东区',
            ),
            487 => 
            array (
                'id' => 7825,
                'area_id' => 130503,
                'name' => '桥西区',
            ),
            488 => 
            array (
                'id' => 7850,
                'area_id' => 130521,
                'name' => '邢台县',
            ),
            489 => 
            array (
                'id' => 7875,
                'area_id' => 130522,
                'name' => '临城县',
            ),
            490 => 
            array (
                'id' => 7900,
                'area_id' => 130523,
                'name' => '内丘县',
            ),
            491 => 
            array (
                'id' => 7925,
                'area_id' => 130524,
                'name' => '柏乡县',
            ),
            492 => 
            array (
                'id' => 7950,
                'area_id' => 130525,
                'name' => '隆尧县',
            ),
            493 => 
            array (
                'id' => 7975,
                'area_id' => 130526,
                'name' => '任　县',
            ),
            494 => 
            array (
                'id' => 8000,
                'area_id' => 130527,
                'name' => '南和县',
            ),
            495 => 
            array (
                'id' => 8025,
                'area_id' => 130528,
                'name' => '宁晋县',
            ),
            496 => 
            array (
                'id' => 8050,
                'area_id' => 130529,
                'name' => '巨鹿县',
            ),
            497 => 
            array (
                'id' => 8075,
                'area_id' => 130530,
                'name' => '新河县',
            ),
            498 => 
            array (
                'id' => 8100,
                'area_id' => 130531,
                'name' => '广宗县',
            ),
            499 => 
            array (
                'id' => 8125,
                'area_id' => 130532,
                'name' => '平乡县',
            ),
        ));
        \DB::table('areas')->insert(array (
            0 => 
            array (
                'id' => 8150,
                'area_id' => 130533,
                'name' => '威　县',
            ),
            1 => 
            array (
                'id' => 8175,
                'area_id' => 130534,
                'name' => '清河县',
            ),
            2 => 
            array (
                'id' => 8200,
                'area_id' => 130535,
                'name' => '临西县',
            ),
            3 => 
            array (
                'id' => 8225,
                'area_id' => 130581,
                'name' => '南宫市',
            ),
            4 => 
            array (
                'id' => 8250,
                'area_id' => 130582,
                'name' => '沙河市',
            ),
            5 => 
            array (
                'id' => 8255,
                'area_id' => 130601,
                'name' => '市辖区',
            ),
            6 => 
            array (
                'id' => 8280,
                'area_id' => 130602,
                'name' => '新市区',
            ),
            7 => 
            array (
                'id' => 8305,
                'area_id' => 130603,
                'name' => '北市区',
            ),
            8 => 
            array (
                'id' => 8330,
                'area_id' => 130604,
                'name' => '南市区',
            ),
            9 => 
            array (
                'id' => 8355,
                'area_id' => 130621,
                'name' => '满城县',
            ),
            10 => 
            array (
                'id' => 8380,
                'area_id' => 130622,
                'name' => '清苑县',
            ),
            11 => 
            array (
                'id' => 8405,
                'area_id' => 130623,
                'name' => '涞水县',
            ),
            12 => 
            array (
                'id' => 8430,
                'area_id' => 130624,
                'name' => '阜平县',
            ),
            13 => 
            array (
                'id' => 8455,
                'area_id' => 130625,
                'name' => '徐水县',
            ),
            14 => 
            array (
                'id' => 8480,
                'area_id' => 130626,
                'name' => '定兴县',
            ),
            15 => 
            array (
                'id' => 8505,
                'area_id' => 130627,
                'name' => '唐　县',
            ),
            16 => 
            array (
                'id' => 8530,
                'area_id' => 130628,
                'name' => '高阳县',
            ),
            17 => 
            array (
                'id' => 8555,
                'area_id' => 130629,
                'name' => '容城县',
            ),
            18 => 
            array (
                'id' => 8580,
                'area_id' => 130630,
                'name' => '涞源县',
            ),
            19 => 
            array (
                'id' => 8605,
                'area_id' => 130631,
                'name' => '望都县',
            ),
            20 => 
            array (
                'id' => 8630,
                'area_id' => 130632,
                'name' => '安新县',
            ),
            21 => 
            array (
                'id' => 8655,
                'area_id' => 130633,
                'name' => '易　县',
            ),
            22 => 
            array (
                'id' => 8680,
                'area_id' => 130634,
                'name' => '曲阳县',
            ),
            23 => 
            array (
                'id' => 8705,
                'area_id' => 130635,
                'name' => '蠡　县',
            ),
            24 => 
            array (
                'id' => 8730,
                'area_id' => 130636,
                'name' => '顺平县',
            ),
            25 => 
            array (
                'id' => 8755,
                'area_id' => 130637,
                'name' => '博野县',
            ),
            26 => 
            array (
                'id' => 8780,
                'area_id' => 130638,
                'name' => '雄　县',
            ),
            27 => 
            array (
                'id' => 8805,
                'area_id' => 130681,
                'name' => '涿州市',
            ),
            28 => 
            array (
                'id' => 8830,
                'area_id' => 130682,
                'name' => '定州市',
            ),
            29 => 
            array (
                'id' => 8855,
                'area_id' => 130683,
                'name' => '安国市',
            ),
            30 => 
            array (
                'id' => 8881,
                'area_id' => 130684,
                'name' => '高碑店市',
            ),
            31 => 
            array (
                'id' => 8882,
                'area_id' => 130701,
                'name' => '市辖区',
            ),
            32 => 
            array (
                'id' => 8909,
                'area_id' => 130702,
                'name' => '桥东区',
            ),
            33 => 
            array (
                'id' => 8936,
                'area_id' => 130703,
                'name' => '桥西区',
            ),
            34 => 
            array (
                'id' => 8963,
                'area_id' => 130705,
                'name' => '宣化区',
            ),
            35 => 
            array (
                'id' => 8990,
                'area_id' => 130706,
                'name' => '下花园区',
            ),
            36 => 
            array (
                'id' => 9017,
                'area_id' => 130721,
                'name' => '宣化县',
            ),
            37 => 
            array (
                'id' => 9044,
                'area_id' => 130722,
                'name' => '张北县',
            ),
            38 => 
            array (
                'id' => 9071,
                'area_id' => 130723,
                'name' => '康保县',
            ),
            39 => 
            array (
                'id' => 9098,
                'area_id' => 130724,
                'name' => '沽源县',
            ),
            40 => 
            array (
                'id' => 9125,
                'area_id' => 130725,
                'name' => '尚义县',
            ),
            41 => 
            array (
                'id' => 9152,
                'area_id' => 130726,
                'name' => '蔚　县',
            ),
            42 => 
            array (
                'id' => 9179,
                'area_id' => 130727,
                'name' => '阳原县',
            ),
            43 => 
            array (
                'id' => 9206,
                'area_id' => 130728,
                'name' => '怀安县',
            ),
            44 => 
            array (
                'id' => 9233,
                'area_id' => 130729,
                'name' => '万全县',
            ),
            45 => 
            array (
                'id' => 9260,
                'area_id' => 130730,
                'name' => '怀来县',
            ),
            46 => 
            array (
                'id' => 9287,
                'area_id' => 130731,
                'name' => '涿鹿县',
            ),
            47 => 
            array (
                'id' => 9314,
                'area_id' => 130732,
                'name' => '赤城县',
            ),
            48 => 
            array (
                'id' => 9341,
                'area_id' => 130733,
                'name' => '崇礼县',
            ),
            49 => 
            array (
                'id' => 9350,
                'area_id' => 130801,
                'name' => '市辖区',
            ),
            50 => 
            array (
                'id' => 9377,
                'area_id' => 130802,
                'name' => '双桥区',
            ),
            51 => 
            array (
                'id' => 9404,
                'area_id' => 130803,
                'name' => '双滦区',
            ),
            52 => 
            array (
                'id' => 9431,
                'area_id' => 130804,
                'name' => '鹰手营子矿区',
            ),
            53 => 
            array (
                'id' => 9458,
                'area_id' => 130821,
                'name' => '承德县',
            ),
            54 => 
            array (
                'id' => 9485,
                'area_id' => 130822,
                'name' => '兴隆县',
            ),
            55 => 
            array (
                'id' => 9512,
                'area_id' => 130823,
                'name' => '平泉县',
            ),
            56 => 
            array (
                'id' => 9539,
                'area_id' => 130824,
                'name' => '滦平县',
            ),
            57 => 
            array (
                'id' => 9566,
                'area_id' => 130825,
                'name' => '隆化县',
            ),
            58 => 
            array (
                'id' => 9593,
                'area_id' => 130826,
                'name' => '丰宁满族自治县',
            ),
            59 => 
            array (
                'id' => 9620,
                'area_id' => 130827,
                'name' => '宽城满族自治县',
            ),
            60 => 
            array (
                'id' => 9647,
                'area_id' => 130828,
                'name' => '围场满族蒙古族自治县',
            ),
            61 => 
            array (
                'id' => 9662,
                'area_id' => 130901,
                'name' => '市辖区',
            ),
            62 => 
            array (
                'id' => 9689,
                'area_id' => 130902,
                'name' => '新华区',
            ),
            63 => 
            array (
                'id' => 9716,
                'area_id' => 130903,
                'name' => '运河区',
            ),
            64 => 
            array (
                'id' => 9743,
                'area_id' => 130921,
                'name' => '沧　县',
            ),
            65 => 
            array (
                'id' => 9770,
                'area_id' => 130922,
                'name' => '青　县',
            ),
            66 => 
            array (
                'id' => 9797,
                'area_id' => 130923,
                'name' => '东光县',
            ),
            67 => 
            array (
                'id' => 9824,
                'area_id' => 130924,
                'name' => '海兴县',
            ),
            68 => 
            array (
                'id' => 9851,
                'area_id' => 130925,
                'name' => '盐山县',
            ),
            69 => 
            array (
                'id' => 9878,
                'area_id' => 130926,
                'name' => '肃宁县',
            ),
            70 => 
            array (
                'id' => 9905,
                'area_id' => 130927,
                'name' => '南皮县',
            ),
            71 => 
            array (
                'id' => 9932,
                'area_id' => 130928,
                'name' => '吴桥县',
            ),
            72 => 
            array (
                'id' => 9959,
                'area_id' => 130929,
                'name' => '献　县',
            ),
            73 => 
            array (
                'id' => 9986,
                'area_id' => 130930,
                'name' => '孟村回族自治县',
            ),
            74 => 
            array (
                'id' => 10013,
                'area_id' => 130981,
                'name' => '泊头市',
            ),
            75 => 
            array (
                'id' => 10040,
                'area_id' => 130982,
                'name' => '任丘市',
            ),
            76 => 
            array (
                'id' => 10067,
                'area_id' => 130983,
                'name' => '黄骅市',
            ),
            77 => 
            array (
                'id' => 10094,
                'area_id' => 130984,
                'name' => '河间市',
            ),
            78 => 
            array (
                'id' => 10104,
                'area_id' => 131001,
                'name' => '市辖区',
            ),
            79 => 
            array (
                'id' => 10131,
                'area_id' => 131002,
                'name' => '安次区',
            ),
            80 => 
            array (
                'id' => 10158,
                'area_id' => 131003,
                'name' => '广阳区',
            ),
            81 => 
            array (
                'id' => 10185,
                'area_id' => 131022,
                'name' => '固安县',
            ),
            82 => 
            array (
                'id' => 10212,
                'area_id' => 131023,
                'name' => '永清县',
            ),
            83 => 
            array (
                'id' => 10239,
                'area_id' => 131024,
                'name' => '香河县',
            ),
            84 => 
            array (
                'id' => 10266,
                'area_id' => 131025,
                'name' => '大城县',
            ),
            85 => 
            array (
                'id' => 10293,
                'area_id' => 131026,
                'name' => '文安县',
            ),
            86 => 
            array (
                'id' => 10320,
                'area_id' => 131028,
                'name' => '大厂回族自治县',
            ),
            87 => 
            array (
                'id' => 10347,
                'area_id' => 131081,
                'name' => '霸州市',
            ),
            88 => 
            array (
                'id' => 10374,
                'area_id' => 131082,
                'name' => '三河市',
            ),
            89 => 
            array (
                'id' => 10390,
                'area_id' => 131101,
                'name' => '市辖区',
            ),
            90 => 
            array (
                'id' => 10417,
                'area_id' => 131102,
                'name' => '桃城区',
            ),
            91 => 
            array (
                'id' => 10444,
                'area_id' => 131121,
                'name' => '枣强县',
            ),
            92 => 
            array (
                'id' => 10471,
                'area_id' => 131122,
                'name' => '武邑县',
            ),
            93 => 
            array (
                'id' => 10498,
                'area_id' => 131123,
                'name' => '武强县',
            ),
            94 => 
            array (
                'id' => 10525,
                'area_id' => 131124,
                'name' => '饶阳县',
            ),
            95 => 
            array (
                'id' => 10552,
                'area_id' => 131125,
                'name' => '安平县',
            ),
            96 => 
            array (
                'id' => 10579,
                'area_id' => 131126,
                'name' => '故城县',
            ),
            97 => 
            array (
                'id' => 10606,
                'area_id' => 131127,
                'name' => '景　县',
            ),
            98 => 
            array (
                'id' => 10633,
                'area_id' => 131128,
                'name' => '阜城县',
            ),
            99 => 
            array (
                'id' => 10660,
                'area_id' => 131181,
                'name' => '冀州市',
            ),
            100 => 
            array (
                'id' => 10687,
                'area_id' => 131182,
                'name' => '深州市',
            ),
            101 => 
            array (
                'id' => 10702,
                'area_id' => 140101,
                'name' => '市辖区',
            ),
            102 => 
            array (
                'id' => 10729,
                'area_id' => 140105,
                'name' => '小店区',
            ),
            103 => 
            array (
                'id' => 10756,
                'area_id' => 140106,
                'name' => '迎泽区',
            ),
            104 => 
            array (
                'id' => 10783,
                'area_id' => 140107,
                'name' => '杏花岭区',
            ),
            105 => 
            array (
                'id' => 10810,
                'area_id' => 140108,
                'name' => '尖草坪区',
            ),
            106 => 
            array (
                'id' => 10837,
                'area_id' => 140109,
                'name' => '万柏林区',
            ),
            107 => 
            array (
                'id' => 10864,
                'area_id' => 140110,
                'name' => '晋源区',
            ),
            108 => 
            array (
                'id' => 10891,
                'area_id' => 140121,
                'name' => '清徐县',
            ),
            109 => 
            array (
                'id' => 10918,
                'area_id' => 140122,
                'name' => '阳曲县',
            ),
            110 => 
            array (
                'id' => 10945,
                'area_id' => 140123,
                'name' => '娄烦县',
            ),
            111 => 
            array (
                'id' => 10972,
                'area_id' => 140181,
                'name' => '古交市',
            ),
            112 => 
            array (
                'id' => 10988,
                'area_id' => 140201,
                'name' => '市辖区',
            ),
            113 => 
            array (
                'id' => 11015,
                'area_id' => 140202,
                'name' => '城　区',
            ),
            114 => 
            array (
                'id' => 11042,
                'area_id' => 140203,
                'name' => '矿　区',
            ),
            115 => 
            array (
                'id' => 11069,
                'area_id' => 140211,
                'name' => '南郊区',
            ),
            116 => 
            array (
                'id' => 11096,
                'area_id' => 140212,
                'name' => '新荣区',
            ),
            117 => 
            array (
                'id' => 11123,
                'area_id' => 140221,
                'name' => '阳高县',
            ),
            118 => 
            array (
                'id' => 11150,
                'area_id' => 140222,
                'name' => '天镇县',
            ),
            119 => 
            array (
                'id' => 11177,
                'area_id' => 140223,
                'name' => '广灵县',
            ),
            120 => 
            array (
                'id' => 11204,
                'area_id' => 140224,
                'name' => '灵丘县',
            ),
            121 => 
            array (
                'id' => 11231,
                'area_id' => 140225,
                'name' => '浑源县',
            ),
            122 => 
            array (
                'id' => 11258,
                'area_id' => 140226,
                'name' => '左云县',
            ),
            123 => 
            array (
                'id' => 11285,
                'area_id' => 140227,
                'name' => '大同县',
            ),
            124 => 
            array (
                'id' => 11300,
                'area_id' => 140301,
                'name' => '市辖区',
            ),
            125 => 
            array (
                'id' => 11327,
                'area_id' => 140302,
                'name' => '城　区',
            ),
            126 => 
            array (
                'id' => 11354,
                'area_id' => 140303,
                'name' => '矿　区',
            ),
            127 => 
            array (
                'id' => 11381,
                'area_id' => 140311,
                'name' => '郊　区',
            ),
            128 => 
            array (
                'id' => 11408,
                'area_id' => 140321,
                'name' => '平定县',
            ),
            129 => 
            array (
                'id' => 11435,
                'area_id' => 140322,
                'name' => '盂　县',
            ),
            130 => 
            array (
                'id' => 11456,
                'area_id' => 140401,
                'name' => '市辖区',
            ),
            131 => 
            array (
                'id' => 11483,
                'area_id' => 140402,
                'name' => '城　区',
            ),
            132 => 
            array (
                'id' => 11510,
                'area_id' => 140411,
                'name' => '郊　区',
            ),
            133 => 
            array (
                'id' => 11537,
                'area_id' => 140421,
                'name' => '长治县',
            ),
            134 => 
            array (
                'id' => 11564,
                'area_id' => 140423,
                'name' => '襄垣县',
            ),
            135 => 
            array (
                'id' => 11591,
                'area_id' => 140424,
                'name' => '屯留县',
            ),
            136 => 
            array (
                'id' => 11618,
                'area_id' => 140425,
                'name' => '平顺县',
            ),
            137 => 
            array (
                'id' => 11645,
                'area_id' => 140426,
                'name' => '黎城县',
            ),
            138 => 
            array (
                'id' => 11672,
                'area_id' => 140427,
                'name' => '壶关县',
            ),
            139 => 
            array (
                'id' => 11699,
                'area_id' => 140428,
                'name' => '长子县',
            ),
            140 => 
            array (
                'id' => 11726,
                'area_id' => 140429,
                'name' => '武乡县',
            ),
            141 => 
            array (
                'id' => 11753,
                'area_id' => 140430,
                'name' => '沁　县',
            ),
            142 => 
            array (
                'id' => 11780,
                'area_id' => 140431,
                'name' => '沁源县',
            ),
            143 => 
            array (
                'id' => 11807,
                'area_id' => 140481,
                'name' => '潞城市',
            ),
            144 => 
            array (
                'id' => 11820,
                'area_id' => 140501,
                'name' => '市辖区',
            ),
            145 => 
            array (
                'id' => 11847,
                'area_id' => 140502,
                'name' => '城　区',
            ),
            146 => 
            array (
                'id' => 11874,
                'area_id' => 140521,
                'name' => '沁水县',
            ),
            147 => 
            array (
                'id' => 11901,
                'area_id' => 140522,
                'name' => '阳城县',
            ),
            148 => 
            array (
                'id' => 11928,
                'area_id' => 140524,
                'name' => '陵川县',
            ),
            149 => 
            array (
                'id' => 11955,
                'area_id' => 140525,
                'name' => '泽州县',
            ),
            150 => 
            array (
                'id' => 11982,
                'area_id' => 140581,
                'name' => '高平市',
            ),
            151 => 
            array (
                'id' => 12002,
                'area_id' => 140601,
                'name' => '市辖区',
            ),
            152 => 
            array (
                'id' => 12029,
                'area_id' => 140602,
                'name' => '朔城区',
            ),
            153 => 
            array (
                'id' => 12056,
                'area_id' => 140603,
                'name' => '平鲁区',
            ),
            154 => 
            array (
                'id' => 12083,
                'area_id' => 140621,
                'name' => '山阴县',
            ),
            155 => 
            array (
                'id' => 12110,
                'area_id' => 140622,
                'name' => '应　县',
            ),
            156 => 
            array (
                'id' => 12137,
                'area_id' => 140623,
                'name' => '右玉县',
            ),
            157 => 
            array (
                'id' => 12164,
                'area_id' => 140624,
                'name' => '怀仁县',
            ),
            158 => 
            array (
                'id' => 12184,
                'area_id' => 140701,
                'name' => '市辖区',
            ),
            159 => 
            array (
                'id' => 12211,
                'area_id' => 140702,
                'name' => '榆次区',
            ),
            160 => 
            array (
                'id' => 12238,
                'area_id' => 140721,
                'name' => '榆社县',
            ),
            161 => 
            array (
                'id' => 12265,
                'area_id' => 140722,
                'name' => '左权县',
            ),
            162 => 
            array (
                'id' => 12292,
                'area_id' => 140723,
                'name' => '和顺县',
            ),
            163 => 
            array (
                'id' => 12319,
                'area_id' => 140724,
                'name' => '昔阳县',
            ),
            164 => 
            array (
                'id' => 12346,
                'area_id' => 140725,
                'name' => '寿阳县',
            ),
            165 => 
            array (
                'id' => 12373,
                'area_id' => 140726,
                'name' => '太谷县',
            ),
            166 => 
            array (
                'id' => 12400,
                'area_id' => 140727,
                'name' => '祁　县',
            ),
            167 => 
            array (
                'id' => 12427,
                'area_id' => 140728,
                'name' => '平遥县',
            ),
            168 => 
            array (
                'id' => 12454,
                'area_id' => 140729,
                'name' => '灵石县',
            ),
            169 => 
            array (
                'id' => 12481,
                'area_id' => 140781,
                'name' => '介休市',
            ),
            170 => 
            array (
                'id' => 12496,
                'area_id' => 140801,
                'name' => '市辖区',
            ),
            171 => 
            array (
                'id' => 12523,
                'area_id' => 140802,
                'name' => '盐湖区',
            ),
            172 => 
            array (
                'id' => 12550,
                'area_id' => 140821,
                'name' => '临猗县',
            ),
            173 => 
            array (
                'id' => 12577,
                'area_id' => 140822,
                'name' => '万荣县',
            ),
            174 => 
            array (
                'id' => 12604,
                'area_id' => 140823,
                'name' => '闻喜县',
            ),
            175 => 
            array (
                'id' => 12631,
                'area_id' => 140824,
                'name' => '稷山县',
            ),
            176 => 
            array (
                'id' => 12658,
                'area_id' => 140825,
                'name' => '新绛县',
            ),
            177 => 
            array (
                'id' => 12685,
                'area_id' => 140826,
                'name' => '绛　县',
            ),
            178 => 
            array (
                'id' => 12712,
                'area_id' => 140827,
                'name' => '垣曲县',
            ),
            179 => 
            array (
                'id' => 12739,
                'area_id' => 140828,
                'name' => '夏　县',
            ),
            180 => 
            array (
                'id' => 12766,
                'area_id' => 140829,
                'name' => '平陆县',
            ),
            181 => 
            array (
                'id' => 12793,
                'area_id' => 140830,
                'name' => '芮城县',
            ),
            182 => 
            array (
                'id' => 12820,
                'area_id' => 140881,
                'name' => '永济市',
            ),
            183 => 
            array (
                'id' => 12847,
                'area_id' => 140882,
                'name' => '河津市',
            ),
            184 => 
            array (
                'id' => 12860,
                'area_id' => 140901,
                'name' => '市辖区',
            ),
            185 => 
            array (
                'id' => 12887,
                'area_id' => 140902,
                'name' => '忻府区',
            ),
            186 => 
            array (
                'id' => 12914,
                'area_id' => 140921,
                'name' => '定襄县',
            ),
            187 => 
            array (
                'id' => 12941,
                'area_id' => 140922,
                'name' => '五台县',
            ),
            188 => 
            array (
                'id' => 12968,
                'area_id' => 140923,
                'name' => '代　县',
            ),
            189 => 
            array (
                'id' => 12995,
                'area_id' => 140924,
                'name' => '繁峙县',
            ),
            190 => 
            array (
                'id' => 13022,
                'area_id' => 140925,
                'name' => '宁武县',
            ),
            191 => 
            array (
                'id' => 13049,
                'area_id' => 140926,
                'name' => '静乐县',
            ),
            192 => 
            array (
                'id' => 13076,
                'area_id' => 140927,
                'name' => '神池县',
            ),
            193 => 
            array (
                'id' => 13103,
                'area_id' => 140928,
                'name' => '五寨县',
            ),
            194 => 
            array (
                'id' => 13130,
                'area_id' => 140929,
                'name' => '岢岚县',
            ),
            195 => 
            array (
                'id' => 13157,
                'area_id' => 140930,
                'name' => '河曲县',
            ),
            196 => 
            array (
                'id' => 13184,
                'area_id' => 140931,
                'name' => '保德县',
            ),
            197 => 
            array (
                'id' => 13211,
                'area_id' => 140932,
                'name' => '偏关县',
            ),
            198 => 
            array (
                'id' => 13238,
                'area_id' => 140981,
                'name' => '原平市',
            ),
            199 => 
            array (
                'id' => 13250,
                'area_id' => 141001,
                'name' => '市辖区',
            ),
            200 => 
            array (
                'id' => 13277,
                'area_id' => 141002,
                'name' => '尧都区',
            ),
            201 => 
            array (
                'id' => 13304,
                'area_id' => 141021,
                'name' => '曲沃县',
            ),
            202 => 
            array (
                'id' => 13331,
                'area_id' => 141022,
                'name' => '翼城县',
            ),
            203 => 
            array (
                'id' => 13358,
                'area_id' => 141023,
                'name' => '襄汾县',
            ),
            204 => 
            array (
                'id' => 13385,
                'area_id' => 141024,
                'name' => '洪洞县',
            ),
            205 => 
            array (
                'id' => 13412,
                'area_id' => 141025,
                'name' => '古　县',
            ),
            206 => 
            array (
                'id' => 13439,
                'area_id' => 141026,
                'name' => '安泽县',
            ),
            207 => 
            array (
                'id' => 13466,
                'area_id' => 141027,
                'name' => '浮山县',
            ),
            208 => 
            array (
                'id' => 13493,
                'area_id' => 141028,
                'name' => '吉　县',
            ),
            209 => 
            array (
                'id' => 13520,
                'area_id' => 141029,
                'name' => '乡宁县',
            ),
            210 => 
            array (
                'id' => 13547,
                'area_id' => 141030,
                'name' => '大宁县',
            ),
            211 => 
            array (
                'id' => 13574,
                'area_id' => 141031,
                'name' => '隰　县',
            ),
            212 => 
            array (
                'id' => 13601,
                'area_id' => 141032,
                'name' => '永和县',
            ),
            213 => 
            array (
                'id' => 13628,
                'area_id' => 141033,
                'name' => '蒲　县',
            ),
            214 => 
            array (
                'id' => 13655,
                'area_id' => 141034,
                'name' => '汾西县',
            ),
            215 => 
            array (
                'id' => 13682,
                'area_id' => 141081,
                'name' => '侯马市',
            ),
            216 => 
            array (
                'id' => 13709,
                'area_id' => 141082,
                'name' => '霍州市',
            ),
            217 => 
            array (
                'id' => 13718,
                'area_id' => 141101,
                'name' => '市辖区',
            ),
            218 => 
            array (
                'id' => 13745,
                'area_id' => 141102,
                'name' => '离石区',
            ),
            219 => 
            array (
                'id' => 13772,
                'area_id' => 141121,
                'name' => '文水县',
            ),
            220 => 
            array (
                'id' => 13799,
                'area_id' => 141122,
                'name' => '交城县',
            ),
            221 => 
            array (
                'id' => 13826,
                'area_id' => 141123,
                'name' => '兴　县',
            ),
            222 => 
            array (
                'id' => 13853,
                'area_id' => 141124,
                'name' => '临　县',
            ),
            223 => 
            array (
                'id' => 13880,
                'area_id' => 141125,
                'name' => '柳林县',
            ),
            224 => 
            array (
                'id' => 13907,
                'area_id' => 141126,
                'name' => '石楼县',
            ),
            225 => 
            array (
                'id' => 13934,
                'area_id' => 141127,
                'name' => '岚　县',
            ),
            226 => 
            array (
                'id' => 13961,
                'area_id' => 141128,
                'name' => '方山县',
            ),
            227 => 
            array (
                'id' => 13988,
                'area_id' => 141129,
                'name' => '中阳县',
            ),
            228 => 
            array (
                'id' => 14015,
                'area_id' => 141130,
                'name' => '交口县',
            ),
            229 => 
            array (
                'id' => 14042,
                'area_id' => 141181,
                'name' => '孝义市',
            ),
            230 => 
            array (
                'id' => 14069,
                'area_id' => 141182,
                'name' => '汾阳市',
            ),
            231 => 
            array (
                'id' => 14082,
                'area_id' => 150101,
                'name' => '市辖区',
            ),
            232 => 
            array (
                'id' => 14109,
                'area_id' => 150102,
                'name' => '新城区',
            ),
            233 => 
            array (
                'id' => 14136,
                'area_id' => 150103,
                'name' => '回民区',
            ),
            234 => 
            array (
                'id' => 14163,
                'area_id' => 150104,
                'name' => '玉泉区',
            ),
            235 => 
            array (
                'id' => 14190,
                'area_id' => 150105,
                'name' => '赛罕区',
            ),
            236 => 
            array (
                'id' => 14217,
                'area_id' => 150121,
                'name' => '土默特左旗',
            ),
            237 => 
            array (
                'id' => 14244,
                'area_id' => 150122,
                'name' => '托克托县',
            ),
            238 => 
            array (
                'id' => 14271,
                'area_id' => 150123,
                'name' => '和林格尔县',
            ),
            239 => 
            array (
                'id' => 14298,
                'area_id' => 150124,
                'name' => '清水河县',
            ),
            240 => 
            array (
                'id' => 14325,
                'area_id' => 150125,
                'name' => '武川县',
            ),
            241 => 
            array (
                'id' => 14342,
                'area_id' => 150201,
                'name' => '市辖区',
            ),
            242 => 
            array (
                'id' => 14369,
                'area_id' => 150202,
                'name' => '东河区',
            ),
            243 => 
            array (
                'id' => 14396,
                'area_id' => 150203,
                'name' => '昆都仑区',
            ),
            244 => 
            array (
                'id' => 14423,
                'area_id' => 150204,
                'name' => '青山区',
            ),
            245 => 
            array (
                'id' => 14450,
                'area_id' => 150205,
                'name' => '石拐区',
            ),
            246 => 
            array (
                'id' => 14477,
                'area_id' => 150206,
                'name' => '白云矿区',
            ),
            247 => 
            array (
                'id' => 14504,
                'area_id' => 150207,
                'name' => '九原区',
            ),
            248 => 
            array (
                'id' => 14531,
                'area_id' => 150221,
                'name' => '土默特右旗',
            ),
            249 => 
            array (
                'id' => 14558,
                'area_id' => 150222,
                'name' => '固阳县',
            ),
            250 => 
            array (
                'id' => 14585,
                'area_id' => 150223,
                'name' => '达尔罕茂明安联合旗',
            ),
            251 => 
            array (
                'id' => 14602,
                'area_id' => 150301,
                'name' => '市辖区',
            ),
            252 => 
            array (
                'id' => 14629,
                'area_id' => 150302,
                'name' => '海勃湾区',
            ),
            253 => 
            array (
                'id' => 14656,
                'area_id' => 150303,
                'name' => '海南区',
            ),
            254 => 
            array (
                'id' => 14683,
                'area_id' => 150304,
                'name' => '乌达区',
            ),
            255 => 
            array (
                'id' => 14706,
                'area_id' => 150401,
                'name' => '市辖区',
            ),
            256 => 
            array (
                'id' => 14733,
                'area_id' => 150402,
                'name' => '红山区',
            ),
            257 => 
            array (
                'id' => 14760,
                'area_id' => 150403,
                'name' => '元宝山区',
            ),
            258 => 
            array (
                'id' => 14787,
                'area_id' => 150404,
                'name' => '松山区',
            ),
            259 => 
            array (
                'id' => 14814,
                'area_id' => 150421,
                'name' => '阿鲁科尔沁旗',
            ),
            260 => 
            array (
                'id' => 14841,
                'area_id' => 150422,
                'name' => '巴林左旗',
            ),
            261 => 
            array (
                'id' => 14868,
                'area_id' => 150423,
                'name' => '巴林右旗',
            ),
            262 => 
            array (
                'id' => 14895,
                'area_id' => 150424,
                'name' => '林西县',
            ),
            263 => 
            array (
                'id' => 14922,
                'area_id' => 150425,
                'name' => '克什克腾旗',
            ),
            264 => 
            array (
                'id' => 14949,
                'area_id' => 150426,
                'name' => '翁牛特旗',
            ),
            265 => 
            array (
                'id' => 14976,
                'area_id' => 150428,
                'name' => '喀喇沁旗',
            ),
            266 => 
            array (
                'id' => 15003,
                'area_id' => 150429,
                'name' => '宁城县',
            ),
            267 => 
            array (
                'id' => 15030,
                'area_id' => 150430,
                'name' => '敖汉旗',
            ),
            268 => 
            array (
                'id' => 15044,
                'area_id' => 150501,
                'name' => '市辖区',
            ),
            269 => 
            array (
                'id' => 15071,
                'area_id' => 150502,
                'name' => '科尔沁区',
            ),
            270 => 
            array (
                'id' => 15098,
                'area_id' => 150521,
                'name' => '科尔沁左翼中旗',
            ),
            271 => 
            array (
                'id' => 15125,
                'area_id' => 150522,
                'name' => '科尔沁左翼后旗',
            ),
            272 => 
            array (
                'id' => 15152,
                'area_id' => 150523,
                'name' => '开鲁县',
            ),
            273 => 
            array (
                'id' => 15179,
                'area_id' => 150524,
                'name' => '库伦旗',
            ),
            274 => 
            array (
                'id' => 15206,
                'area_id' => 150525,
                'name' => '奈曼旗',
            ),
            275 => 
            array (
                'id' => 15233,
                'area_id' => 150526,
                'name' => '扎鲁特旗',
            ),
            276 => 
            array (
                'id' => 15260,
                'area_id' => 150581,
                'name' => '霍林郭勒市',
            ),
            277 => 
            array (
                'id' => 15278,
                'area_id' => 150602,
                'name' => '东胜区',
            ),
            278 => 
            array (
                'id' => 15305,
                'area_id' => 150621,
                'name' => '达拉特旗',
            ),
            279 => 
            array (
                'id' => 15332,
                'area_id' => 150622,
                'name' => '准格尔旗',
            ),
            280 => 
            array (
                'id' => 15359,
                'area_id' => 150623,
                'name' => '鄂托克前旗',
            ),
            281 => 
            array (
                'id' => 15386,
                'area_id' => 150624,
                'name' => '鄂托克旗',
            ),
            282 => 
            array (
                'id' => 15413,
                'area_id' => 150625,
                'name' => '杭锦旗',
            ),
            283 => 
            array (
                'id' => 15440,
                'area_id' => 150626,
                'name' => '乌审旗',
            ),
            284 => 
            array (
                'id' => 15467,
                'area_id' => 150627,
                'name' => '伊金霍洛旗',
            ),
            285 => 
            array (
                'id' => 15486,
                'area_id' => 150701,
                'name' => '市辖区',
            ),
            286 => 
            array (
                'id' => 15513,
                'area_id' => 150702,
                'name' => '海拉尔区',
            ),
            287 => 
            array (
                'id' => 15540,
                'area_id' => 150721,
                'name' => '阿荣旗',
            ),
            288 => 
            array (
                'id' => 15567,
                'area_id' => 150722,
                'name' => '莫力达瓦达斡尔族自治旗',
            ),
            289 => 
            array (
                'id' => 15594,
                'area_id' => 150723,
                'name' => '鄂伦春自治旗',
            ),
            290 => 
            array (
                'id' => 15621,
                'area_id' => 150724,
                'name' => '鄂温克族自治旗',
            ),
            291 => 
            array (
                'id' => 15648,
                'area_id' => 150725,
                'name' => '陈巴尔虎旗',
            ),
            292 => 
            array (
                'id' => 15675,
                'area_id' => 150726,
                'name' => '新巴尔虎左旗',
            ),
            293 => 
            array (
                'id' => 15702,
                'area_id' => 150727,
                'name' => '新巴尔虎右旗',
            ),
            294 => 
            array (
                'id' => 15729,
                'area_id' => 150781,
                'name' => '满洲里市',
            ),
            295 => 
            array (
                'id' => 15756,
                'area_id' => 150782,
                'name' => '牙克石市',
            ),
            296 => 
            array (
                'id' => 15783,
                'area_id' => 150783,
                'name' => '扎兰屯市',
            ),
            297 => 
            array (
                'id' => 15810,
                'area_id' => 150784,
                'name' => '额尔古纳市',
            ),
            298 => 
            array (
                'id' => 15837,
                'area_id' => 150785,
                'name' => '根河市',
            ),
            299 => 
            array (
                'id' => 15850,
                'area_id' => 150801,
                'name' => '市辖区',
            ),
            300 => 
            array (
                'id' => 15877,
                'area_id' => 150802,
                'name' => '临河区',
            ),
            301 => 
            array (
                'id' => 15904,
                'area_id' => 150821,
                'name' => '五原县',
            ),
            302 => 
            array (
                'id' => 15931,
                'area_id' => 150822,
                'name' => '磴口县',
            ),
            303 => 
            array (
                'id' => 15958,
                'area_id' => 150823,
                'name' => '乌拉特前旗',
            ),
            304 => 
            array (
                'id' => 15985,
                'area_id' => 150824,
                'name' => '乌拉特中旗',
            ),
            305 => 
            array (
                'id' => 16012,
                'area_id' => 150825,
                'name' => '乌拉特后旗',
            ),
            306 => 
            array (
                'id' => 16039,
                'area_id' => 150826,
                'name' => '杭锦后旗',
            ),
            307 => 
            array (
                'id' => 16058,
                'area_id' => 150901,
                'name' => '市辖区',
            ),
            308 => 
            array (
                'id' => 16085,
                'area_id' => 150902,
                'name' => '集宁区',
            ),
            309 => 
            array (
                'id' => 16112,
                'area_id' => 150921,
                'name' => '卓资县',
            ),
            310 => 
            array (
                'id' => 16139,
                'area_id' => 150922,
                'name' => '化德县',
            ),
            311 => 
            array (
                'id' => 16166,
                'area_id' => 150923,
                'name' => '商都县',
            ),
            312 => 
            array (
                'id' => 16193,
                'area_id' => 150924,
                'name' => '兴和县',
            ),
            313 => 
            array (
                'id' => 16220,
                'area_id' => 150925,
                'name' => '凉城县',
            ),
            314 => 
            array (
                'id' => 16247,
                'area_id' => 150926,
                'name' => '察哈尔右翼前旗',
            ),
            315 => 
            array (
                'id' => 16274,
                'area_id' => 150927,
                'name' => '察哈尔右翼中旗',
            ),
            316 => 
            array (
                'id' => 16301,
                'area_id' => 150928,
                'name' => '察哈尔右翼后旗',
            ),
            317 => 
            array (
                'id' => 16328,
                'area_id' => 150929,
                'name' => '四子王旗',
            ),
            318 => 
            array (
                'id' => 16355,
                'area_id' => 150981,
                'name' => '丰镇市',
            ),
            319 => 
            array (
                'id' => 16370,
                'area_id' => 152201,
                'name' => '乌兰浩特市',
            ),
            320 => 
            array (
                'id' => 16397,
                'area_id' => 152202,
                'name' => '阿尔山市',
            ),
            321 => 
            array (
                'id' => 16424,
                'area_id' => 152221,
                'name' => '科尔沁右翼前旗',
            ),
            322 => 
            array (
                'id' => 16451,
                'area_id' => 152222,
                'name' => '科尔沁右翼中旗',
            ),
            323 => 
            array (
                'id' => 16478,
                'area_id' => 152223,
                'name' => '扎赉特旗',
            ),
            324 => 
            array (
                'id' => 16505,
                'area_id' => 152224,
                'name' => '突泉县',
            ),
            325 => 
            array (
                'id' => 16526,
                'area_id' => 152501,
                'name' => '二连浩特市',
            ),
            326 => 
            array (
                'id' => 16553,
                'area_id' => 152502,
                'name' => '锡林浩特市',
            ),
            327 => 
            array (
                'id' => 16580,
                'area_id' => 152522,
                'name' => '阿巴嘎旗',
            ),
            328 => 
            array (
                'id' => 16607,
                'area_id' => 152523,
                'name' => '苏尼特左旗',
            ),
            329 => 
            array (
                'id' => 16634,
                'area_id' => 152524,
                'name' => '苏尼特右旗',
            ),
            330 => 
            array (
                'id' => 16661,
                'area_id' => 152525,
                'name' => '东乌珠穆沁旗',
            ),
            331 => 
            array (
                'id' => 16688,
                'area_id' => 152526,
                'name' => '西乌珠穆沁旗',
            ),
            332 => 
            array (
                'id' => 16715,
                'area_id' => 152527,
                'name' => '太仆寺旗',
            ),
            333 => 
            array (
                'id' => 16742,
                'area_id' => 152528,
                'name' => '镶黄旗',
            ),
            334 => 
            array (
                'id' => 16769,
                'area_id' => 152529,
                'name' => '正镶白旗',
            ),
            335 => 
            array (
                'id' => 16796,
                'area_id' => 152530,
                'name' => '正蓝旗',
            ),
            336 => 
            array (
                'id' => 16823,
                'area_id' => 152531,
                'name' => '多伦县',
            ),
            337 => 
            array (
                'id' => 16838,
                'area_id' => 152921,
                'name' => '阿拉善左旗',
            ),
            338 => 
            array (
                'id' => 16865,
                'area_id' => 152922,
                'name' => '阿拉善右旗',
            ),
            339 => 
            array (
                'id' => 16892,
                'area_id' => 152923,
                'name' => '额济纳旗',
            ),
            340 => 
            array (
                'id' => 16916,
                'area_id' => 210101,
                'name' => '市辖区',
            ),
            341 => 
            array (
                'id' => 16943,
                'area_id' => 210102,
                'name' => '和平区',
            ),
            342 => 
            array (
                'id' => 16970,
                'area_id' => 210103,
                'name' => '沈河区',
            ),
            343 => 
            array (
                'id' => 16997,
                'area_id' => 210104,
                'name' => '大东区',
            ),
            344 => 
            array (
                'id' => 17024,
                'area_id' => 210105,
                'name' => '皇姑区',
            ),
            345 => 
            array (
                'id' => 17051,
                'area_id' => 210106,
                'name' => '铁西区',
            ),
            346 => 
            array (
                'id' => 17078,
                'area_id' => 210111,
                'name' => '苏家屯区',
            ),
            347 => 
            array (
                'id' => 17105,
                'area_id' => 210112,
                'name' => '东陵区',
            ),
            348 => 
            array (
                'id' => 17132,
                'area_id' => 210113,
                'name' => '新城子区',
            ),
            349 => 
            array (
                'id' => 17159,
                'area_id' => 210114,
                'name' => '于洪区',
            ),
            350 => 
            array (
                'id' => 17186,
                'area_id' => 210122,
                'name' => '辽中县',
            ),
            351 => 
            array (
                'id' => 17213,
                'area_id' => 210123,
                'name' => '康平县',
            ),
            352 => 
            array (
                'id' => 17240,
                'area_id' => 210124,
                'name' => '法库县',
            ),
            353 => 
            array (
                'id' => 17267,
                'area_id' => 210181,
                'name' => '新民市',
            ),
            354 => 
            array (
                'id' => 17280,
                'area_id' => 210201,
                'name' => '市辖区',
            ),
            355 => 
            array (
                'id' => 17307,
                'area_id' => 210202,
                'name' => '中山区',
            ),
            356 => 
            array (
                'id' => 17334,
                'area_id' => 210203,
                'name' => '西岗区',
            ),
            357 => 
            array (
                'id' => 17361,
                'area_id' => 210204,
                'name' => '沙河口区',
            ),
            358 => 
            array (
                'id' => 17388,
                'area_id' => 210211,
                'name' => '甘井子区',
            ),
            359 => 
            array (
                'id' => 17415,
                'area_id' => 210212,
                'name' => '旅顺口区',
            ),
            360 => 
            array (
                'id' => 17442,
                'area_id' => 210213,
                'name' => '金州区',
            ),
            361 => 
            array (
                'id' => 17469,
                'area_id' => 210224,
                'name' => '长海县',
            ),
            362 => 
            array (
                'id' => 17496,
                'area_id' => 210281,
                'name' => '瓦房店市',
            ),
            363 => 
            array (
                'id' => 17523,
                'area_id' => 210282,
                'name' => '普兰店市',
            ),
            364 => 
            array (
                'id' => 17550,
                'area_id' => 210283,
                'name' => '庄河市',
            ),
            365 => 
            array (
                'id' => 17566,
                'area_id' => 210301,
                'name' => '市辖区',
            ),
            366 => 
            array (
                'id' => 17593,
                'area_id' => 210302,
                'name' => '铁东区',
            ),
            367 => 
            array (
                'id' => 17620,
                'area_id' => 210303,
                'name' => '铁西区',
            ),
            368 => 
            array (
                'id' => 17647,
                'area_id' => 210304,
                'name' => '立山区',
            ),
            369 => 
            array (
                'id' => 17674,
                'area_id' => 210311,
                'name' => '千山区',
            ),
            370 => 
            array (
                'id' => 17701,
                'area_id' => 210321,
                'name' => '台安县',
            ),
            371 => 
            array (
                'id' => 17728,
                'area_id' => 210323,
                'name' => '岫岩满族自治县',
            ),
            372 => 
            array (
                'id' => 17755,
                'area_id' => 210381,
                'name' => '海城市',
            ),
            373 => 
            array (
                'id' => 17774,
                'area_id' => 210401,
                'name' => '市辖区',
            ),
            374 => 
            array (
                'id' => 17801,
                'area_id' => 210402,
                'name' => '新抚区',
            ),
            375 => 
            array (
                'id' => 17828,
                'area_id' => 210403,
                'name' => '东洲区',
            ),
            376 => 
            array (
                'id' => 17855,
                'area_id' => 210404,
                'name' => '望花区',
            ),
            377 => 
            array (
                'id' => 17882,
                'area_id' => 210411,
                'name' => '顺城区',
            ),
            378 => 
            array (
                'id' => 17909,
                'area_id' => 210421,
                'name' => '抚顺县',
            ),
            379 => 
            array (
                'id' => 17936,
                'area_id' => 210422,
                'name' => '新宾满族自治县',
            ),
            380 => 
            array (
                'id' => 17963,
                'area_id' => 210423,
                'name' => '清原满族自治县',
            ),
            381 => 
            array (
                'id' => 17982,
                'area_id' => 210501,
                'name' => '市辖区',
            ),
            382 => 
            array (
                'id' => 18009,
                'area_id' => 210502,
                'name' => '平山区',
            ),
            383 => 
            array (
                'id' => 18036,
                'area_id' => 210503,
                'name' => '溪湖区',
            ),
            384 => 
            array (
                'id' => 18063,
                'area_id' => 210504,
                'name' => '明山区',
            ),
            385 => 
            array (
                'id' => 18090,
                'area_id' => 210505,
                'name' => '南芬区',
            ),
            386 => 
            array (
                'id' => 18117,
                'area_id' => 210521,
                'name' => '本溪满族自治县',
            ),
            387 => 
            array (
                'id' => 18144,
                'area_id' => 210522,
                'name' => '桓仁满族自治县',
            ),
            388 => 
            array (
                'id' => 18164,
                'area_id' => 210601,
                'name' => '市辖区',
            ),
            389 => 
            array (
                'id' => 18191,
                'area_id' => 210602,
                'name' => '元宝区',
            ),
            390 => 
            array (
                'id' => 18218,
                'area_id' => 210603,
                'name' => '振兴区',
            ),
            391 => 
            array (
                'id' => 18245,
                'area_id' => 210604,
                'name' => '振安区',
            ),
            392 => 
            array (
                'id' => 18272,
                'area_id' => 210624,
                'name' => '宽甸满族自治县',
            ),
            393 => 
            array (
                'id' => 18299,
                'area_id' => 210681,
                'name' => '东港市',
            ),
            394 => 
            array (
                'id' => 18326,
                'area_id' => 210682,
                'name' => '凤城市',
            ),
            395 => 
            array (
                'id' => 18346,
                'area_id' => 210701,
                'name' => '市辖区',
            ),
            396 => 
            array (
                'id' => 18373,
                'area_id' => 210702,
                'name' => '古塔区',
            ),
            397 => 
            array (
                'id' => 18400,
                'area_id' => 210703,
                'name' => '凌河区',
            ),
            398 => 
            array (
                'id' => 18427,
                'area_id' => 210711,
                'name' => '太和区',
            ),
            399 => 
            array (
                'id' => 18454,
                'area_id' => 210726,
                'name' => '黑山县',
            ),
            400 => 
            array (
                'id' => 18481,
                'area_id' => 210727,
                'name' => '义　县',
            ),
            401 => 
            array (
                'id' => 18508,
                'area_id' => 210781,
                'name' => '凌海市',
            ),
            402 => 
            array (
                'id' => 18535,
                'area_id' => 210782,
                'name' => '北宁市',
            ),
            403 => 
            array (
                'id' => 18554,
                'area_id' => 210801,
                'name' => '市辖区',
            ),
            404 => 
            array (
                'id' => 18581,
                'area_id' => 210802,
                'name' => '站前区',
            ),
            405 => 
            array (
                'id' => 18608,
                'area_id' => 210803,
                'name' => '西市区',
            ),
            406 => 
            array (
                'id' => 18635,
                'area_id' => 210804,
                'name' => '鲅鱼圈区',
            ),
            407 => 
            array (
                'id' => 18662,
                'area_id' => 210811,
                'name' => '老边区',
            ),
            408 => 
            array (
                'id' => 18689,
                'area_id' => 210881,
                'name' => '盖州市',
            ),
            409 => 
            array (
                'id' => 18716,
                'area_id' => 210882,
                'name' => '大石桥市',
            ),
            410 => 
            array (
                'id' => 18736,
                'area_id' => 210901,
                'name' => '市辖区',
            ),
            411 => 
            array (
                'id' => 18763,
                'area_id' => 210902,
                'name' => '海州区',
            ),
            412 => 
            array (
                'id' => 18790,
                'area_id' => 210903,
                'name' => '新邱区',
            ),
            413 => 
            array (
                'id' => 18817,
                'area_id' => 210904,
                'name' => '太平区',
            ),
            414 => 
            array (
                'id' => 18844,
                'area_id' => 210905,
                'name' => '清河门区',
            ),
            415 => 
            array (
                'id' => 18871,
                'area_id' => 210911,
                'name' => '细河区',
            ),
            416 => 
            array (
                'id' => 18898,
                'area_id' => 210921,
                'name' => '阜新蒙古族自治县',
            ),
            417 => 
            array (
                'id' => 18925,
                'area_id' => 210922,
                'name' => '彰武县',
            ),
            418 => 
            array (
                'id' => 18944,
                'area_id' => 211001,
                'name' => '市辖区',
            ),
            419 => 
            array (
                'id' => 18971,
                'area_id' => 211002,
                'name' => '白塔区',
            ),
            420 => 
            array (
                'id' => 18998,
                'area_id' => 211003,
                'name' => '文圣区',
            ),
            421 => 
            array (
                'id' => 19025,
                'area_id' => 211004,
                'name' => '宏伟区',
            ),
            422 => 
            array (
                'id' => 19052,
                'area_id' => 211005,
                'name' => '弓长岭区',
            ),
            423 => 
            array (
                'id' => 19079,
                'area_id' => 211011,
                'name' => '太子河区',
            ),
            424 => 
            array (
                'id' => 19106,
                'area_id' => 211021,
                'name' => '辽阳县',
            ),
            425 => 
            array (
                'id' => 19133,
                'area_id' => 211081,
                'name' => '灯塔市',
            ),
            426 => 
            array (
                'id' => 19152,
                'area_id' => 211101,
                'name' => '市辖区',
            ),
            427 => 
            array (
                'id' => 19179,
                'area_id' => 211102,
                'name' => '双台子区',
            ),
            428 => 
            array (
                'id' => 19206,
                'area_id' => 211103,
                'name' => '兴隆台区',
            ),
            429 => 
            array (
                'id' => 19233,
                'area_id' => 211121,
                'name' => '大洼县',
            ),
            430 => 
            array (
                'id' => 19260,
                'area_id' => 211122,
                'name' => '盘山县',
            ),
            431 => 
            array (
                'id' => 19282,
                'area_id' => 211201,
                'name' => '市辖区',
            ),
            432 => 
            array (
                'id' => 19309,
                'area_id' => 211202,
                'name' => '银州区',
            ),
            433 => 
            array (
                'id' => 19336,
                'area_id' => 211204,
                'name' => '清河区',
            ),
            434 => 
            array (
                'id' => 19363,
                'area_id' => 211221,
                'name' => '铁岭县',
            ),
            435 => 
            array (
                'id' => 19390,
                'area_id' => 211223,
                'name' => '西丰县',
            ),
            436 => 
            array (
                'id' => 19417,
                'area_id' => 211224,
                'name' => '昌图县',
            ),
            437 => 
            array (
                'id' => 19444,
                'area_id' => 211281,
                'name' => '调兵山市',
            ),
            438 => 
            array (
                'id' => 19471,
                'area_id' => 211282,
                'name' => '开原市',
            ),
            439 => 
            array (
                'id' => 19490,
                'area_id' => 211301,
                'name' => '市辖区',
            ),
            440 => 
            array (
                'id' => 19517,
                'area_id' => 211302,
                'name' => '双塔区',
            ),
            441 => 
            array (
                'id' => 19544,
                'area_id' => 211303,
                'name' => '龙城区',
            ),
            442 => 
            array (
                'id' => 19571,
                'area_id' => 211321,
                'name' => '朝阳县',
            ),
            443 => 
            array (
                'id' => 19598,
                'area_id' => 211322,
                'name' => '建平县',
            ),
            444 => 
            array (
                'id' => 19625,
                'area_id' => 211324,
                'name' => '喀喇沁左翼蒙古族自治县',
            ),
            445 => 
            array (
                'id' => 19652,
                'area_id' => 211381,
                'name' => '北票市',
            ),
            446 => 
            array (
                'id' => 19679,
                'area_id' => 211382,
                'name' => '凌源市',
            ),
            447 => 
            array (
                'id' => 19698,
                'area_id' => 211401,
                'name' => '市辖区',
            ),
            448 => 
            array (
                'id' => 19725,
                'area_id' => 211402,
                'name' => '连山区',
            ),
            449 => 
            array (
                'id' => 19752,
                'area_id' => 211403,
                'name' => '龙港区',
            ),
            450 => 
            array (
                'id' => 19779,
                'area_id' => 211404,
                'name' => '南票区',
            ),
            451 => 
            array (
                'id' => 19806,
                'area_id' => 211421,
                'name' => '绥中县',
            ),
            452 => 
            array (
                'id' => 19833,
                'area_id' => 211422,
                'name' => '建昌县',
            ),
            453 => 
            array (
                'id' => 19860,
                'area_id' => 211481,
                'name' => '兴城市',
            ),
            454 => 
            array (
                'id' => 19880,
                'area_id' => 220101,
                'name' => '市辖区',
            ),
            455 => 
            array (
                'id' => 19907,
                'area_id' => 220102,
                'name' => '南关区',
            ),
            456 => 
            array (
                'id' => 19934,
                'area_id' => 220103,
                'name' => '宽城区',
            ),
            457 => 
            array (
                'id' => 19961,
                'area_id' => 220104,
                'name' => '朝阳区',
            ),
            458 => 
            array (
                'id' => 19988,
                'area_id' => 220105,
                'name' => '二道区',
            ),
            459 => 
            array (
                'id' => 20015,
                'area_id' => 220106,
                'name' => '绿园区',
            ),
            460 => 
            array (
                'id' => 20042,
                'area_id' => 220112,
                'name' => '双阳区',
            ),
            461 => 
            array (
                'id' => 20069,
                'area_id' => 220122,
                'name' => '农安县',
            ),
            462 => 
            array (
                'id' => 20096,
                'area_id' => 220181,
                'name' => '九台市',
            ),
            463 => 
            array (
                'id' => 20123,
                'area_id' => 220182,
                'name' => '榆树市',
            ),
            464 => 
            array (
                'id' => 20150,
                'area_id' => 220183,
                'name' => '德惠市',
            ),
            465 => 
            array (
                'id' => 20166,
                'area_id' => 220201,
                'name' => '市辖区',
            ),
            466 => 
            array (
                'id' => 20193,
                'area_id' => 220202,
                'name' => '昌邑区',
            ),
            467 => 
            array (
                'id' => 20220,
                'area_id' => 220203,
                'name' => '龙潭区',
            ),
            468 => 
            array (
                'id' => 20247,
                'area_id' => 220204,
                'name' => '船营区',
            ),
            469 => 
            array (
                'id' => 20274,
                'area_id' => 220211,
                'name' => '丰满区',
            ),
            470 => 
            array (
                'id' => 20301,
                'area_id' => 220221,
                'name' => '永吉县',
            ),
            471 => 
            array (
                'id' => 20328,
                'area_id' => 220281,
                'name' => '蛟河市',
            ),
            472 => 
            array (
                'id' => 20355,
                'area_id' => 220282,
                'name' => '桦甸市',
            ),
            473 => 
            array (
                'id' => 20382,
                'area_id' => 220283,
                'name' => '舒兰市',
            ),
            474 => 
            array (
                'id' => 20409,
                'area_id' => 220284,
                'name' => '磐石市',
            ),
            475 => 
            array (
                'id' => 20426,
                'area_id' => 220301,
                'name' => '市辖区',
            ),
            476 => 
            array (
                'id' => 20453,
                'area_id' => 220302,
                'name' => '铁西区',
            ),
            477 => 
            array (
                'id' => 20480,
                'area_id' => 220303,
                'name' => '铁东区',
            ),
            478 => 
            array (
                'id' => 20507,
                'area_id' => 220322,
                'name' => '梨树县',
            ),
            479 => 
            array (
                'id' => 20534,
                'area_id' => 220323,
                'name' => '伊通满族自治县',
            ),
            480 => 
            array (
                'id' => 20561,
                'area_id' => 220381,
                'name' => '公主岭市',
            ),
            481 => 
            array (
                'id' => 20588,
                'area_id' => 220382,
                'name' => '双辽市',
            ),
            482 => 
            array (
                'id' => 20608,
                'area_id' => 220401,
                'name' => '市辖区',
            ),
            483 => 
            array (
                'id' => 20635,
                'area_id' => 220402,
                'name' => '龙山区',
            ),
            484 => 
            array (
                'id' => 20662,
                'area_id' => 220403,
                'name' => '西安区',
            ),
            485 => 
            array (
                'id' => 20689,
                'area_id' => 220421,
                'name' => '东丰县',
            ),
            486 => 
            array (
                'id' => 20716,
                'area_id' => 220422,
                'name' => '东辽县',
            ),
            487 => 
            array (
                'id' => 20738,
                'area_id' => 220501,
                'name' => '市辖区',
            ),
            488 => 
            array (
                'id' => 20765,
                'area_id' => 220502,
                'name' => '东昌区',
            ),
            489 => 
            array (
                'id' => 20792,
                'area_id' => 220503,
                'name' => '二道江区',
            ),
            490 => 
            array (
                'id' => 20819,
                'area_id' => 220521,
                'name' => '通化县',
            ),
            491 => 
            array (
                'id' => 20846,
                'area_id' => 220523,
                'name' => '辉南县',
            ),
            492 => 
            array (
                'id' => 20873,
                'area_id' => 220524,
                'name' => '柳河县',
            ),
            493 => 
            array (
                'id' => 20900,
                'area_id' => 220581,
                'name' => '梅河口市',
            ),
            494 => 
            array (
                'id' => 20927,
                'area_id' => 220582,
                'name' => '集安市',
            ),
            495 => 
            array (
                'id' => 20946,
                'area_id' => 220601,
                'name' => '市辖区',
            ),
            496 => 
            array (
                'id' => 20973,
                'area_id' => 220602,
                'name' => '八道江区',
            ),
            497 => 
            array (
                'id' => 21000,
                'area_id' => 220621,
                'name' => '抚松县',
            ),
            498 => 
            array (
                'id' => 21027,
                'area_id' => 220622,
                'name' => '靖宇县',
            ),
            499 => 
            array (
                'id' => 21054,
                'area_id' => 220623,
                'name' => '长白朝鲜族自治县',
            ),
        ));
        \DB::table('areas')->insert(array (
            0 => 
            array (
                'id' => 21081,
                'area_id' => 220625,
                'name' => '江源县',
            ),
            1 => 
            array (
                'id' => 21108,
                'area_id' => 220681,
                'name' => '临江市',
            ),
            2 => 
            array (
                'id' => 21128,
                'area_id' => 220701,
                'name' => '市辖区',
            ),
            3 => 
            array (
                'id' => 21155,
                'area_id' => 220702,
                'name' => '宁江区',
            ),
            4 => 
            array (
                'id' => 21182,
                'area_id' => 220721,
                'name' => '前郭尔罗斯蒙古族自治县',
            ),
            5 => 
            array (
                'id' => 21209,
                'area_id' => 220722,
                'name' => '长岭县',
            ),
            6 => 
            array (
                'id' => 21236,
                'area_id' => 220723,
                'name' => '乾安县',
            ),
            7 => 
            array (
                'id' => 21263,
                'area_id' => 220724,
                'name' => '扶余县',
            ),
            8 => 
            array (
                'id' => 21284,
                'area_id' => 220801,
                'name' => '市辖区',
            ),
            9 => 
            array (
                'id' => 21311,
                'area_id' => 220802,
                'name' => '洮北区',
            ),
            10 => 
            array (
                'id' => 21338,
                'area_id' => 220821,
                'name' => '镇赉县',
            ),
            11 => 
            array (
                'id' => 21365,
                'area_id' => 220822,
                'name' => '通榆县',
            ),
            12 => 
            array (
                'id' => 21392,
                'area_id' => 220881,
                'name' => '洮南市',
            ),
            13 => 
            array (
                'id' => 21419,
                'area_id' => 220882,
                'name' => '大安市',
            ),
            14 => 
            array (
                'id' => 21440,
                'area_id' => 222401,
                'name' => '延吉市',
            ),
            15 => 
            array (
                'id' => 21467,
                'area_id' => 222402,
                'name' => '图们市',
            ),
            16 => 
            array (
                'id' => 21494,
                'area_id' => 222403,
                'name' => '敦化市',
            ),
            17 => 
            array (
                'id' => 21521,
                'area_id' => 222404,
                'name' => '珲春市',
            ),
            18 => 
            array (
                'id' => 21548,
                'area_id' => 222405,
                'name' => '龙井市',
            ),
            19 => 
            array (
                'id' => 21575,
                'area_id' => 222406,
                'name' => '和龙市',
            ),
            20 => 
            array (
                'id' => 21602,
                'area_id' => 222424,
                'name' => '汪清县',
            ),
            21 => 
            array (
                'id' => 21629,
                'area_id' => 222426,
                'name' => '安图县',
            ),
            22 => 
            array (
                'id' => 21648,
                'area_id' => 230101,
                'name' => '市辖区',
            ),
            23 => 
            array (
                'id' => 21675,
                'area_id' => 230102,
                'name' => '道里区',
            ),
            24 => 
            array (
                'id' => 21702,
                'area_id' => 230103,
                'name' => '南岗区',
            ),
            25 => 
            array (
                'id' => 21729,
                'area_id' => 230104,
                'name' => '道外区',
            ),
            26 => 
            array (
                'id' => 21756,
                'area_id' => 230106,
                'name' => '香坊区',
            ),
            27 => 
            array (
                'id' => 21783,
                'area_id' => 230107,
                'name' => '动力区',
            ),
            28 => 
            array (
                'id' => 21810,
                'area_id' => 230108,
                'name' => '平房区',
            ),
            29 => 
            array (
                'id' => 21837,
                'area_id' => 230109,
                'name' => '松北区',
            ),
            30 => 
            array (
                'id' => 21864,
                'area_id' => 230111,
                'name' => '呼兰区',
            ),
            31 => 
            array (
                'id' => 21891,
                'area_id' => 230123,
                'name' => '依兰县',
            ),
            32 => 
            array (
                'id' => 21918,
                'area_id' => 230124,
                'name' => '方正县',
            ),
            33 => 
            array (
                'id' => 21945,
                'area_id' => 230125,
                'name' => '宾　县',
            ),
            34 => 
            array (
                'id' => 21972,
                'area_id' => 230126,
                'name' => '巴彦县',
            ),
            35 => 
            array (
                'id' => 21999,
                'area_id' => 230127,
                'name' => '木兰县',
            ),
            36 => 
            array (
                'id' => 22026,
                'area_id' => 230128,
                'name' => '通河县',
            ),
            37 => 
            array (
                'id' => 22053,
                'area_id' => 230129,
                'name' => '延寿县',
            ),
            38 => 
            array (
                'id' => 22080,
                'area_id' => 230181,
                'name' => '阿城市',
            ),
            39 => 
            array (
                'id' => 22107,
                'area_id' => 230182,
                'name' => '双城市',
            ),
            40 => 
            array (
                'id' => 22134,
                'area_id' => 230183,
                'name' => '尚志市',
            ),
            41 => 
            array (
                'id' => 22161,
                'area_id' => 230184,
                'name' => '五常市',
            ),
            42 => 
            array (
                'id' => 22168,
                'area_id' => 230201,
                'name' => '市辖区',
            ),
            43 => 
            array (
                'id' => 22195,
                'area_id' => 230202,
                'name' => '龙沙区',
            ),
            44 => 
            array (
                'id' => 22222,
                'area_id' => 230203,
                'name' => '建华区',
            ),
            45 => 
            array (
                'id' => 22249,
                'area_id' => 230204,
                'name' => '铁锋区',
            ),
            46 => 
            array (
                'id' => 22276,
                'area_id' => 230205,
                'name' => '昂昂溪区',
            ),
            47 => 
            array (
                'id' => 22303,
                'area_id' => 230206,
                'name' => '富拉尔基区',
            ),
            48 => 
            array (
                'id' => 22330,
                'area_id' => 230207,
                'name' => '碾子山区',
            ),
            49 => 
            array (
                'id' => 22357,
                'area_id' => 230208,
                'name' => '梅里斯达斡尔族区',
            ),
            50 => 
            array (
                'id' => 22384,
                'area_id' => 230221,
                'name' => '龙江县',
            ),
            51 => 
            array (
                'id' => 22411,
                'area_id' => 230223,
                'name' => '依安县',
            ),
            52 => 
            array (
                'id' => 22438,
                'area_id' => 230224,
                'name' => '泰来县',
            ),
            53 => 
            array (
                'id' => 22465,
                'area_id' => 230225,
                'name' => '甘南县',
            ),
            54 => 
            array (
                'id' => 22492,
                'area_id' => 230227,
                'name' => '富裕县',
            ),
            55 => 
            array (
                'id' => 22519,
                'area_id' => 230229,
                'name' => '克山县',
            ),
            56 => 
            array (
                'id' => 22546,
                'area_id' => 230230,
                'name' => '克东县',
            ),
            57 => 
            array (
                'id' => 22573,
                'area_id' => 230231,
                'name' => '拜泉县',
            ),
            58 => 
            array (
                'id' => 22600,
                'area_id' => 230281,
                'name' => '讷河市',
            ),
            59 => 
            array (
                'id' => 22610,
                'area_id' => 230301,
                'name' => '市辖区',
            ),
            60 => 
            array (
                'id' => 22637,
                'area_id' => 230302,
                'name' => '鸡冠区',
            ),
            61 => 
            array (
                'id' => 22664,
                'area_id' => 230303,
                'name' => '恒山区',
            ),
            62 => 
            array (
                'id' => 22691,
                'area_id' => 230304,
                'name' => '滴道区',
            ),
            63 => 
            array (
                'id' => 22718,
                'area_id' => 230305,
                'name' => '梨树区',
            ),
            64 => 
            array (
                'id' => 22745,
                'area_id' => 230306,
                'name' => '城子河区',
            ),
            65 => 
            array (
                'id' => 22772,
                'area_id' => 230307,
                'name' => '麻山区',
            ),
            66 => 
            array (
                'id' => 22799,
                'area_id' => 230321,
                'name' => '鸡东县',
            ),
            67 => 
            array (
                'id' => 22826,
                'area_id' => 230381,
                'name' => '虎林市',
            ),
            68 => 
            array (
                'id' => 22853,
                'area_id' => 230382,
                'name' => '密山市',
            ),
            69 => 
            array (
                'id' => 22870,
                'area_id' => 230401,
                'name' => '市辖区',
            ),
            70 => 
            array (
                'id' => 22897,
                'area_id' => 230402,
                'name' => '向阳区',
            ),
            71 => 
            array (
                'id' => 22924,
                'area_id' => 230403,
                'name' => '工农区',
            ),
            72 => 
            array (
                'id' => 22951,
                'area_id' => 230404,
                'name' => '南山区',
            ),
            73 => 
            array (
                'id' => 22978,
                'area_id' => 230405,
                'name' => '兴安区',
            ),
            74 => 
            array (
                'id' => 23005,
                'area_id' => 230406,
                'name' => '东山区',
            ),
            75 => 
            array (
                'id' => 23032,
                'area_id' => 230407,
                'name' => '兴山区',
            ),
            76 => 
            array (
                'id' => 23059,
                'area_id' => 230421,
                'name' => '萝北县',
            ),
            77 => 
            array (
                'id' => 23086,
                'area_id' => 230422,
                'name' => '绥滨县',
            ),
            78 => 
            array (
                'id' => 23104,
                'area_id' => 230501,
                'name' => '市辖区',
            ),
            79 => 
            array (
                'id' => 23131,
                'area_id' => 230502,
                'name' => '尖山区',
            ),
            80 => 
            array (
                'id' => 23158,
                'area_id' => 230503,
                'name' => '岭东区',
            ),
            81 => 
            array (
                'id' => 23185,
                'area_id' => 230505,
                'name' => '四方台区',
            ),
            82 => 
            array (
                'id' => 23212,
                'area_id' => 230506,
                'name' => '宝山区',
            ),
            83 => 
            array (
                'id' => 23239,
                'area_id' => 230521,
                'name' => '集贤县',
            ),
            84 => 
            array (
                'id' => 23266,
                'area_id' => 230522,
                'name' => '友谊县',
            ),
            85 => 
            array (
                'id' => 23293,
                'area_id' => 230523,
                'name' => '宝清县',
            ),
            86 => 
            array (
                'id' => 23320,
                'area_id' => 230524,
                'name' => '饶河县',
            ),
            87 => 
            array (
                'id' => 23338,
                'area_id' => 230601,
                'name' => '市辖区',
            ),
            88 => 
            array (
                'id' => 23365,
                'area_id' => 230602,
                'name' => '萨尔图区',
            ),
            89 => 
            array (
                'id' => 23392,
                'area_id' => 230603,
                'name' => '龙凤区',
            ),
            90 => 
            array (
                'id' => 23419,
                'area_id' => 230604,
                'name' => '让胡路区',
            ),
            91 => 
            array (
                'id' => 23446,
                'area_id' => 230605,
                'name' => '红岗区',
            ),
            92 => 
            array (
                'id' => 23473,
                'area_id' => 230606,
                'name' => '大同区',
            ),
            93 => 
            array (
                'id' => 23500,
                'area_id' => 230621,
                'name' => '肇州县',
            ),
            94 => 
            array (
                'id' => 23527,
                'area_id' => 230622,
                'name' => '肇源县',
            ),
            95 => 
            array (
                'id' => 23554,
                'area_id' => 230623,
                'name' => '林甸县',
            ),
            96 => 
            array (
                'id' => 23581,
                'area_id' => 230624,
                'name' => '杜尔伯特蒙古族自治县',
            ),
            97 => 
            array (
                'id' => 23598,
                'area_id' => 230701,
                'name' => '市辖区',
            ),
            98 => 
            array (
                'id' => 23625,
                'area_id' => 230702,
                'name' => '伊春区',
            ),
            99 => 
            array (
                'id' => 23652,
                'area_id' => 230703,
                'name' => '南岔区',
            ),
            100 => 
            array (
                'id' => 23679,
                'area_id' => 230704,
                'name' => '友好区',
            ),
            101 => 
            array (
                'id' => 23706,
                'area_id' => 230705,
                'name' => '西林区',
            ),
            102 => 
            array (
                'id' => 23733,
                'area_id' => 230706,
                'name' => '翠峦区',
            ),
            103 => 
            array (
                'id' => 23760,
                'area_id' => 230707,
                'name' => '新青区',
            ),
            104 => 
            array (
                'id' => 23787,
                'area_id' => 230708,
                'name' => '美溪区',
            ),
            105 => 
            array (
                'id' => 23814,
                'area_id' => 230709,
                'name' => '金山屯区',
            ),
            106 => 
            array (
                'id' => 23841,
                'area_id' => 230710,
                'name' => '五营区',
            ),
            107 => 
            array (
                'id' => 23868,
                'area_id' => 230711,
                'name' => '乌马河区',
            ),
            108 => 
            array (
                'id' => 23895,
                'area_id' => 230712,
                'name' => '汤旺河区',
            ),
            109 => 
            array (
                'id' => 23922,
                'area_id' => 230713,
                'name' => '带岭区',
            ),
            110 => 
            array (
                'id' => 23949,
                'area_id' => 230714,
                'name' => '乌伊岭区',
            ),
            111 => 
            array (
                'id' => 23976,
                'area_id' => 230715,
                'name' => '红星区',
            ),
            112 => 
            array (
                'id' => 24003,
                'area_id' => 230716,
                'name' => '上甘岭区',
            ),
            113 => 
            array (
                'id' => 24030,
                'area_id' => 230722,
                'name' => '嘉荫县',
            ),
            114 => 
            array (
                'id' => 24057,
                'area_id' => 230781,
                'name' => '铁力市',
            ),
            115 => 
            array (
                'id' => 24066,
                'area_id' => 230801,
                'name' => '市辖区',
            ),
            116 => 
            array (
                'id' => 24093,
                'area_id' => 230802,
                'name' => '永红区',
            ),
            117 => 
            array (
                'id' => 24120,
                'area_id' => 230803,
                'name' => '向阳区',
            ),
            118 => 
            array (
                'id' => 24147,
                'area_id' => 230804,
                'name' => '前进区',
            ),
            119 => 
            array (
                'id' => 24174,
                'area_id' => 230805,
                'name' => '东风区',
            ),
            120 => 
            array (
                'id' => 24201,
                'area_id' => 230811,
                'name' => '郊　区',
            ),
            121 => 
            array (
                'id' => 24228,
                'area_id' => 230822,
                'name' => '桦南县',
            ),
            122 => 
            array (
                'id' => 24255,
                'area_id' => 230826,
                'name' => '桦川县',
            ),
            123 => 
            array (
                'id' => 24282,
                'area_id' => 230828,
                'name' => '汤原县',
            ),
            124 => 
            array (
                'id' => 24309,
                'area_id' => 230833,
                'name' => '抚远县',
            ),
            125 => 
            array (
                'id' => 24336,
                'area_id' => 230881,
                'name' => '同江市',
            ),
            126 => 
            array (
                'id' => 24363,
                'area_id' => 230882,
                'name' => '富锦市',
            ),
            127 => 
            array (
                'id' => 24378,
                'area_id' => 230901,
                'name' => '市辖区',
            ),
            128 => 
            array (
                'id' => 24405,
                'area_id' => 230902,
                'name' => '新兴区',
            ),
            129 => 
            array (
                'id' => 24432,
                'area_id' => 230903,
                'name' => '桃山区',
            ),
            130 => 
            array (
                'id' => 24459,
                'area_id' => 230904,
                'name' => '茄子河区',
            ),
            131 => 
            array (
                'id' => 24486,
                'area_id' => 230921,
                'name' => '勃利县',
            ),
            132 => 
            array (
                'id' => 24508,
                'area_id' => 231001,
                'name' => '市辖区',
            ),
            133 => 
            array (
                'id' => 24535,
                'area_id' => 231002,
                'name' => '东安区',
            ),
            134 => 
            array (
                'id' => 24562,
                'area_id' => 231003,
                'name' => '阳明区',
            ),
            135 => 
            array (
                'id' => 24589,
                'area_id' => 231004,
                'name' => '爱民区',
            ),
            136 => 
            array (
                'id' => 24616,
                'area_id' => 231005,
                'name' => '西安区',
            ),
            137 => 
            array (
                'id' => 24643,
                'area_id' => 231024,
                'name' => '东宁县',
            ),
            138 => 
            array (
                'id' => 24670,
                'area_id' => 231025,
                'name' => '林口县',
            ),
            139 => 
            array (
                'id' => 24697,
                'area_id' => 231081,
                'name' => '绥芬河市',
            ),
            140 => 
            array (
                'id' => 24724,
                'area_id' => 231083,
                'name' => '海林市',
            ),
            141 => 
            array (
                'id' => 24751,
                'area_id' => 231084,
                'name' => '宁安市',
            ),
            142 => 
            array (
                'id' => 24778,
                'area_id' => 231085,
                'name' => '穆棱市',
            ),
            143 => 
            array (
                'id' => 24794,
                'area_id' => 231101,
                'name' => '市辖区',
            ),
            144 => 
            array (
                'id' => 24821,
                'area_id' => 231102,
                'name' => '爱辉区',
            ),
            145 => 
            array (
                'id' => 24848,
                'area_id' => 231121,
                'name' => '嫩江县',
            ),
            146 => 
            array (
                'id' => 24875,
                'area_id' => 231123,
                'name' => '逊克县',
            ),
            147 => 
            array (
                'id' => 24902,
                'area_id' => 231124,
                'name' => '孙吴县',
            ),
            148 => 
            array (
                'id' => 24929,
                'area_id' => 231181,
                'name' => '北安市',
            ),
            149 => 
            array (
                'id' => 24956,
                'area_id' => 231182,
                'name' => '五大连池市',
            ),
            150 => 
            array (
                'id' => 24976,
                'area_id' => 231201,
                'name' => '市辖区',
            ),
            151 => 
            array (
                'id' => 25003,
                'area_id' => 231202,
                'name' => '北林区',
            ),
            152 => 
            array (
                'id' => 25030,
                'area_id' => 231221,
                'name' => '望奎县',
            ),
            153 => 
            array (
                'id' => 25057,
                'area_id' => 231222,
                'name' => '兰西县',
            ),
            154 => 
            array (
                'id' => 25084,
                'area_id' => 231223,
                'name' => '青冈县',
            ),
            155 => 
            array (
                'id' => 25111,
                'area_id' => 231224,
                'name' => '庆安县',
            ),
            156 => 
            array (
                'id' => 25138,
                'area_id' => 231225,
                'name' => '明水县',
            ),
            157 => 
            array (
                'id' => 25165,
                'area_id' => 231226,
                'name' => '绥棱县',
            ),
            158 => 
            array (
                'id' => 25192,
                'area_id' => 231281,
                'name' => '安达市',
            ),
            159 => 
            array (
                'id' => 25219,
                'area_id' => 231282,
                'name' => '肇东市',
            ),
            160 => 
            array (
                'id' => 25246,
                'area_id' => 231283,
                'name' => '海伦市',
            ),
            161 => 
            array (
                'id' => 25262,
                'area_id' => 232721,
                'name' => '呼玛县',
            ),
            162 => 
            array (
                'id' => 25289,
                'area_id' => 232722,
                'name' => '塔河县',
            ),
            163 => 
            array (
                'id' => 25316,
                'area_id' => 232723,
                'name' => '漠河县',
            ),
            164 => 
            array (
                'id' => 25340,
                'area_id' => 310101,
                'name' => '黄浦区',
            ),
            165 => 
            array (
                'id' => 25367,
                'area_id' => 310103,
                'name' => '卢湾区',
            ),
            166 => 
            array (
                'id' => 25394,
                'area_id' => 310104,
                'name' => '徐汇区',
            ),
            167 => 
            array (
                'id' => 25421,
                'area_id' => 310105,
                'name' => '长宁区',
            ),
            168 => 
            array (
                'id' => 25448,
                'area_id' => 310106,
                'name' => '静安区',
            ),
            169 => 
            array (
                'id' => 25475,
                'area_id' => 310107,
                'name' => '普陀区',
            ),
            170 => 
            array (
                'id' => 25502,
                'area_id' => 310108,
                'name' => '闸北区',
            ),
            171 => 
            array (
                'id' => 25529,
                'area_id' => 310109,
                'name' => '虹口区',
            ),
            172 => 
            array (
                'id' => 25556,
                'area_id' => 310110,
                'name' => '杨浦区',
            ),
            173 => 
            array (
                'id' => 25583,
                'area_id' => 310112,
                'name' => '闵行区',
            ),
            174 => 
            array (
                'id' => 25610,
                'area_id' => 310113,
                'name' => '宝山区',
            ),
            175 => 
            array (
                'id' => 25637,
                'area_id' => 310114,
                'name' => '嘉定区',
            ),
            176 => 
            array (
                'id' => 25664,
                'area_id' => 310115,
                'name' => '浦东新区',
            ),
            177 => 
            array (
                'id' => 25691,
                'area_id' => 310116,
                'name' => '金山区',
            ),
            178 => 
            array (
                'id' => 25718,
                'area_id' => 310117,
                'name' => '松江区',
            ),
            179 => 
            array (
                'id' => 25745,
                'area_id' => 310118,
                'name' => '青浦区',
            ),
            180 => 
            array (
                'id' => 25772,
                'area_id' => 310119,
                'name' => '南汇区',
            ),
            181 => 
            array (
                'id' => 25799,
                'area_id' => 310120,
                'name' => '奉贤区',
            ),
            182 => 
            array (
                'id' => 25808,
                'area_id' => 310230,
                'name' => '崇明县',
            ),
            183 => 
            array (
                'id' => 25834,
                'area_id' => 320101,
                'name' => '市辖区',
            ),
            184 => 
            array (
                'id' => 25861,
                'area_id' => 320102,
                'name' => '玄武区',
            ),
            185 => 
            array (
                'id' => 25888,
                'area_id' => 320103,
                'name' => '白下区',
            ),
            186 => 
            array (
                'id' => 25915,
                'area_id' => 320104,
                'name' => '秦淮区',
            ),
            187 => 
            array (
                'id' => 25942,
                'area_id' => 320105,
                'name' => '建邺区',
            ),
            188 => 
            array (
                'id' => 25969,
                'area_id' => 320106,
                'name' => '鼓楼区',
            ),
            189 => 
            array (
                'id' => 25996,
                'area_id' => 320107,
                'name' => '下关区',
            ),
            190 => 
            array (
                'id' => 26023,
                'area_id' => 320111,
                'name' => '浦口区',
            ),
            191 => 
            array (
                'id' => 26050,
                'area_id' => 320113,
                'name' => '栖霞区',
            ),
            192 => 
            array (
                'id' => 26077,
                'area_id' => 320114,
                'name' => '雨花台区',
            ),
            193 => 
            array (
                'id' => 26104,
                'area_id' => 320115,
                'name' => '江宁区',
            ),
            194 => 
            array (
                'id' => 26131,
                'area_id' => 320116,
                'name' => '六合区',
            ),
            195 => 
            array (
                'id' => 26158,
                'area_id' => 320124,
                'name' => '溧水县',
            ),
            196 => 
            array (
                'id' => 26185,
                'area_id' => 320125,
                'name' => '高淳县',
            ),
            197 => 
            array (
                'id' => 26198,
                'area_id' => 320201,
                'name' => '市辖区',
            ),
            198 => 
            array (
                'id' => 26225,
                'area_id' => 320202,
                'name' => '崇安区',
            ),
            199 => 
            array (
                'id' => 26252,
                'area_id' => 320203,
                'name' => '南长区',
            ),
            200 => 
            array (
                'id' => 26279,
                'area_id' => 320204,
                'name' => '北塘区',
            ),
            201 => 
            array (
                'id' => 26306,
                'area_id' => 320205,
                'name' => '锡山区',
            ),
            202 => 
            array (
                'id' => 26333,
                'area_id' => 320206,
                'name' => '惠山区',
            ),
            203 => 
            array (
                'id' => 26360,
                'area_id' => 320211,
                'name' => '滨湖区',
            ),
            204 => 
            array (
                'id' => 26387,
                'area_id' => 320281,
                'name' => '江阴市',
            ),
            205 => 
            array (
                'id' => 26414,
                'area_id' => 320282,
                'name' => '宜兴市',
            ),
            206 => 
            array (
                'id' => 26432,
                'area_id' => 320301,
                'name' => '市辖区',
            ),
            207 => 
            array (
                'id' => 26459,
                'area_id' => 320302,
                'name' => '鼓楼区',
            ),
            208 => 
            array (
                'id' => 26486,
                'area_id' => 320303,
                'name' => '云龙区',
            ),
            209 => 
            array (
                'id' => 26513,
                'area_id' => 320304,
                'name' => '九里区',
            ),
            210 => 
            array (
                'id' => 26540,
                'area_id' => 320305,
                'name' => '贾汪区',
            ),
            211 => 
            array (
                'id' => 26567,
                'area_id' => 320311,
                'name' => '泉山区',
            ),
            212 => 
            array (
                'id' => 26594,
                'area_id' => 320321,
                'name' => '丰　县',
            ),
            213 => 
            array (
                'id' => 26621,
                'area_id' => 320322,
                'name' => '沛　县',
            ),
            214 => 
            array (
                'id' => 26648,
                'area_id' => 320323,
                'name' => '铜山县',
            ),
            215 => 
            array (
                'id' => 26675,
                'area_id' => 320324,
                'name' => '睢宁县',
            ),
            216 => 
            array (
                'id' => 26702,
                'area_id' => 320381,
                'name' => '新沂市',
            ),
            217 => 
            array (
                'id' => 26729,
                'area_id' => 320382,
                'name' => '邳州市',
            ),
            218 => 
            array (
                'id' => 26744,
                'area_id' => 320401,
                'name' => '市辖区',
            ),
            219 => 
            array (
                'id' => 26771,
                'area_id' => 320402,
                'name' => '天宁区',
            ),
            220 => 
            array (
                'id' => 26798,
                'area_id' => 320404,
                'name' => '钟楼区',
            ),
            221 => 
            array (
                'id' => 26825,
                'area_id' => 320405,
                'name' => '戚墅堰区',
            ),
            222 => 
            array (
                'id' => 26852,
                'area_id' => 320411,
                'name' => '新北区',
            ),
            223 => 
            array (
                'id' => 26879,
                'area_id' => 320412,
                'name' => '武进区',
            ),
            224 => 
            array (
                'id' => 26906,
                'area_id' => 320481,
                'name' => '溧阳市',
            ),
            225 => 
            array (
                'id' => 26933,
                'area_id' => 320482,
                'name' => '金坛市',
            ),
            226 => 
            array (
                'id' => 26952,
                'area_id' => 320501,
                'name' => '市辖区',
            ),
            227 => 
            array (
                'id' => 26979,
                'area_id' => 320502,
                'name' => '沧浪区',
            ),
            228 => 
            array (
                'id' => 27006,
                'area_id' => 320503,
                'name' => '平江区',
            ),
            229 => 
            array (
                'id' => 27033,
                'area_id' => 320504,
                'name' => '金阊区',
            ),
            230 => 
            array (
                'id' => 27060,
                'area_id' => 320505,
                'name' => '虎丘区',
            ),
            231 => 
            array (
                'id' => 27087,
                'area_id' => 320506,
                'name' => '吴中区',
            ),
            232 => 
            array (
                'id' => 27114,
                'area_id' => 320507,
                'name' => '相城区',
            ),
            233 => 
            array (
                'id' => 27141,
                'area_id' => 320581,
                'name' => '常熟市',
            ),
            234 => 
            array (
                'id' => 27168,
                'area_id' => 320582,
                'name' => '张家港市',
            ),
            235 => 
            array (
                'id' => 27195,
                'area_id' => 320583,
                'name' => '昆山市',
            ),
            236 => 
            array (
                'id' => 27222,
                'area_id' => 320584,
                'name' => '吴江市',
            ),
            237 => 
            array (
                'id' => 27249,
                'area_id' => 320585,
                'name' => '太仓市',
            ),
            238 => 
            array (
                'id' => 27264,
                'area_id' => 320601,
                'name' => '市辖区',
            ),
            239 => 
            array (
                'id' => 27291,
                'area_id' => 320602,
                'name' => '崇川区',
            ),
            240 => 
            array (
                'id' => 27318,
                'area_id' => 320611,
                'name' => '港闸区',
            ),
            241 => 
            array (
                'id' => 27345,
                'area_id' => 320621,
                'name' => '海安县',
            ),
            242 => 
            array (
                'id' => 27372,
                'area_id' => 320623,
                'name' => '如东县',
            ),
            243 => 
            array (
                'id' => 27399,
                'area_id' => 320681,
                'name' => '启东市',
            ),
            244 => 
            array (
                'id' => 27426,
                'area_id' => 320682,
                'name' => '如皋市',
            ),
            245 => 
            array (
                'id' => 27453,
                'area_id' => 320683,
                'name' => '通州市',
            ),
            246 => 
            array (
                'id' => 27480,
                'area_id' => 320684,
                'name' => '海门市',
            ),
            247 => 
            array (
                'id' => 27498,
                'area_id' => 320701,
                'name' => '市辖区',
            ),
            248 => 
            array (
                'id' => 27525,
                'area_id' => 320703,
                'name' => '连云区',
            ),
            249 => 
            array (
                'id' => 27552,
                'area_id' => 320705,
                'name' => '新浦区',
            ),
            250 => 
            array (
                'id' => 27579,
                'area_id' => 320706,
                'name' => '海州区',
            ),
            251 => 
            array (
                'id' => 27606,
                'area_id' => 320721,
                'name' => '赣榆县',
            ),
            252 => 
            array (
                'id' => 27633,
                'area_id' => 320722,
                'name' => '东海县',
            ),
            253 => 
            array (
                'id' => 27660,
                'area_id' => 320723,
                'name' => '灌云县',
            ),
            254 => 
            array (
                'id' => 27687,
                'area_id' => 320724,
                'name' => '灌南县',
            ),
            255 => 
            array (
                'id' => 27706,
                'area_id' => 320801,
                'name' => '市辖区',
            ),
            256 => 
            array (
                'id' => 27733,
                'area_id' => 320802,
                'name' => '清河区',
            ),
            257 => 
            array (
                'id' => 27760,
                'area_id' => 320803,
                'name' => '楚州区',
            ),
            258 => 
            array (
                'id' => 27787,
                'area_id' => 320804,
                'name' => '淮阴区',
            ),
            259 => 
            array (
                'id' => 27814,
                'area_id' => 320811,
                'name' => '清浦区',
            ),
            260 => 
            array (
                'id' => 27841,
                'area_id' => 320826,
                'name' => '涟水县',
            ),
            261 => 
            array (
                'id' => 27868,
                'area_id' => 320829,
                'name' => '洪泽县',
            ),
            262 => 
            array (
                'id' => 27895,
                'area_id' => 320830,
                'name' => '盱眙县',
            ),
            263 => 
            array (
                'id' => 27922,
                'area_id' => 320831,
                'name' => '金湖县',
            ),
            264 => 
            array (
                'id' => 27940,
                'area_id' => 320901,
                'name' => '市辖区',
            ),
            265 => 
            array (
                'id' => 27967,
                'area_id' => 320902,
                'name' => '亭湖区',
            ),
            266 => 
            array (
                'id' => 27994,
                'area_id' => 320903,
                'name' => '盐都区',
            ),
            267 => 
            array (
                'id' => 28021,
                'area_id' => 320921,
                'name' => '响水县',
            ),
            268 => 
            array (
                'id' => 28048,
                'area_id' => 320922,
                'name' => '滨海县',
            ),
            269 => 
            array (
                'id' => 28075,
                'area_id' => 320923,
                'name' => '阜宁县',
            ),
            270 => 
            array (
                'id' => 28102,
                'area_id' => 320924,
                'name' => '射阳县',
            ),
            271 => 
            array (
                'id' => 28129,
                'area_id' => 320925,
                'name' => '建湖县',
            ),
            272 => 
            array (
                'id' => 28156,
                'area_id' => 320981,
                'name' => '东台市',
            ),
            273 => 
            array (
                'id' => 28183,
                'area_id' => 320982,
                'name' => '大丰市',
            ),
            274 => 
            array (
                'id' => 28200,
                'area_id' => 321001,
                'name' => '市辖区',
            ),
            275 => 
            array (
                'id' => 28227,
                'area_id' => 321002,
                'name' => '广陵区',
            ),
            276 => 
            array (
                'id' => 28254,
                'area_id' => 321003,
                'name' => '邗江区',
            ),
            277 => 
            array (
                'id' => 28281,
                'area_id' => 321011,
                'name' => '郊　区',
            ),
            278 => 
            array (
                'id' => 28308,
                'area_id' => 321023,
                'name' => '宝应县',
            ),
            279 => 
            array (
                'id' => 28335,
                'area_id' => 321081,
                'name' => '仪征市',
            ),
            280 => 
            array (
                'id' => 28362,
                'area_id' => 321084,
                'name' => '高邮市',
            ),
            281 => 
            array (
                'id' => 28389,
                'area_id' => 321088,
                'name' => '江都市',
            ),
            282 => 
            array (
                'id' => 28408,
                'area_id' => 321101,
                'name' => '市辖区',
            ),
            283 => 
            array (
                'id' => 28435,
                'area_id' => 321102,
                'name' => '京口区',
            ),
            284 => 
            array (
                'id' => 28462,
                'area_id' => 321111,
                'name' => '润州区',
            ),
            285 => 
            array (
                'id' => 28489,
                'area_id' => 321112,
                'name' => '丹徒区',
            ),
            286 => 
            array (
                'id' => 28516,
                'area_id' => 321181,
                'name' => '丹阳市',
            ),
            287 => 
            array (
                'id' => 28543,
                'area_id' => 321182,
                'name' => '扬中市',
            ),
            288 => 
            array (
                'id' => 28570,
                'area_id' => 321183,
                'name' => '句容市',
            ),
            289 => 
            array (
                'id' => 28590,
                'area_id' => 321201,
                'name' => '市辖区',
            ),
            290 => 
            array (
                'id' => 28617,
                'area_id' => 321202,
                'name' => '海陵区',
            ),
            291 => 
            array (
                'id' => 28644,
                'area_id' => 321203,
                'name' => '高港区',
            ),
            292 => 
            array (
                'id' => 28671,
                'area_id' => 321281,
                'name' => '兴化市',
            ),
            293 => 
            array (
                'id' => 28698,
                'area_id' => 321282,
                'name' => '靖江市',
            ),
            294 => 
            array (
                'id' => 28725,
                'area_id' => 321283,
                'name' => '泰兴市',
            ),
            295 => 
            array (
                'id' => 28752,
                'area_id' => 321284,
                'name' => '姜堰市',
            ),
            296 => 
            array (
                'id' => 28772,
                'area_id' => 321301,
                'name' => '市辖区',
            ),
            297 => 
            array (
                'id' => 28799,
                'area_id' => 321302,
                'name' => '宿城区',
            ),
            298 => 
            array (
                'id' => 28826,
                'area_id' => 321311,
                'name' => '宿豫区',
            ),
            299 => 
            array (
                'id' => 28853,
                'area_id' => 321322,
                'name' => '沭阳县',
            ),
            300 => 
            array (
                'id' => 28880,
                'area_id' => 321323,
                'name' => '泗阳县',
            ),
            301 => 
            array (
                'id' => 28907,
                'area_id' => 321324,
                'name' => '泗洪县',
            ),
            302 => 
            array (
                'id' => 28928,
                'area_id' => 330101,
                'name' => '市辖区',
            ),
            303 => 
            array (
                'id' => 28955,
                'area_id' => 330102,
                'name' => '上城区',
            ),
            304 => 
            array (
                'id' => 28982,
                'area_id' => 330103,
                'name' => '下城区',
            ),
            305 => 
            array (
                'id' => 29009,
                'area_id' => 330104,
                'name' => '江干区',
            ),
            306 => 
            array (
                'id' => 29036,
                'area_id' => 330105,
                'name' => '拱墅区',
            ),
            307 => 
            array (
                'id' => 29063,
                'area_id' => 330106,
                'name' => '西湖区',
            ),
            308 => 
            array (
                'id' => 29090,
                'area_id' => 330108,
                'name' => '滨江区',
            ),
            309 => 
            array (
                'id' => 29117,
                'area_id' => 330109,
                'name' => '萧山区',
            ),
            310 => 
            array (
                'id' => 29144,
                'area_id' => 330110,
                'name' => '余杭区',
            ),
            311 => 
            array (
                'id' => 29171,
                'area_id' => 330122,
                'name' => '桐庐县',
            ),
            312 => 
            array (
                'id' => 29198,
                'area_id' => 330127,
                'name' => '淳安县',
            ),
            313 => 
            array (
                'id' => 29225,
                'area_id' => 330182,
                'name' => '建德市',
            ),
            314 => 
            array (
                'id' => 29252,
                'area_id' => 330183,
                'name' => '富阳市',
            ),
            315 => 
            array (
                'id' => 29279,
                'area_id' => 330185,
                'name' => '临安市',
            ),
            316 => 
            array (
                'id' => 29292,
                'area_id' => 330201,
                'name' => '市辖区',
            ),
            317 => 
            array (
                'id' => 29319,
                'area_id' => 330203,
                'name' => '海曙区',
            ),
            318 => 
            array (
                'id' => 29346,
                'area_id' => 330204,
                'name' => '江东区',
            ),
            319 => 
            array (
                'id' => 29373,
                'area_id' => 330205,
                'name' => '江北区',
            ),
            320 => 
            array (
                'id' => 29400,
                'area_id' => 330206,
                'name' => '北仑区',
            ),
            321 => 
            array (
                'id' => 29427,
                'area_id' => 330211,
                'name' => '镇海区',
            ),
            322 => 
            array (
                'id' => 29454,
                'area_id' => 330212,
                'name' => '鄞州区',
            ),
            323 => 
            array (
                'id' => 29481,
                'area_id' => 330225,
                'name' => '象山县',
            ),
            324 => 
            array (
                'id' => 29508,
                'area_id' => 330226,
                'name' => '宁海县',
            ),
            325 => 
            array (
                'id' => 29535,
                'area_id' => 330281,
                'name' => '余姚市',
            ),
            326 => 
            array (
                'id' => 29562,
                'area_id' => 330282,
                'name' => '慈溪市',
            ),
            327 => 
            array (
                'id' => 29589,
                'area_id' => 330283,
                'name' => '奉化市',
            ),
            328 => 
            array (
                'id' => 29604,
                'area_id' => 330301,
                'name' => '市辖区',
            ),
            329 => 
            array (
                'id' => 29631,
                'area_id' => 330302,
                'name' => '鹿城区',
            ),
            330 => 
            array (
                'id' => 29658,
                'area_id' => 330303,
                'name' => '龙湾区',
            ),
            331 => 
            array (
                'id' => 29685,
                'area_id' => 330304,
                'name' => '瓯海区',
            ),
            332 => 
            array (
                'id' => 29712,
                'area_id' => 330322,
                'name' => '洞头县',
            ),
            333 => 
            array (
                'id' => 29739,
                'area_id' => 330324,
                'name' => '永嘉县',
            ),
            334 => 
            array (
                'id' => 29766,
                'area_id' => 330326,
                'name' => '平阳县',
            ),
            335 => 
            array (
                'id' => 29793,
                'area_id' => 330327,
                'name' => '苍南县',
            ),
            336 => 
            array (
                'id' => 29820,
                'area_id' => 330328,
                'name' => '文成县',
            ),
            337 => 
            array (
                'id' => 29847,
                'area_id' => 330329,
                'name' => '泰顺县',
            ),
            338 => 
            array (
                'id' => 29874,
                'area_id' => 330381,
                'name' => '瑞安市',
            ),
            339 => 
            array (
                'id' => 29901,
                'area_id' => 330382,
                'name' => '乐清市',
            ),
            340 => 
            array (
                'id' => 29916,
                'area_id' => 330401,
                'name' => '市辖区',
            ),
            341 => 
            array (
                'id' => 29943,
                'area_id' => 330402,
                'name' => '秀城区',
            ),
            342 => 
            array (
                'id' => 29970,
                'area_id' => 330411,
                'name' => '秀洲区',
            ),
            343 => 
            array (
                'id' => 29997,
                'area_id' => 330421,
                'name' => '嘉善县',
            ),
            344 => 
            array (
                'id' => 30024,
                'area_id' => 330424,
                'name' => '海盐县',
            ),
            345 => 
            array (
                'id' => 30051,
                'area_id' => 330481,
                'name' => '海宁市',
            ),
            346 => 
            array (
                'id' => 30078,
                'area_id' => 330482,
                'name' => '平湖市',
            ),
            347 => 
            array (
                'id' => 30105,
                'area_id' => 330483,
                'name' => '桐乡市',
            ),
            348 => 
            array (
                'id' => 30124,
                'area_id' => 330501,
                'name' => '市辖区',
            ),
            349 => 
            array (
                'id' => 30151,
                'area_id' => 330502,
                'name' => '吴兴区',
            ),
            350 => 
            array (
                'id' => 30178,
                'area_id' => 330503,
                'name' => '南浔区',
            ),
            351 => 
            array (
                'id' => 30205,
                'area_id' => 330521,
                'name' => '德清县',
            ),
            352 => 
            array (
                'id' => 30232,
                'area_id' => 330522,
                'name' => '长兴县',
            ),
            353 => 
            array (
                'id' => 30259,
                'area_id' => 330523,
                'name' => '安吉县',
            ),
            354 => 
            array (
                'id' => 30280,
                'area_id' => 330601,
                'name' => '市辖区',
            ),
            355 => 
            array (
                'id' => 30307,
                'area_id' => 330602,
                'name' => '越城区',
            ),
            356 => 
            array (
                'id' => 30334,
                'area_id' => 330621,
                'name' => '绍兴县',
            ),
            357 => 
            array (
                'id' => 30361,
                'area_id' => 330624,
                'name' => '新昌县',
            ),
            358 => 
            array (
                'id' => 30388,
                'area_id' => 330681,
                'name' => '诸暨市',
            ),
            359 => 
            array (
                'id' => 30415,
                'area_id' => 330682,
                'name' => '上虞市',
            ),
            360 => 
            array (
                'id' => 30442,
                'area_id' => 330683,
                'name' => '嵊州市',
            ),
            361 => 
            array (
                'id' => 30462,
                'area_id' => 330701,
                'name' => '市辖区',
            ),
            362 => 
            array (
                'id' => 30489,
                'area_id' => 330702,
                'name' => '婺城区',
            ),
            363 => 
            array (
                'id' => 30516,
                'area_id' => 330703,
                'name' => '金东区',
            ),
            364 => 
            array (
                'id' => 30543,
                'area_id' => 330723,
                'name' => '武义县',
            ),
            365 => 
            array (
                'id' => 30570,
                'area_id' => 330726,
                'name' => '浦江县',
            ),
            366 => 
            array (
                'id' => 30597,
                'area_id' => 330727,
                'name' => '磐安县',
            ),
            367 => 
            array (
                'id' => 30624,
                'area_id' => 330781,
                'name' => '兰溪市',
            ),
            368 => 
            array (
                'id' => 30651,
                'area_id' => 330782,
                'name' => '义乌市',
            ),
            369 => 
            array (
                'id' => 30678,
                'area_id' => 330783,
                'name' => '东阳市',
            ),
            370 => 
            array (
                'id' => 30705,
                'area_id' => 330784,
                'name' => '永康市',
            ),
            371 => 
            array (
                'id' => 30722,
                'area_id' => 330801,
                'name' => '市辖区',
            ),
            372 => 
            array (
                'id' => 30749,
                'area_id' => 330802,
                'name' => '柯城区',
            ),
            373 => 
            array (
                'id' => 30776,
                'area_id' => 330803,
                'name' => '衢江区',
            ),
            374 => 
            array (
                'id' => 30803,
                'area_id' => 330822,
                'name' => '常山县',
            ),
            375 => 
            array (
                'id' => 30830,
                'area_id' => 330824,
                'name' => '开化县',
            ),
            376 => 
            array (
                'id' => 30857,
                'area_id' => 330825,
                'name' => '龙游县',
            ),
            377 => 
            array (
                'id' => 30884,
                'area_id' => 330881,
                'name' => '江山市',
            ),
            378 => 
            array (
                'id' => 30904,
                'area_id' => 330901,
                'name' => '市辖区',
            ),
            379 => 
            array (
                'id' => 30931,
                'area_id' => 330902,
                'name' => '定海区',
            ),
            380 => 
            array (
                'id' => 30958,
                'area_id' => 330903,
                'name' => '普陀区',
            ),
            381 => 
            array (
                'id' => 30985,
                'area_id' => 330921,
                'name' => '岱山县',
            ),
            382 => 
            array (
                'id' => 31012,
                'area_id' => 330922,
                'name' => '嵊泗县',
            ),
            383 => 
            array (
                'id' => 31034,
                'area_id' => 331001,
                'name' => '市辖区',
            ),
            384 => 
            array (
                'id' => 31061,
                'area_id' => 331002,
                'name' => '椒江区',
            ),
            385 => 
            array (
                'id' => 31088,
                'area_id' => 331003,
                'name' => '黄岩区',
            ),
            386 => 
            array (
                'id' => 31115,
                'area_id' => 331004,
                'name' => '路桥区',
            ),
            387 => 
            array (
                'id' => 31142,
                'area_id' => 331021,
                'name' => '玉环县',
            ),
            388 => 
            array (
                'id' => 31169,
                'area_id' => 331022,
                'name' => '三门县',
            ),
            389 => 
            array (
                'id' => 31196,
                'area_id' => 331023,
                'name' => '天台县',
            ),
            390 => 
            array (
                'id' => 31223,
                'area_id' => 331024,
                'name' => '仙居县',
            ),
            391 => 
            array (
                'id' => 31250,
                'area_id' => 331081,
                'name' => '温岭市',
            ),
            392 => 
            array (
                'id' => 31277,
                'area_id' => 331082,
                'name' => '临海市',
            ),
            393 => 
            array (
                'id' => 31294,
                'area_id' => 331101,
                'name' => '市辖区',
            ),
            394 => 
            array (
                'id' => 31321,
                'area_id' => 331102,
                'name' => '莲都区',
            ),
            395 => 
            array (
                'id' => 31348,
                'area_id' => 331121,
                'name' => '青田县',
            ),
            396 => 
            array (
                'id' => 31375,
                'area_id' => 331122,
                'name' => '缙云县',
            ),
            397 => 
            array (
                'id' => 31402,
                'area_id' => 331123,
                'name' => '遂昌县',
            ),
            398 => 
            array (
                'id' => 31429,
                'area_id' => 331124,
                'name' => '松阳县',
            ),
            399 => 
            array (
                'id' => 31456,
                'area_id' => 331125,
                'name' => '云和县',
            ),
            400 => 
            array (
                'id' => 31483,
                'area_id' => 331126,
                'name' => '庆元县',
            ),
            401 => 
            array (
                'id' => 31510,
                'area_id' => 331127,
                'name' => '景宁畲族自治县',
            ),
            402 => 
            array (
                'id' => 31537,
                'area_id' => 331181,
                'name' => '龙泉市',
            ),
            403 => 
            array (
                'id' => 31554,
                'area_id' => 340101,
                'name' => '市辖区',
            ),
            404 => 
            array (
                'id' => 31581,
                'area_id' => 340102,
                'name' => '瑶海区',
            ),
            405 => 
            array (
                'id' => 31608,
                'area_id' => 340103,
                'name' => '庐阳区',
            ),
            406 => 
            array (
                'id' => 31635,
                'area_id' => 340104,
                'name' => '蜀山区',
            ),
            407 => 
            array (
                'id' => 31662,
                'area_id' => 340111,
                'name' => '包河区',
            ),
            408 => 
            array (
                'id' => 31689,
                'area_id' => 340121,
                'name' => '长丰县',
            ),
            409 => 
            array (
                'id' => 31716,
                'area_id' => 340122,
                'name' => '肥东县',
            ),
            410 => 
            array (
                'id' => 31743,
                'area_id' => 340123,
                'name' => '肥西县',
            ),
            411 => 
            array (
                'id' => 31770,
                'area_id' => 341401,
                'name' => '庐江县',
            ),
            412 => 
            array (
                'id' => 31797,
                'area_id' => 341402,
                'name' => '巢湖市',
            ),
            413 => 
            array (
                'id' => 31814,
                'area_id' => 340201,
                'name' => '市辖区',
            ),
            414 => 
            array (
                'id' => 31841,
                'area_id' => 340202,
                'name' => '镜湖区',
            ),
            415 => 
            array (
                'id' => 31868,
                'area_id' => 340203,
                'name' => '马塘区',
            ),
            416 => 
            array (
                'id' => 31895,
                'area_id' => 340204,
                'name' => '新芜区',
            ),
            417 => 
            array (
                'id' => 31922,
                'area_id' => 340207,
                'name' => '鸠江区',
            ),
            418 => 
            array (
                'id' => 31949,
                'area_id' => 340221,
                'name' => '芜湖县',
            ),
            419 => 
            array (
                'id' => 31976,
                'area_id' => 340222,
                'name' => '繁昌县',
            ),
            420 => 
            array (
                'id' => 32003,
                'area_id' => 340223,
                'name' => '南陵县',
            ),
            421 => 
            array (
                'id' => 32030,
                'area_id' => 341422,
                'name' => '无为县',
            ),
            422 => 
            array (
                'id' => 32048,
                'area_id' => 340301,
                'name' => '市辖区',
            ),
            423 => 
            array (
                'id' => 32075,
                'area_id' => 340302,
                'name' => '龙子湖区',
            ),
            424 => 
            array (
                'id' => 32102,
                'area_id' => 340303,
                'name' => '蚌山区',
            ),
            425 => 
            array (
                'id' => 32129,
                'area_id' => 340304,
                'name' => '禹会区',
            ),
            426 => 
            array (
                'id' => 32156,
                'area_id' => 340311,
                'name' => '淮上区',
            ),
            427 => 
            array (
                'id' => 32183,
                'area_id' => 340321,
                'name' => '怀远县',
            ),
            428 => 
            array (
                'id' => 32210,
                'area_id' => 340322,
                'name' => '五河县',
            ),
            429 => 
            array (
                'id' => 32237,
                'area_id' => 340323,
                'name' => '固镇县',
            ),
            430 => 
            array (
                'id' => 32256,
                'area_id' => 340401,
                'name' => '市辖区',
            ),
            431 => 
            array (
                'id' => 32283,
                'area_id' => 340402,
                'name' => '大通区',
            ),
            432 => 
            array (
                'id' => 32310,
                'area_id' => 340403,
                'name' => '田家庵区',
            ),
            433 => 
            array (
                'id' => 32337,
                'area_id' => 340404,
                'name' => '谢家集区',
            ),
            434 => 
            array (
                'id' => 32364,
                'area_id' => 340405,
                'name' => '八公山区',
            ),
            435 => 
            array (
                'id' => 32391,
                'area_id' => 340406,
                'name' => '潘集区',
            ),
            436 => 
            array (
                'id' => 32418,
                'area_id' => 340421,
                'name' => '凤台县',
            ),
            437 => 
            array (
                'id' => 32438,
                'area_id' => 340501,
                'name' => '市辖区',
            ),
            438 => 
            array (
                'id' => 32465,
                'area_id' => 340502,
                'name' => '金家庄区',
            ),
            439 => 
            array (
                'id' => 32492,
                'area_id' => 340503,
                'name' => '花山区',
            ),
            440 => 
            array (
                'id' => 32519,
                'area_id' => 340504,
                'name' => '雨山区',
            ),
            441 => 
            array (
                'id' => 32546,
                'area_id' => 340521,
                'name' => '当涂县',
            ),
            442 => 
            array (
                'id' => 32573,
                'area_id' => 341423,
                'name' => '含山县',
            ),
            443 => 
            array (
                'id' => 32600,
                'area_id' => 341424,
                'name' => '和　县',
            ),
            444 => 
            array (
                'id' => 32620,
                'area_id' => 340601,
                'name' => '市辖区',
            ),
            445 => 
            array (
                'id' => 32647,
                'area_id' => 340602,
                'name' => '杜集区',
            ),
            446 => 
            array (
                'id' => 32674,
                'area_id' => 340603,
                'name' => '相山区',
            ),
            447 => 
            array (
                'id' => 32701,
                'area_id' => 340604,
                'name' => '烈山区',
            ),
            448 => 
            array (
                'id' => 32728,
                'area_id' => 340621,
                'name' => '濉溪县',
            ),
            449 => 
            array (
                'id' => 32750,
                'area_id' => 340701,
                'name' => '市辖区',
            ),
            450 => 
            array (
                'id' => 32777,
                'area_id' => 340702,
                'name' => '铜官山区',
            ),
            451 => 
            array (
                'id' => 32804,
                'area_id' => 340703,
                'name' => '狮子山区',
            ),
            452 => 
            array (
                'id' => 32831,
                'area_id' => 340711,
                'name' => '郊　区',
            ),
            453 => 
            array (
                'id' => 32858,
                'area_id' => 340721,
                'name' => '铜陵县',
            ),
            454 => 
            array (
                'id' => 32880,
                'area_id' => 340801,
                'name' => '市辖区',
            ),
            455 => 
            array (
                'id' => 32907,
                'area_id' => 340802,
                'name' => '迎江区',
            ),
            456 => 
            array (
                'id' => 32934,
                'area_id' => 340803,
                'name' => '大观区',
            ),
            457 => 
            array (
                'id' => 32961,
                'area_id' => 340811,
                'name' => '郊　区',
            ),
            458 => 
            array (
                'id' => 32988,
                'area_id' => 340822,
                'name' => '怀宁县',
            ),
            459 => 
            array (
                'id' => 33015,
                'area_id' => 340823,
                'name' => '枞阳县',
            ),
            460 => 
            array (
                'id' => 33042,
                'area_id' => 340824,
                'name' => '潜山县',
            ),
            461 => 
            array (
                'id' => 33069,
                'area_id' => 340825,
                'name' => '太湖县',
            ),
            462 => 
            array (
                'id' => 33096,
                'area_id' => 340826,
                'name' => '宿松县',
            ),
            463 => 
            array (
                'id' => 33123,
                'area_id' => 340827,
                'name' => '望江县',
            ),
            464 => 
            array (
                'id' => 33150,
                'area_id' => 340828,
                'name' => '岳西县',
            ),
            465 => 
            array (
                'id' => 33177,
                'area_id' => 340881,
                'name' => '桐城市',
            ),
            466 => 
            array (
                'id' => 33192,
                'area_id' => 341001,
                'name' => '市辖区',
            ),
            467 => 
            array (
                'id' => 33219,
                'area_id' => 341002,
                'name' => '屯溪区',
            ),
            468 => 
            array (
                'id' => 33246,
                'area_id' => 341003,
                'name' => '黄山区',
            ),
            469 => 
            array (
                'id' => 33273,
                'area_id' => 341004,
                'name' => '徽州区',
            ),
            470 => 
            array (
                'id' => 33300,
                'area_id' => 341021,
                'name' => '歙　县',
            ),
            471 => 
            array (
                'id' => 33327,
                'area_id' => 341022,
                'name' => '休宁县',
            ),
            472 => 
            array (
                'id' => 33354,
                'area_id' => 341023,
                'name' => '黟　县',
            ),
            473 => 
            array (
                'id' => 33381,
                'area_id' => 341024,
                'name' => '祁门县',
            ),
            474 => 
            array (
                'id' => 33400,
                'area_id' => 341101,
                'name' => '市辖区',
            ),
            475 => 
            array (
                'id' => 33427,
                'area_id' => 341102,
                'name' => '琅琊区',
            ),
            476 => 
            array (
                'id' => 33454,
                'area_id' => 341103,
                'name' => '南谯区',
            ),
            477 => 
            array (
                'id' => 33481,
                'area_id' => 341122,
                'name' => '来安县',
            ),
            478 => 
            array (
                'id' => 33508,
                'area_id' => 341124,
                'name' => '全椒县',
            ),
            479 => 
            array (
                'id' => 33535,
                'area_id' => 341125,
                'name' => '定远县',
            ),
            480 => 
            array (
                'id' => 33562,
                'area_id' => 341126,
                'name' => '凤阳县',
            ),
            481 => 
            array (
                'id' => 33589,
                'area_id' => 341181,
                'name' => '天长市',
            ),
            482 => 
            array (
                'id' => 33616,
                'area_id' => 341182,
                'name' => '明光市',
            ),
            483 => 
            array (
                'id' => 33634,
                'area_id' => 341201,
                'name' => '市辖区',
            ),
            484 => 
            array (
                'id' => 33661,
                'area_id' => 341202,
                'name' => '颍州区',
            ),
            485 => 
            array (
                'id' => 33688,
                'area_id' => 341203,
                'name' => '颍东区',
            ),
            486 => 
            array (
                'id' => 33715,
                'area_id' => 341204,
                'name' => '颍泉区',
            ),
            487 => 
            array (
                'id' => 33742,
                'area_id' => 341221,
                'name' => '临泉县',
            ),
            488 => 
            array (
                'id' => 33769,
                'area_id' => 341222,
                'name' => '太和县',
            ),
            489 => 
            array (
                'id' => 33796,
                'area_id' => 341225,
                'name' => '阜南县',
            ),
            490 => 
            array (
                'id' => 33823,
                'area_id' => 341226,
                'name' => '颍上县',
            ),
            491 => 
            array (
                'id' => 33850,
                'area_id' => 341282,
                'name' => '界首市',
            ),
            492 => 
            array (
                'id' => 33868,
                'area_id' => 341301,
                'name' => '市辖区',
            ),
            493 => 
            array (
                'id' => 33895,
                'area_id' => 341302,
                'name' => '墉桥区',
            ),
            494 => 
            array (
                'id' => 33922,
                'area_id' => 341321,
                'name' => '砀山县',
            ),
            495 => 
            array (
                'id' => 33949,
                'area_id' => 341322,
                'name' => '萧　县',
            ),
            496 => 
            array (
                'id' => 33976,
                'area_id' => 341323,
                'name' => '灵璧县',
            ),
            497 => 
            array (
                'id' => 34003,
                'area_id' => 341324,
                'name' => '泗　县',
            ),
            498 => 
            array (
                'id' => 34024,
                'area_id' => 341501,
                'name' => '市辖区',
            ),
            499 => 
            array (
                'id' => 34051,
                'area_id' => 341502,
                'name' => '金安区',
            ),
        ));
        \DB::table('areas')->insert(array (
            0 => 
            array (
                'id' => 34078,
                'area_id' => 341503,
                'name' => '裕安区',
            ),
            1 => 
            array (
                'id' => 34105,
                'area_id' => 341521,
                'name' => '寿　县',
            ),
            2 => 
            array (
                'id' => 34132,
                'area_id' => 341522,
                'name' => '霍邱县',
            ),
            3 => 
            array (
                'id' => 34159,
                'area_id' => 341523,
                'name' => '舒城县',
            ),
            4 => 
            array (
                'id' => 34186,
                'area_id' => 341524,
                'name' => '金寨县',
            ),
            5 => 
            array (
                'id' => 34213,
                'area_id' => 341525,
                'name' => '霍山县',
            ),
            6 => 
            array (
                'id' => 34232,
                'area_id' => 341601,
                'name' => '市辖区',
            ),
            7 => 
            array (
                'id' => 34259,
                'area_id' => 341602,
                'name' => '谯城区',
            ),
            8 => 
            array (
                'id' => 34286,
                'area_id' => 341621,
                'name' => '涡阳县',
            ),
            9 => 
            array (
                'id' => 34313,
                'area_id' => 341622,
                'name' => '蒙城县',
            ),
            10 => 
            array (
                'id' => 34340,
                'area_id' => 341623,
                'name' => '利辛县',
            ),
            11 => 
            array (
                'id' => 34362,
                'area_id' => 341701,
                'name' => '市辖区',
            ),
            12 => 
            array (
                'id' => 34389,
                'area_id' => 341702,
                'name' => '贵池区',
            ),
            13 => 
            array (
                'id' => 34416,
                'area_id' => 341721,
                'name' => '东至县',
            ),
            14 => 
            array (
                'id' => 34443,
                'area_id' => 341722,
                'name' => '石台县',
            ),
            15 => 
            array (
                'id' => 34470,
                'area_id' => 341723,
                'name' => '青阳县',
            ),
            16 => 
            array (
                'id' => 34492,
                'area_id' => 341801,
                'name' => '市辖区',
            ),
            17 => 
            array (
                'id' => 34519,
                'area_id' => 341802,
                'name' => '宣州区',
            ),
            18 => 
            array (
                'id' => 34546,
                'area_id' => 341821,
                'name' => '郎溪县',
            ),
            19 => 
            array (
                'id' => 34573,
                'area_id' => 341822,
                'name' => '广德县',
            ),
            20 => 
            array (
                'id' => 34600,
                'area_id' => 341823,
                'name' => '泾　县',
            ),
            21 => 
            array (
                'id' => 34627,
                'area_id' => 341824,
                'name' => '绩溪县',
            ),
            22 => 
            array (
                'id' => 34654,
                'area_id' => 341825,
                'name' => '旌德县',
            ),
            23 => 
            array (
                'id' => 34681,
                'area_id' => 341881,
                'name' => '宁国市',
            ),
            24 => 
            array (
                'id' => 34700,
                'area_id' => 350101,
                'name' => '市辖区',
            ),
            25 => 
            array (
                'id' => 34727,
                'area_id' => 350102,
                'name' => '鼓楼区',
            ),
            26 => 
            array (
                'id' => 34754,
                'area_id' => 350103,
                'name' => '台江区',
            ),
            27 => 
            array (
                'id' => 34781,
                'area_id' => 350104,
                'name' => '仓山区',
            ),
            28 => 
            array (
                'id' => 34808,
                'area_id' => 350105,
                'name' => '马尾区',
            ),
            29 => 
            array (
                'id' => 34835,
                'area_id' => 350111,
                'name' => '晋安区',
            ),
            30 => 
            array (
                'id' => 34862,
                'area_id' => 350121,
                'name' => '闽侯县',
            ),
            31 => 
            array (
                'id' => 34889,
                'area_id' => 350122,
                'name' => '连江县',
            ),
            32 => 
            array (
                'id' => 34916,
                'area_id' => 350123,
                'name' => '罗源县',
            ),
            33 => 
            array (
                'id' => 34943,
                'area_id' => 350124,
                'name' => '闽清县',
            ),
            34 => 
            array (
                'id' => 34970,
                'area_id' => 350125,
                'name' => '永泰县',
            ),
            35 => 
            array (
                'id' => 34997,
                'area_id' => 350128,
                'name' => '平潭县',
            ),
            36 => 
            array (
                'id' => 35024,
                'area_id' => 350181,
                'name' => '福清市',
            ),
            37 => 
            array (
                'id' => 35051,
                'area_id' => 350182,
                'name' => '长乐市',
            ),
            38 => 
            array (
                'id' => 35064,
                'area_id' => 350201,
                'name' => '市辖区',
            ),
            39 => 
            array (
                'id' => 35091,
                'area_id' => 350203,
                'name' => '思明区',
            ),
            40 => 
            array (
                'id' => 35118,
                'area_id' => 350205,
                'name' => '海沧区',
            ),
            41 => 
            array (
                'id' => 35145,
                'area_id' => 350206,
                'name' => '湖里区',
            ),
            42 => 
            array (
                'id' => 35172,
                'area_id' => 350211,
                'name' => '集美区',
            ),
            43 => 
            array (
                'id' => 35199,
                'area_id' => 350212,
                'name' => '同安区',
            ),
            44 => 
            array (
                'id' => 35226,
                'area_id' => 350213,
                'name' => '翔安区',
            ),
            45 => 
            array (
                'id' => 35246,
                'area_id' => 350301,
                'name' => '市辖区',
            ),
            46 => 
            array (
                'id' => 35273,
                'area_id' => 350302,
                'name' => '城厢区',
            ),
            47 => 
            array (
                'id' => 35300,
                'area_id' => 350303,
                'name' => '涵江区',
            ),
            48 => 
            array (
                'id' => 35327,
                'area_id' => 350304,
                'name' => '荔城区',
            ),
            49 => 
            array (
                'id' => 35354,
                'area_id' => 350305,
                'name' => '秀屿区',
            ),
            50 => 
            array (
                'id' => 35381,
                'area_id' => 350322,
                'name' => '仙游县',
            ),
            51 => 
            array (
                'id' => 35402,
                'area_id' => 350401,
                'name' => '市辖区',
            ),
            52 => 
            array (
                'id' => 35429,
                'area_id' => 350402,
                'name' => '梅列区',
            ),
            53 => 
            array (
                'id' => 35456,
                'area_id' => 350403,
                'name' => '三元区',
            ),
            54 => 
            array (
                'id' => 35483,
                'area_id' => 350421,
                'name' => '明溪县',
            ),
            55 => 
            array (
                'id' => 35510,
                'area_id' => 350423,
                'name' => '清流县',
            ),
            56 => 
            array (
                'id' => 35537,
                'area_id' => 350424,
                'name' => '宁化县',
            ),
            57 => 
            array (
                'id' => 35564,
                'area_id' => 350425,
                'name' => '大田县',
            ),
            58 => 
            array (
                'id' => 35591,
                'area_id' => 350426,
                'name' => '尤溪县',
            ),
            59 => 
            array (
                'id' => 35618,
                'area_id' => 350427,
                'name' => '沙　县',
            ),
            60 => 
            array (
                'id' => 35645,
                'area_id' => 350428,
                'name' => '将乐县',
            ),
            61 => 
            array (
                'id' => 35672,
                'area_id' => 350429,
                'name' => '泰宁县',
            ),
            62 => 
            array (
                'id' => 35699,
                'area_id' => 350430,
                'name' => '建宁县',
            ),
            63 => 
            array (
                'id' => 35726,
                'area_id' => 350481,
                'name' => '永安市',
            ),
            64 => 
            array (
                'id' => 35740,
                'area_id' => 350501,
                'name' => '市辖区',
            ),
            65 => 
            array (
                'id' => 35767,
                'area_id' => 350502,
                'name' => '鲤城区',
            ),
            66 => 
            array (
                'id' => 35794,
                'area_id' => 350503,
                'name' => '丰泽区',
            ),
            67 => 
            array (
                'id' => 35821,
                'area_id' => 350504,
                'name' => '洛江区',
            ),
            68 => 
            array (
                'id' => 35848,
                'area_id' => 350505,
                'name' => '泉港区',
            ),
            69 => 
            array (
                'id' => 35875,
                'area_id' => 350521,
                'name' => '惠安县',
            ),
            70 => 
            array (
                'id' => 35902,
                'area_id' => 350524,
                'name' => '安溪县',
            ),
            71 => 
            array (
                'id' => 35929,
                'area_id' => 350525,
                'name' => '永春县',
            ),
            72 => 
            array (
                'id' => 35956,
                'area_id' => 350526,
                'name' => '德化县',
            ),
            73 => 
            array (
                'id' => 35983,
                'area_id' => 350527,
                'name' => '金门县',
            ),
            74 => 
            array (
                'id' => 36010,
                'area_id' => 350581,
                'name' => '石狮市',
            ),
            75 => 
            array (
                'id' => 36037,
                'area_id' => 350582,
                'name' => '晋江市',
            ),
            76 => 
            array (
                'id' => 36064,
                'area_id' => 350583,
                'name' => '南安市',
            ),
            77 => 
            array (
                'id' => 36078,
                'area_id' => 350601,
                'name' => '市辖区',
            ),
            78 => 
            array (
                'id' => 36105,
                'area_id' => 350602,
                'name' => '芗城区',
            ),
            79 => 
            array (
                'id' => 36132,
                'area_id' => 350603,
                'name' => '龙文区',
            ),
            80 => 
            array (
                'id' => 36159,
                'area_id' => 350622,
                'name' => '云霄县',
            ),
            81 => 
            array (
                'id' => 36186,
                'area_id' => 350623,
                'name' => '漳浦县',
            ),
            82 => 
            array (
                'id' => 36213,
                'area_id' => 350624,
                'name' => '诏安县',
            ),
            83 => 
            array (
                'id' => 36240,
                'area_id' => 350625,
                'name' => '长泰县',
            ),
            84 => 
            array (
                'id' => 36267,
                'area_id' => 350626,
                'name' => '东山县',
            ),
            85 => 
            array (
                'id' => 36294,
                'area_id' => 350627,
                'name' => '南靖县',
            ),
            86 => 
            array (
                'id' => 36321,
                'area_id' => 350628,
                'name' => '平和县',
            ),
            87 => 
            array (
                'id' => 36348,
                'area_id' => 350629,
                'name' => '华安县',
            ),
            88 => 
            array (
                'id' => 36375,
                'area_id' => 350681,
                'name' => '龙海市',
            ),
            89 => 
            array (
                'id' => 36390,
                'area_id' => 350701,
                'name' => '市辖区',
            ),
            90 => 
            array (
                'id' => 36417,
                'area_id' => 350702,
                'name' => '延平区',
            ),
            91 => 
            array (
                'id' => 36444,
                'area_id' => 350721,
                'name' => '顺昌县',
            ),
            92 => 
            array (
                'id' => 36471,
                'area_id' => 350722,
                'name' => '浦城县',
            ),
            93 => 
            array (
                'id' => 36498,
                'area_id' => 350723,
                'name' => '光泽县',
            ),
            94 => 
            array (
                'id' => 36525,
                'area_id' => 350724,
                'name' => '松溪县',
            ),
            95 => 
            array (
                'id' => 36552,
                'area_id' => 350725,
                'name' => '政和县',
            ),
            96 => 
            array (
                'id' => 36579,
                'area_id' => 350781,
                'name' => '邵武市',
            ),
            97 => 
            array (
                'id' => 36606,
                'area_id' => 350782,
                'name' => '武夷山市',
            ),
            98 => 
            array (
                'id' => 36633,
                'area_id' => 350783,
                'name' => '建瓯市',
            ),
            99 => 
            array (
                'id' => 36660,
                'area_id' => 350784,
                'name' => '建阳市',
            ),
            100 => 
            array (
                'id' => 36676,
                'area_id' => 350801,
                'name' => '市辖区',
            ),
            101 => 
            array (
                'id' => 36703,
                'area_id' => 350802,
                'name' => '新罗区',
            ),
            102 => 
            array (
                'id' => 36730,
                'area_id' => 350821,
                'name' => '长汀县',
            ),
            103 => 
            array (
                'id' => 36757,
                'area_id' => 350822,
                'name' => '永定县',
            ),
            104 => 
            array (
                'id' => 36784,
                'area_id' => 350823,
                'name' => '上杭县',
            ),
            105 => 
            array (
                'id' => 36811,
                'area_id' => 350824,
                'name' => '武平县',
            ),
            106 => 
            array (
                'id' => 36838,
                'area_id' => 350825,
                'name' => '连城县',
            ),
            107 => 
            array (
                'id' => 36865,
                'area_id' => 350881,
                'name' => '漳平市',
            ),
            108 => 
            array (
                'id' => 36884,
                'area_id' => 350901,
                'name' => '市辖区',
            ),
            109 => 
            array (
                'id' => 36911,
                'area_id' => 350902,
                'name' => '蕉城区',
            ),
            110 => 
            array (
                'id' => 36938,
                'area_id' => 350921,
                'name' => '霞浦县',
            ),
            111 => 
            array (
                'id' => 36965,
                'area_id' => 350922,
                'name' => '古田县',
            ),
            112 => 
            array (
                'id' => 36992,
                'area_id' => 350923,
                'name' => '屏南县',
            ),
            113 => 
            array (
                'id' => 37019,
                'area_id' => 350924,
                'name' => '寿宁县',
            ),
            114 => 
            array (
                'id' => 37046,
                'area_id' => 350925,
                'name' => '周宁县',
            ),
            115 => 
            array (
                'id' => 37073,
                'area_id' => 350926,
                'name' => '柘荣县',
            ),
            116 => 
            array (
                'id' => 37100,
                'area_id' => 350981,
                'name' => '福安市',
            ),
            117 => 
            array (
                'id' => 37127,
                'area_id' => 350982,
                'name' => '福鼎市',
            ),
            118 => 
            array (
                'id' => 37144,
                'area_id' => 360101,
                'name' => '市辖区',
            ),
            119 => 
            array (
                'id' => 37171,
                'area_id' => 360102,
                'name' => '东湖区',
            ),
            120 => 
            array (
                'id' => 37198,
                'area_id' => 360103,
                'name' => '西湖区',
            ),
            121 => 
            array (
                'id' => 37225,
                'area_id' => 360104,
                'name' => '青云谱区',
            ),
            122 => 
            array (
                'id' => 37252,
                'area_id' => 360105,
                'name' => '湾里区',
            ),
            123 => 
            array (
                'id' => 37279,
                'area_id' => 360111,
                'name' => '青山湖区',
            ),
            124 => 
            array (
                'id' => 37306,
                'area_id' => 360121,
                'name' => '南昌县',
            ),
            125 => 
            array (
                'id' => 37333,
                'area_id' => 360122,
                'name' => '新建县',
            ),
            126 => 
            array (
                'id' => 37360,
                'area_id' => 360123,
                'name' => '安义县',
            ),
            127 => 
            array (
                'id' => 37387,
                'area_id' => 360124,
                'name' => '进贤县',
            ),
            128 => 
            array (
                'id' => 37404,
                'area_id' => 360201,
                'name' => '市辖区',
            ),
            129 => 
            array (
                'id' => 37431,
                'area_id' => 360202,
                'name' => '昌江区',
            ),
            130 => 
            array (
                'id' => 37458,
                'area_id' => 360203,
                'name' => '珠山区',
            ),
            131 => 
            array (
                'id' => 37485,
                'area_id' => 360222,
                'name' => '浮梁县',
            ),
            132 => 
            array (
                'id' => 37512,
                'area_id' => 360281,
                'name' => '乐平市',
            ),
            133 => 
            array (
                'id' => 37534,
                'area_id' => 360301,
                'name' => '市辖区',
            ),
            134 => 
            array (
                'id' => 37561,
                'area_id' => 360302,
                'name' => '安源区',
            ),
            135 => 
            array (
                'id' => 37588,
                'area_id' => 360313,
                'name' => '湘东区',
            ),
            136 => 
            array (
                'id' => 37615,
                'area_id' => 360321,
                'name' => '莲花县',
            ),
            137 => 
            array (
                'id' => 37642,
                'area_id' => 360322,
                'name' => '上栗县',
            ),
            138 => 
            array (
                'id' => 37669,
                'area_id' => 360323,
                'name' => '芦溪县',
            ),
            139 => 
            array (
                'id' => 37690,
                'area_id' => 360401,
                'name' => '市辖区',
            ),
            140 => 
            array (
                'id' => 37717,
                'area_id' => 360402,
                'name' => '庐山区',
            ),
            141 => 
            array (
                'id' => 37744,
                'area_id' => 360403,
                'name' => '浔阳区',
            ),
            142 => 
            array (
                'id' => 37771,
                'area_id' => 360421,
                'name' => '九江县',
            ),
            143 => 
            array (
                'id' => 37798,
                'area_id' => 360423,
                'name' => '武宁县',
            ),
            144 => 
            array (
                'id' => 37825,
                'area_id' => 360424,
                'name' => '修水县',
            ),
            145 => 
            array (
                'id' => 37852,
                'area_id' => 360425,
                'name' => '永修县',
            ),
            146 => 
            array (
                'id' => 37879,
                'area_id' => 360426,
                'name' => '德安县',
            ),
            147 => 
            array (
                'id' => 37906,
                'area_id' => 360427,
                'name' => '星子县',
            ),
            148 => 
            array (
                'id' => 37933,
                'area_id' => 360428,
                'name' => '都昌县',
            ),
            149 => 
            array (
                'id' => 37960,
                'area_id' => 360429,
                'name' => '湖口县',
            ),
            150 => 
            array (
                'id' => 37987,
                'area_id' => 360430,
                'name' => '彭泽县',
            ),
            151 => 
            array (
                'id' => 38014,
                'area_id' => 360481,
                'name' => '瑞昌市',
            ),
            152 => 
            array (
                'id' => 38028,
                'area_id' => 360501,
                'name' => '市辖区',
            ),
            153 => 
            array (
                'id' => 38055,
                'area_id' => 360502,
                'name' => '渝水区',
            ),
            154 => 
            array (
                'id' => 38082,
                'area_id' => 360521,
                'name' => '分宜县',
            ),
            155 => 
            array (
                'id' => 38106,
                'area_id' => 360601,
                'name' => '市辖区',
            ),
            156 => 
            array (
                'id' => 38133,
                'area_id' => 360602,
                'name' => '月湖区',
            ),
            157 => 
            array (
                'id' => 38160,
                'area_id' => 360622,
                'name' => '余江县',
            ),
            158 => 
            array (
                'id' => 38187,
                'area_id' => 360681,
                'name' => '贵溪市',
            ),
            159 => 
            array (
                'id' => 38210,
                'area_id' => 360701,
                'name' => '市辖区',
            ),
            160 => 
            array (
                'id' => 38237,
                'area_id' => 360702,
                'name' => '章贡区',
            ),
            161 => 
            array (
                'id' => 38264,
                'area_id' => 360721,
                'name' => '赣　县',
            ),
            162 => 
            array (
                'id' => 38291,
                'area_id' => 360722,
                'name' => '信丰县',
            ),
            163 => 
            array (
                'id' => 38318,
                'area_id' => 360723,
                'name' => '大余县',
            ),
            164 => 
            array (
                'id' => 38345,
                'area_id' => 360724,
                'name' => '上犹县',
            ),
            165 => 
            array (
                'id' => 38372,
                'area_id' => 360725,
                'name' => '崇义县',
            ),
            166 => 
            array (
                'id' => 38399,
                'area_id' => 360726,
                'name' => '安远县',
            ),
            167 => 
            array (
                'id' => 38426,
                'area_id' => 360727,
                'name' => '龙南县',
            ),
            168 => 
            array (
                'id' => 38453,
                'area_id' => 360728,
                'name' => '定南县',
            ),
            169 => 
            array (
                'id' => 38480,
                'area_id' => 360729,
                'name' => '全南县',
            ),
            170 => 
            array (
                'id' => 38507,
                'area_id' => 360730,
                'name' => '宁都县',
            ),
            171 => 
            array (
                'id' => 38534,
                'area_id' => 360731,
                'name' => '于都县',
            ),
            172 => 
            array (
                'id' => 38561,
                'area_id' => 360732,
                'name' => '兴国县',
            ),
            173 => 
            array (
                'id' => 38588,
                'area_id' => 360733,
                'name' => '会昌县',
            ),
            174 => 
            array (
                'id' => 38615,
                'area_id' => 360734,
                'name' => '寻乌县',
            ),
            175 => 
            array (
                'id' => 38642,
                'area_id' => 360735,
                'name' => '石城县',
            ),
            176 => 
            array (
                'id' => 38669,
                'area_id' => 360781,
                'name' => '瑞金市',
            ),
            177 => 
            array (
                'id' => 38696,
                'area_id' => 360782,
                'name' => '南康市',
            ),
            178 => 
            array (
                'id' => 38704,
                'area_id' => 360801,
                'name' => '市辖区',
            ),
            179 => 
            array (
                'id' => 38731,
                'area_id' => 360802,
                'name' => '吉州区',
            ),
            180 => 
            array (
                'id' => 38758,
                'area_id' => 360803,
                'name' => '青原区',
            ),
            181 => 
            array (
                'id' => 38785,
                'area_id' => 360821,
                'name' => '吉安县',
            ),
            182 => 
            array (
                'id' => 38812,
                'area_id' => 360822,
                'name' => '吉水县',
            ),
            183 => 
            array (
                'id' => 38839,
                'area_id' => 360823,
                'name' => '峡江县',
            ),
            184 => 
            array (
                'id' => 38866,
                'area_id' => 360824,
                'name' => '新干县',
            ),
            185 => 
            array (
                'id' => 38893,
                'area_id' => 360825,
                'name' => '永丰县',
            ),
            186 => 
            array (
                'id' => 38920,
                'area_id' => 360826,
                'name' => '泰和县',
            ),
            187 => 
            array (
                'id' => 38947,
                'area_id' => 360827,
                'name' => '遂川县',
            ),
            188 => 
            array (
                'id' => 38974,
                'area_id' => 360828,
                'name' => '万安县',
            ),
            189 => 
            array (
                'id' => 39001,
                'area_id' => 360829,
                'name' => '安福县',
            ),
            190 => 
            array (
                'id' => 39028,
                'area_id' => 360830,
                'name' => '永新县',
            ),
            191 => 
            array (
                'id' => 39055,
                'area_id' => 360881,
                'name' => '井冈山市',
            ),
            192 => 
            array (
                'id' => 39068,
                'area_id' => 360901,
                'name' => '市辖区',
            ),
            193 => 
            array (
                'id' => 39095,
                'area_id' => 360902,
                'name' => '袁州区',
            ),
            194 => 
            array (
                'id' => 39122,
                'area_id' => 360921,
                'name' => '奉新县',
            ),
            195 => 
            array (
                'id' => 39149,
                'area_id' => 360922,
                'name' => '万载县',
            ),
            196 => 
            array (
                'id' => 39176,
                'area_id' => 360923,
                'name' => '上高县',
            ),
            197 => 
            array (
                'id' => 39203,
                'area_id' => 360924,
                'name' => '宜丰县',
            ),
            198 => 
            array (
                'id' => 39230,
                'area_id' => 360925,
                'name' => '靖安县',
            ),
            199 => 
            array (
                'id' => 39257,
                'area_id' => 360926,
                'name' => '铜鼓县',
            ),
            200 => 
            array (
                'id' => 39284,
                'area_id' => 360981,
                'name' => '丰城市',
            ),
            201 => 
            array (
                'id' => 39311,
                'area_id' => 360982,
                'name' => '樟树市',
            ),
            202 => 
            array (
                'id' => 39338,
                'area_id' => 360983,
                'name' => '高安市',
            ),
            203 => 
            array (
                'id' => 39354,
                'area_id' => 361001,
                'name' => '市辖区',
            ),
            204 => 
            array (
                'id' => 39381,
                'area_id' => 361002,
                'name' => '临川区',
            ),
            205 => 
            array (
                'id' => 39408,
                'area_id' => 361021,
                'name' => '南城县',
            ),
            206 => 
            array (
                'id' => 39435,
                'area_id' => 361022,
                'name' => '黎川县',
            ),
            207 => 
            array (
                'id' => 39462,
                'area_id' => 361023,
                'name' => '南丰县',
            ),
            208 => 
            array (
                'id' => 39489,
                'area_id' => 361024,
                'name' => '崇仁县',
            ),
            209 => 
            array (
                'id' => 39516,
                'area_id' => 361025,
                'name' => '乐安县',
            ),
            210 => 
            array (
                'id' => 39543,
                'area_id' => 361026,
                'name' => '宜黄县',
            ),
            211 => 
            array (
                'id' => 39570,
                'area_id' => 361027,
                'name' => '金溪县',
            ),
            212 => 
            array (
                'id' => 39597,
                'area_id' => 361028,
                'name' => '资溪县',
            ),
            213 => 
            array (
                'id' => 39624,
                'area_id' => 361029,
                'name' => '东乡县',
            ),
            214 => 
            array (
                'id' => 39651,
                'area_id' => 361030,
                'name' => '广昌县',
            ),
            215 => 
            array (
                'id' => 39666,
                'area_id' => 361101,
                'name' => '市辖区',
            ),
            216 => 
            array (
                'id' => 39693,
                'area_id' => 361102,
                'name' => '信州区',
            ),
            217 => 
            array (
                'id' => 39720,
                'area_id' => 361121,
                'name' => '上饶县',
            ),
            218 => 
            array (
                'id' => 39747,
                'area_id' => 361122,
                'name' => '广丰县',
            ),
            219 => 
            array (
                'id' => 39774,
                'area_id' => 361123,
                'name' => '玉山县',
            ),
            220 => 
            array (
                'id' => 39801,
                'area_id' => 361124,
                'name' => '铅山县',
            ),
            221 => 
            array (
                'id' => 39828,
                'area_id' => 361125,
                'name' => '横峰县',
            ),
            222 => 
            array (
                'id' => 39855,
                'area_id' => 361126,
                'name' => '弋阳县',
            ),
            223 => 
            array (
                'id' => 39882,
                'area_id' => 361127,
                'name' => '余干县',
            ),
            224 => 
            array (
                'id' => 39909,
                'area_id' => 361128,
                'name' => '鄱阳县',
            ),
            225 => 
            array (
                'id' => 39936,
                'area_id' => 361129,
                'name' => '万年县',
            ),
            226 => 
            array (
                'id' => 39963,
                'area_id' => 361130,
                'name' => '婺源县',
            ),
            227 => 
            array (
                'id' => 39990,
                'area_id' => 361181,
                'name' => '德兴市',
            ),
            228 => 
            array (
                'id' => 40004,
                'area_id' => 370101,
                'name' => '市辖区',
            ),
            229 => 
            array (
                'id' => 40031,
                'area_id' => 370102,
                'name' => '历下区',
            ),
            230 => 
            array (
                'id' => 40058,
                'area_id' => 370103,
                'name' => '市中区',
            ),
            231 => 
            array (
                'id' => 40085,
                'area_id' => 370104,
                'name' => '槐荫区',
            ),
            232 => 
            array (
                'id' => 40112,
                'area_id' => 370105,
                'name' => '天桥区',
            ),
            233 => 
            array (
                'id' => 40139,
                'area_id' => 370112,
                'name' => '历城区',
            ),
            234 => 
            array (
                'id' => 40166,
                'area_id' => 370113,
                'name' => '长清区',
            ),
            235 => 
            array (
                'id' => 40193,
                'area_id' => 370124,
                'name' => '平阴县',
            ),
            236 => 
            array (
                'id' => 40220,
                'area_id' => 370125,
                'name' => '济阳县',
            ),
            237 => 
            array (
                'id' => 40247,
                'area_id' => 370126,
                'name' => '商河县',
            ),
            238 => 
            array (
                'id' => 40274,
                'area_id' => 370181,
                'name' => '章丘市',
            ),
            239 => 
            array (
                'id' => 40290,
                'area_id' => 370201,
                'name' => '市辖区',
            ),
            240 => 
            array (
                'id' => 40317,
                'area_id' => 370202,
                'name' => '市南区',
            ),
            241 => 
            array (
                'id' => 40344,
                'area_id' => 370203,
                'name' => '市北区',
            ),
            242 => 
            array (
                'id' => 40371,
                'area_id' => 370205,
                'name' => '四方区',
            ),
            243 => 
            array (
                'id' => 40398,
                'area_id' => 370211,
                'name' => '黄岛区',
            ),
            244 => 
            array (
                'id' => 40425,
                'area_id' => 370212,
                'name' => '崂山区',
            ),
            245 => 
            array (
                'id' => 40452,
                'area_id' => 370213,
                'name' => '李沧区',
            ),
            246 => 
            array (
                'id' => 40479,
                'area_id' => 370214,
                'name' => '城阳区',
            ),
            247 => 
            array (
                'id' => 40506,
                'area_id' => 370281,
                'name' => '胶州市',
            ),
            248 => 
            array (
                'id' => 40533,
                'area_id' => 370282,
                'name' => '即墨市',
            ),
            249 => 
            array (
                'id' => 40560,
                'area_id' => 370283,
                'name' => '平度市',
            ),
            250 => 
            array (
                'id' => 40587,
                'area_id' => 370284,
                'name' => '胶南市',
            ),
            251 => 
            array (
                'id' => 40614,
                'area_id' => 370285,
                'name' => '莱西市',
            ),
            252 => 
            array (
                'id' => 40628,
                'area_id' => 370301,
                'name' => '市辖区',
            ),
            253 => 
            array (
                'id' => 40655,
                'area_id' => 370302,
                'name' => '淄川区',
            ),
            254 => 
            array (
                'id' => 40682,
                'area_id' => 370303,
                'name' => '张店区',
            ),
            255 => 
            array (
                'id' => 40709,
                'area_id' => 370304,
                'name' => '博山区',
            ),
            256 => 
            array (
                'id' => 40736,
                'area_id' => 370305,
                'name' => '临淄区',
            ),
            257 => 
            array (
                'id' => 40763,
                'area_id' => 370306,
                'name' => '周村区',
            ),
            258 => 
            array (
                'id' => 40790,
                'area_id' => 370321,
                'name' => '桓台县',
            ),
            259 => 
            array (
                'id' => 40817,
                'area_id' => 370322,
                'name' => '高青县',
            ),
            260 => 
            array (
                'id' => 40844,
                'area_id' => 370323,
                'name' => '沂源县',
            ),
            261 => 
            array (
                'id' => 40862,
                'area_id' => 370401,
                'name' => '市辖区',
            ),
            262 => 
            array (
                'id' => 40889,
                'area_id' => 370402,
                'name' => '市中区',
            ),
            263 => 
            array (
                'id' => 40916,
                'area_id' => 370403,
                'name' => '薛城区',
            ),
            264 => 
            array (
                'id' => 40943,
                'area_id' => 370404,
                'name' => '峄城区',
            ),
            265 => 
            array (
                'id' => 40970,
                'area_id' => 370405,
                'name' => '台儿庄区',
            ),
            266 => 
            array (
                'id' => 40997,
                'area_id' => 370406,
                'name' => '山亭区',
            ),
            267 => 
            array (
                'id' => 41024,
                'area_id' => 370481,
                'name' => '滕州市',
            ),
            268 => 
            array (
                'id' => 41044,
                'area_id' => 370501,
                'name' => '市辖区',
            ),
            269 => 
            array (
                'id' => 41071,
                'area_id' => 370502,
                'name' => '东营区',
            ),
            270 => 
            array (
                'id' => 41098,
                'area_id' => 370503,
                'name' => '河口区',
            ),
            271 => 
            array (
                'id' => 41125,
                'area_id' => 370521,
                'name' => '垦利县',
            ),
            272 => 
            array (
                'id' => 41152,
                'area_id' => 370522,
                'name' => '利津县',
            ),
            273 => 
            array (
                'id' => 41179,
                'area_id' => 370523,
                'name' => '广饶县',
            ),
            274 => 
            array (
                'id' => 41200,
                'area_id' => 370601,
                'name' => '市辖区',
            ),
            275 => 
            array (
                'id' => 41227,
                'area_id' => 370602,
                'name' => '芝罘区',
            ),
            276 => 
            array (
                'id' => 41254,
                'area_id' => 370611,
                'name' => '福山区',
            ),
            277 => 
            array (
                'id' => 41281,
                'area_id' => 370612,
                'name' => '牟平区',
            ),
            278 => 
            array (
                'id' => 41308,
                'area_id' => 370613,
                'name' => '莱山区',
            ),
            279 => 
            array (
                'id' => 41335,
                'area_id' => 370634,
                'name' => '长岛县',
            ),
            280 => 
            array (
                'id' => 41362,
                'area_id' => 370681,
                'name' => '龙口市',
            ),
            281 => 
            array (
                'id' => 41389,
                'area_id' => 370682,
                'name' => '莱阳市',
            ),
            282 => 
            array (
                'id' => 41416,
                'area_id' => 370683,
                'name' => '莱州市',
            ),
            283 => 
            array (
                'id' => 41443,
                'area_id' => 370684,
                'name' => '蓬莱市',
            ),
            284 => 
            array (
                'id' => 41470,
                'area_id' => 370685,
                'name' => '招远市',
            ),
            285 => 
            array (
                'id' => 41497,
                'area_id' => 370686,
                'name' => '栖霞市',
            ),
            286 => 
            array (
                'id' => 41524,
                'area_id' => 370687,
                'name' => '海阳市',
            ),
            287 => 
            array (
                'id' => 41538,
                'area_id' => 370701,
                'name' => '市辖区',
            ),
            288 => 
            array (
                'id' => 41565,
                'area_id' => 370702,
                'name' => '潍城区',
            ),
            289 => 
            array (
                'id' => 41592,
                'area_id' => 370703,
                'name' => '寒亭区',
            ),
            290 => 
            array (
                'id' => 41619,
                'area_id' => 370704,
                'name' => '坊子区',
            ),
            291 => 
            array (
                'id' => 41646,
                'area_id' => 370705,
                'name' => '奎文区',
            ),
            292 => 
            array (
                'id' => 41673,
                'area_id' => 370724,
                'name' => '临朐县',
            ),
            293 => 
            array (
                'id' => 41700,
                'area_id' => 370725,
                'name' => '昌乐县',
            ),
            294 => 
            array (
                'id' => 41727,
                'area_id' => 370781,
                'name' => '青州市',
            ),
            295 => 
            array (
                'id' => 41754,
                'area_id' => 370782,
                'name' => '诸城市',
            ),
            296 => 
            array (
                'id' => 41781,
                'area_id' => 370783,
                'name' => '寿光市',
            ),
            297 => 
            array (
                'id' => 41808,
                'area_id' => 370784,
                'name' => '安丘市',
            ),
            298 => 
            array (
                'id' => 41835,
                'area_id' => 370785,
                'name' => '高密市',
            ),
            299 => 
            array (
                'id' => 41862,
                'area_id' => 370786,
                'name' => '昌邑市',
            ),
            300 => 
            array (
                'id' => 41876,
                'area_id' => 370801,
                'name' => '市辖区',
            ),
            301 => 
            array (
                'id' => 41903,
                'area_id' => 370802,
                'name' => '市中区',
            ),
            302 => 
            array (
                'id' => 41930,
                'area_id' => 370811,
                'name' => '任城区',
            ),
            303 => 
            array (
                'id' => 41957,
                'area_id' => 370826,
                'name' => '微山县',
            ),
            304 => 
            array (
                'id' => 41984,
                'area_id' => 370827,
                'name' => '鱼台县',
            ),
            305 => 
            array (
                'id' => 42011,
                'area_id' => 370828,
                'name' => '金乡县',
            ),
            306 => 
            array (
                'id' => 42038,
                'area_id' => 370829,
                'name' => '嘉祥县',
            ),
            307 => 
            array (
                'id' => 42065,
                'area_id' => 370830,
                'name' => '汶上县',
            ),
            308 => 
            array (
                'id' => 42092,
                'area_id' => 370831,
                'name' => '泗水县',
            ),
            309 => 
            array (
                'id' => 42119,
                'area_id' => 370832,
                'name' => '梁山县',
            ),
            310 => 
            array (
                'id' => 42146,
                'area_id' => 370881,
                'name' => '曲阜市',
            ),
            311 => 
            array (
                'id' => 42173,
                'area_id' => 370882,
                'name' => '兖州市',
            ),
            312 => 
            array (
                'id' => 42200,
                'area_id' => 370883,
                'name' => '邹城市',
            ),
            313 => 
            array (
                'id' => 42214,
                'area_id' => 370901,
                'name' => '市辖区',
            ),
            314 => 
            array (
                'id' => 42241,
                'area_id' => 370902,
                'name' => '泰山区',
            ),
            315 => 
            array (
                'id' => 42268,
                'area_id' => 370903,
                'name' => '岱岳区',
            ),
            316 => 
            array (
                'id' => 42295,
                'area_id' => 370921,
                'name' => '宁阳县',
            ),
            317 => 
            array (
                'id' => 42322,
                'area_id' => 370923,
                'name' => '东平县',
            ),
            318 => 
            array (
                'id' => 42349,
                'area_id' => 370982,
                'name' => '新泰市',
            ),
            319 => 
            array (
                'id' => 42376,
                'area_id' => 370983,
                'name' => '肥城市',
            ),
            320 => 
            array (
                'id' => 42396,
                'area_id' => 371001,
                'name' => '市辖区',
            ),
            321 => 
            array (
                'id' => 42423,
                'area_id' => 371002,
                'name' => '环翠区',
            ),
            322 => 
            array (
                'id' => 42450,
                'area_id' => 371081,
                'name' => '文登市',
            ),
            323 => 
            array (
                'id' => 42477,
                'area_id' => 371082,
                'name' => '荣成市',
            ),
            324 => 
            array (
                'id' => 42504,
                'area_id' => 371083,
                'name' => '乳山市',
            ),
            325 => 
            array (
                'id' => 42526,
                'area_id' => 371101,
                'name' => '市辖区',
            ),
            326 => 
            array (
                'id' => 42553,
                'area_id' => 371102,
                'name' => '东港区',
            ),
            327 => 
            array (
                'id' => 42580,
                'area_id' => 371103,
                'name' => '岚山区',
            ),
            328 => 
            array (
                'id' => 42607,
                'area_id' => 371121,
                'name' => '五莲县',
            ),
            329 => 
            array (
                'id' => 42634,
                'area_id' => 371122,
                'name' => '莒　县',
            ),
            330 => 
            array (
                'id' => 42656,
                'area_id' => 371201,
                'name' => '市辖区',
            ),
            331 => 
            array (
                'id' => 42683,
                'area_id' => 371202,
                'name' => '莱城区',
            ),
            332 => 
            array (
                'id' => 42710,
                'area_id' => 371203,
                'name' => '钢城区',
            ),
            333 => 
            array (
                'id' => 42734,
                'area_id' => 371301,
                'name' => '市辖区',
            ),
            334 => 
            array (
                'id' => 42761,
                'area_id' => 371302,
                'name' => '兰山区',
            ),
            335 => 
            array (
                'id' => 42788,
                'area_id' => 371311,
                'name' => '罗庄区',
            ),
            336 => 
            array (
                'id' => 42815,
                'area_id' => 371312,
                'name' => '河东区',
            ),
            337 => 
            array (
                'id' => 42842,
                'area_id' => 371321,
                'name' => '沂南县',
            ),
            338 => 
            array (
                'id' => 42869,
                'area_id' => 371322,
                'name' => '郯城县',
            ),
            339 => 
            array (
                'id' => 42896,
                'area_id' => 371323,
                'name' => '沂水县',
            ),
            340 => 
            array (
                'id' => 42923,
                'area_id' => 371324,
                'name' => '苍山县',
            ),
            341 => 
            array (
                'id' => 42950,
                'area_id' => 371325,
                'name' => '费　县',
            ),
            342 => 
            array (
                'id' => 42977,
                'area_id' => 371326,
                'name' => '平邑县',
            ),
            343 => 
            array (
                'id' => 43004,
                'area_id' => 371327,
                'name' => '莒南县',
            ),
            344 => 
            array (
                'id' => 43031,
                'area_id' => 371328,
                'name' => '蒙阴县',
            ),
            345 => 
            array (
                'id' => 43058,
                'area_id' => 371329,
                'name' => '临沭县',
            ),
            346 => 
            array (
                'id' => 43072,
                'area_id' => 371401,
                'name' => '市辖区',
            ),
            347 => 
            array (
                'id' => 43099,
                'area_id' => 371402,
                'name' => '德城区',
            ),
            348 => 
            array (
                'id' => 43126,
                'area_id' => 371421,
                'name' => '陵　县',
            ),
            349 => 
            array (
                'id' => 43153,
                'area_id' => 371422,
                'name' => '宁津县',
            ),
            350 => 
            array (
                'id' => 43180,
                'area_id' => 371423,
                'name' => '庆云县',
            ),
            351 => 
            array (
                'id' => 43207,
                'area_id' => 371424,
                'name' => '临邑县',
            ),
            352 => 
            array (
                'id' => 43234,
                'area_id' => 371425,
                'name' => '齐河县',
            ),
            353 => 
            array (
                'id' => 43261,
                'area_id' => 371426,
                'name' => '平原县',
            ),
            354 => 
            array (
                'id' => 43288,
                'area_id' => 371427,
                'name' => '夏津县',
            ),
            355 => 
            array (
                'id' => 43315,
                'area_id' => 371428,
                'name' => '武城县',
            ),
            356 => 
            array (
                'id' => 43342,
                'area_id' => 371481,
                'name' => '乐陵市',
            ),
            357 => 
            array (
                'id' => 43369,
                'area_id' => 371482,
                'name' => '禹城市',
            ),
            358 => 
            array (
                'id' => 43384,
                'area_id' => 371501,
                'name' => '市辖区',
            ),
            359 => 
            array (
                'id' => 43411,
                'area_id' => 371502,
                'name' => '东昌府区',
            ),
            360 => 
            array (
                'id' => 43438,
                'area_id' => 371521,
                'name' => '阳谷县',
            ),
            361 => 
            array (
                'id' => 43465,
                'area_id' => 371522,
                'name' => '莘　县',
            ),
            362 => 
            array (
                'id' => 43492,
                'area_id' => 371523,
                'name' => '茌平县',
            ),
            363 => 
            array (
                'id' => 43519,
                'area_id' => 371524,
                'name' => '东阿县',
            ),
            364 => 
            array (
                'id' => 43546,
                'area_id' => 371525,
                'name' => '冠　县',
            ),
            365 => 
            array (
                'id' => 43573,
                'area_id' => 371526,
                'name' => '高唐县',
            ),
            366 => 
            array (
                'id' => 43600,
                'area_id' => 371581,
                'name' => '临清市',
            ),
            367 => 
            array (
                'id' => 43618,
                'area_id' => 371601,
                'name' => '市辖区',
            ),
            368 => 
            array (
                'id' => 43645,
                'area_id' => 371602,
                'name' => '滨城区',
            ),
            369 => 
            array (
                'id' => 43672,
                'area_id' => 371621,
                'name' => '惠民县',
            ),
            370 => 
            array (
                'id' => 43699,
                'area_id' => 371622,
                'name' => '阳信县',
            ),
            371 => 
            array (
                'id' => 43726,
                'area_id' => 371623,
                'name' => '无棣县',
            ),
            372 => 
            array (
                'id' => 43753,
                'area_id' => 371624,
                'name' => '沾化县',
            ),
            373 => 
            array (
                'id' => 43780,
                'area_id' => 371625,
                'name' => '博兴县',
            ),
            374 => 
            array (
                'id' => 43807,
                'area_id' => 371626,
                'name' => '邹平县',
            ),
            375 => 
            array (
                'id' => 43826,
                'area_id' => 371701,
                'name' => '市辖区',
            ),
            376 => 
            array (
                'id' => 43853,
                'area_id' => 371702,
                'name' => '牡丹区',
            ),
            377 => 
            array (
                'id' => 43880,
                'area_id' => 371721,
                'name' => '曹　县',
            ),
            378 => 
            array (
                'id' => 43907,
                'area_id' => 371722,
                'name' => '单　县',
            ),
            379 => 
            array (
                'id' => 43934,
                'area_id' => 371723,
                'name' => '成武县',
            ),
            380 => 
            array (
                'id' => 43961,
                'area_id' => 371724,
                'name' => '巨野县',
            ),
            381 => 
            array (
                'id' => 43988,
                'area_id' => 371725,
                'name' => '郓城县',
            ),
            382 => 
            array (
                'id' => 44015,
                'area_id' => 371726,
                'name' => '鄄城县',
            ),
            383 => 
            array (
                'id' => 44042,
                'area_id' => 371727,
                'name' => '定陶县',
            ),
            384 => 
            array (
                'id' => 44069,
                'area_id' => 371728,
                'name' => '东明县',
            ),
            385 => 
            array (
                'id' => 44086,
                'area_id' => 410101,
                'name' => '市辖区',
            ),
            386 => 
            array (
                'id' => 44113,
                'area_id' => 410102,
                'name' => '中原区',
            ),
            387 => 
            array (
                'id' => 44140,
                'area_id' => 410103,
                'name' => '二七区',
            ),
            388 => 
            array (
                'id' => 44167,
                'area_id' => 410104,
                'name' => '管城回族区',
            ),
            389 => 
            array (
                'id' => 44194,
                'area_id' => 410105,
                'name' => '金水区',
            ),
            390 => 
            array (
                'id' => 44221,
                'area_id' => 410106,
                'name' => '上街区',
            ),
            391 => 
            array (
                'id' => 44248,
                'area_id' => 410108,
                'name' => '邙山区',
            ),
            392 => 
            array (
                'id' => 44275,
                'area_id' => 410122,
                'name' => '中牟县',
            ),
            393 => 
            array (
                'id' => 44302,
                'area_id' => 410181,
                'name' => '巩义市',
            ),
            394 => 
            array (
                'id' => 44329,
                'area_id' => 410182,
                'name' => '荥阳市',
            ),
            395 => 
            array (
                'id' => 44356,
                'area_id' => 410183,
                'name' => '新密市',
            ),
            396 => 
            array (
                'id' => 44383,
                'area_id' => 410184,
                'name' => '新郑市',
            ),
            397 => 
            array (
                'id' => 44410,
                'area_id' => 410185,
                'name' => '登封市',
            ),
            398 => 
            array (
                'id' => 44424,
                'area_id' => 410201,
                'name' => '市辖区',
            ),
            399 => 
            array (
                'id' => 44451,
                'area_id' => 410202,
                'name' => '龙亭区',
            ),
            400 => 
            array (
                'id' => 44478,
                'area_id' => 410203,
                'name' => '顺河回族区',
            ),
            401 => 
            array (
                'id' => 44505,
                'area_id' => 410204,
                'name' => '鼓楼区',
            ),
            402 => 
            array (
                'id' => 44532,
                'area_id' => 410205,
                'name' => '南关区',
            ),
            403 => 
            array (
                'id' => 44559,
                'area_id' => 410211,
                'name' => '郊　区',
            ),
            404 => 
            array (
                'id' => 44586,
                'area_id' => 410221,
                'name' => '杞　县',
            ),
            405 => 
            array (
                'id' => 44613,
                'area_id' => 410222,
                'name' => '通许县',
            ),
            406 => 
            array (
                'id' => 44640,
                'area_id' => 410223,
                'name' => '尉氏县',
            ),
            407 => 
            array (
                'id' => 44667,
                'area_id' => 410224,
                'name' => '开封县',
            ),
            408 => 
            array (
                'id' => 44694,
                'area_id' => 410225,
                'name' => '兰考县',
            ),
            409 => 
            array (
                'id' => 44710,
                'area_id' => 410301,
                'name' => '市辖区',
            ),
            410 => 
            array (
                'id' => 44737,
                'area_id' => 410302,
                'name' => '老城区',
            ),
            411 => 
            array (
                'id' => 44764,
                'area_id' => 410303,
                'name' => '西工区',
            ),
            412 => 
            array (
                'id' => 44791,
                'area_id' => 410304,
                'name' => '廛河回族区',
            ),
            413 => 
            array (
                'id' => 44818,
                'area_id' => 410305,
                'name' => '涧西区',
            ),
            414 => 
            array (
                'id' => 44845,
                'area_id' => 410306,
                'name' => '吉利区',
            ),
            415 => 
            array (
                'id' => 44872,
                'area_id' => 410307,
                'name' => '洛龙区',
            ),
            416 => 
            array (
                'id' => 44899,
                'area_id' => 410322,
                'name' => '孟津县',
            ),
            417 => 
            array (
                'id' => 44926,
                'area_id' => 410323,
                'name' => '新安县',
            ),
            418 => 
            array (
                'id' => 44953,
                'area_id' => 410324,
                'name' => '栾川县',
            ),
            419 => 
            array (
                'id' => 44980,
                'area_id' => 410325,
                'name' => '嵩　县',
            ),
            420 => 
            array (
                'id' => 45007,
                'area_id' => 410326,
                'name' => '汝阳县',
            ),
            421 => 
            array (
                'id' => 45034,
                'area_id' => 410327,
                'name' => '宜阳县',
            ),
            422 => 
            array (
                'id' => 45061,
                'area_id' => 410328,
                'name' => '洛宁县',
            ),
            423 => 
            array (
                'id' => 45088,
                'area_id' => 410329,
                'name' => '伊川县',
            ),
            424 => 
            array (
                'id' => 45115,
                'area_id' => 410381,
                'name' => '偃师市',
            ),
            425 => 
            array (
                'id' => 45126,
                'area_id' => 410401,
                'name' => '市辖区',
            ),
            426 => 
            array (
                'id' => 45153,
                'area_id' => 410402,
                'name' => '新华区',
            ),
            427 => 
            array (
                'id' => 45180,
                'area_id' => 410403,
                'name' => '卫东区',
            ),
            428 => 
            array (
                'id' => 45207,
                'area_id' => 410404,
                'name' => '石龙区',
            ),
            429 => 
            array (
                'id' => 45234,
                'area_id' => 410411,
                'name' => '湛河区',
            ),
            430 => 
            array (
                'id' => 45261,
                'area_id' => 410421,
                'name' => '宝丰县',
            ),
            431 => 
            array (
                'id' => 45288,
                'area_id' => 410422,
                'name' => '叶　县',
            ),
            432 => 
            array (
                'id' => 45315,
                'area_id' => 410423,
                'name' => '鲁山县',
            ),
            433 => 
            array (
                'id' => 45342,
                'area_id' => 410425,
                'name' => '郏　县',
            ),
            434 => 
            array (
                'id' => 45369,
                'area_id' => 410481,
                'name' => '舞钢市',
            ),
            435 => 
            array (
                'id' => 45396,
                'area_id' => 410482,
                'name' => '汝州市',
            ),
            436 => 
            array (
                'id' => 45412,
                'area_id' => 410501,
                'name' => '市辖区',
            ),
            437 => 
            array (
                'id' => 45439,
                'area_id' => 410502,
                'name' => '文峰区',
            ),
            438 => 
            array (
                'id' => 45466,
                'area_id' => 410503,
                'name' => '北关区',
            ),
            439 => 
            array (
                'id' => 45493,
                'area_id' => 410505,
                'name' => '殷都区',
            ),
            440 => 
            array (
                'id' => 45520,
                'area_id' => 410506,
                'name' => '龙安区',
            ),
            441 => 
            array (
                'id' => 45547,
                'area_id' => 410522,
                'name' => '安阳县',
            ),
            442 => 
            array (
                'id' => 45574,
                'area_id' => 410523,
                'name' => '汤阴县',
            ),
            443 => 
            array (
                'id' => 45601,
                'area_id' => 410526,
                'name' => '滑　县',
            ),
            444 => 
            array (
                'id' => 45628,
                'area_id' => 410527,
                'name' => '内黄县',
            ),
            445 => 
            array (
                'id' => 45655,
                'area_id' => 410581,
                'name' => '林州市',
            ),
            446 => 
            array (
                'id' => 45672,
                'area_id' => 410601,
                'name' => '市辖区',
            ),
            447 => 
            array (
                'id' => 45699,
                'area_id' => 410602,
                'name' => '鹤山区',
            ),
            448 => 
            array (
                'id' => 45726,
                'area_id' => 410603,
                'name' => '山城区',
            ),
            449 => 
            array (
                'id' => 45753,
                'area_id' => 410611,
                'name' => '淇滨区',
            ),
            450 => 
            array (
                'id' => 45780,
                'area_id' => 410621,
                'name' => '浚　县',
            ),
            451 => 
            array (
                'id' => 45807,
                'area_id' => 410622,
                'name' => '淇　县',
            ),
            452 => 
            array (
                'id' => 45828,
                'area_id' => 410701,
                'name' => '市辖区',
            ),
            453 => 
            array (
                'id' => 45855,
                'area_id' => 410702,
                'name' => '红旗区',
            ),
            454 => 
            array (
                'id' => 45882,
                'area_id' => 410703,
                'name' => '卫滨区',
            ),
            455 => 
            array (
                'id' => 45909,
                'area_id' => 410704,
                'name' => '凤泉区',
            ),
            456 => 
            array (
                'id' => 45936,
                'area_id' => 410711,
                'name' => '牧野区',
            ),
            457 => 
            array (
                'id' => 45963,
                'area_id' => 410721,
                'name' => '新乡县',
            ),
            458 => 
            array (
                'id' => 45990,
                'area_id' => 410724,
                'name' => '获嘉县',
            ),
            459 => 
            array (
                'id' => 46017,
                'area_id' => 410725,
                'name' => '原阳县',
            ),
            460 => 
            array (
                'id' => 46044,
                'area_id' => 410726,
                'name' => '延津县',
            ),
            461 => 
            array (
                'id' => 46071,
                'area_id' => 410727,
                'name' => '封丘县',
            ),
            462 => 
            array (
                'id' => 46098,
                'area_id' => 410728,
                'name' => '长垣县',
            ),
            463 => 
            array (
                'id' => 46125,
                'area_id' => 410781,
                'name' => '卫辉市',
            ),
            464 => 
            array (
                'id' => 46152,
                'area_id' => 410782,
                'name' => '辉县市',
            ),
            465 => 
            array (
                'id' => 46166,
                'area_id' => 410801,
                'name' => '市辖区',
            ),
            466 => 
            array (
                'id' => 46193,
                'area_id' => 410802,
                'name' => '解放区',
            ),
            467 => 
            array (
                'id' => 46220,
                'area_id' => 410803,
                'name' => '中站区',
            ),
            468 => 
            array (
                'id' => 46247,
                'area_id' => 410804,
                'name' => '马村区',
            ),
            469 => 
            array (
                'id' => 46274,
                'area_id' => 410811,
                'name' => '山阳区',
            ),
            470 => 
            array (
                'id' => 46301,
                'area_id' => 410821,
                'name' => '修武县',
            ),
            471 => 
            array (
                'id' => 46328,
                'area_id' => 410822,
                'name' => '博爱县',
            ),
            472 => 
            array (
                'id' => 46355,
                'area_id' => 410823,
                'name' => '武陟县',
            ),
            473 => 
            array (
                'id' => 46382,
                'area_id' => 410825,
                'name' => '温　县',
            ),
            474 => 
            array (
                'id' => 46409,
                'area_id' => 410881,
                'name' => '济源市',
            ),
            475 => 
            array (
                'id' => 46436,
                'area_id' => 410882,
                'name' => '沁阳市',
            ),
            476 => 
            array (
                'id' => 46463,
                'area_id' => 410883,
                'name' => '孟州市',
            ),
            477 => 
            array (
                'id' => 46478,
                'area_id' => 410901,
                'name' => '市辖区',
            ),
            478 => 
            array (
                'id' => 46505,
                'area_id' => 410902,
                'name' => '华龙区',
            ),
            479 => 
            array (
                'id' => 46532,
                'area_id' => 410922,
                'name' => '清丰县',
            ),
            480 => 
            array (
                'id' => 46559,
                'area_id' => 410923,
                'name' => '南乐县',
            ),
            481 => 
            array (
                'id' => 46586,
                'area_id' => 410926,
                'name' => '范　县',
            ),
            482 => 
            array (
                'id' => 46613,
                'area_id' => 410927,
                'name' => '台前县',
            ),
            483 => 
            array (
                'id' => 46640,
                'area_id' => 410928,
                'name' => '濮阳县',
            ),
            484 => 
            array (
                'id' => 46660,
                'area_id' => 411001,
                'name' => '市辖区',
            ),
            485 => 
            array (
                'id' => 46687,
                'area_id' => 411002,
                'name' => '魏都区',
            ),
            486 => 
            array (
                'id' => 46714,
                'area_id' => 411023,
                'name' => '许昌县',
            ),
            487 => 
            array (
                'id' => 46741,
                'area_id' => 411024,
                'name' => '鄢陵县',
            ),
            488 => 
            array (
                'id' => 46768,
                'area_id' => 411025,
                'name' => '襄城县',
            ),
            489 => 
            array (
                'id' => 46795,
                'area_id' => 411081,
                'name' => '禹州市',
            ),
            490 => 
            array (
                'id' => 46822,
                'area_id' => 411082,
                'name' => '长葛市',
            ),
            491 => 
            array (
                'id' => 46842,
                'area_id' => 411101,
                'name' => '市辖区',
            ),
            492 => 
            array (
                'id' => 46869,
                'area_id' => 411102,
                'name' => '源汇区',
            ),
            493 => 
            array (
                'id' => 46896,
                'area_id' => 411103,
                'name' => '郾城区',
            ),
            494 => 
            array (
                'id' => 46923,
                'area_id' => 411104,
                'name' => '召陵区',
            ),
            495 => 
            array (
                'id' => 46950,
                'area_id' => 411121,
                'name' => '舞阳县',
            ),
            496 => 
            array (
                'id' => 46977,
                'area_id' => 411122,
                'name' => '临颍县',
            ),
            497 => 
            array (
                'id' => 46998,
                'area_id' => 411201,
                'name' => '市辖区',
            ),
            498 => 
            array (
                'id' => 47025,
                'area_id' => 411202,
                'name' => '湖滨区',
            ),
            499 => 
            array (
                'id' => 47052,
                'area_id' => 411221,
                'name' => '渑池县',
            ),
        ));
        \DB::table('areas')->insert(array (
            0 => 
            array (
                'id' => 47079,
                'area_id' => 411222,
                'name' => '陕　县',
            ),
            1 => 
            array (
                'id' => 47106,
                'area_id' => 411224,
                'name' => '卢氏县',
            ),
            2 => 
            array (
                'id' => 47133,
                'area_id' => 411281,
                'name' => '义马市',
            ),
            3 => 
            array (
                'id' => 47160,
                'area_id' => 411282,
                'name' => '灵宝市',
            ),
            4 => 
            array (
                'id' => 47180,
                'area_id' => 411301,
                'name' => '市辖区',
            ),
            5 => 
            array (
                'id' => 47207,
                'area_id' => 411302,
                'name' => '宛城区',
            ),
            6 => 
            array (
                'id' => 47234,
                'area_id' => 411303,
                'name' => '卧龙区',
            ),
            7 => 
            array (
                'id' => 47261,
                'area_id' => 411321,
                'name' => '南召县',
            ),
            8 => 
            array (
                'id' => 47288,
                'area_id' => 411322,
                'name' => '方城县',
            ),
            9 => 
            array (
                'id' => 47315,
                'area_id' => 411323,
                'name' => '西峡县',
            ),
            10 => 
            array (
                'id' => 47342,
                'area_id' => 411324,
                'name' => '镇平县',
            ),
            11 => 
            array (
                'id' => 47369,
                'area_id' => 411325,
                'name' => '内乡县',
            ),
            12 => 
            array (
                'id' => 47396,
                'area_id' => 411326,
                'name' => '淅川县',
            ),
            13 => 
            array (
                'id' => 47423,
                'area_id' => 411327,
                'name' => '社旗县',
            ),
            14 => 
            array (
                'id' => 47450,
                'area_id' => 411328,
                'name' => '唐河县',
            ),
            15 => 
            array (
                'id' => 47477,
                'area_id' => 411329,
                'name' => '新野县',
            ),
            16 => 
            array (
                'id' => 47504,
                'area_id' => 411330,
                'name' => '桐柏县',
            ),
            17 => 
            array (
                'id' => 47531,
                'area_id' => 411381,
                'name' => '邓州市',
            ),
            18 => 
            array (
                'id' => 47544,
                'area_id' => 411401,
                'name' => '市辖区',
            ),
            19 => 
            array (
                'id' => 47571,
                'area_id' => 411402,
                'name' => '梁园区',
            ),
            20 => 
            array (
                'id' => 47598,
                'area_id' => 411403,
                'name' => '睢阳区',
            ),
            21 => 
            array (
                'id' => 47625,
                'area_id' => 411421,
                'name' => '民权县',
            ),
            22 => 
            array (
                'id' => 47652,
                'area_id' => 411422,
                'name' => '睢　县',
            ),
            23 => 
            array (
                'id' => 47679,
                'area_id' => 411423,
                'name' => '宁陵县',
            ),
            24 => 
            array (
                'id' => 47706,
                'area_id' => 411424,
                'name' => '柘城县',
            ),
            25 => 
            array (
                'id' => 47733,
                'area_id' => 411425,
                'name' => '虞城县',
            ),
            26 => 
            array (
                'id' => 47760,
                'area_id' => 411426,
                'name' => '夏邑县',
            ),
            27 => 
            array (
                'id' => 47787,
                'area_id' => 411481,
                'name' => '永城市',
            ),
            28 => 
            array (
                'id' => 47804,
                'area_id' => 411501,
                'name' => '市辖区',
            ),
            29 => 
            array (
                'id' => 47831,
                'area_id' => 411502,
                'name' => '师河区',
            ),
            30 => 
            array (
                'id' => 47858,
                'area_id' => 411503,
                'name' => '平桥区',
            ),
            31 => 
            array (
                'id' => 47885,
                'area_id' => 411521,
                'name' => '罗山县',
            ),
            32 => 
            array (
                'id' => 47912,
                'area_id' => 411522,
                'name' => '光山县',
            ),
            33 => 
            array (
                'id' => 47939,
                'area_id' => 411523,
                'name' => '新　县',
            ),
            34 => 
            array (
                'id' => 47966,
                'area_id' => 411524,
                'name' => '商城县',
            ),
            35 => 
            array (
                'id' => 47993,
                'area_id' => 411525,
                'name' => '固始县',
            ),
            36 => 
            array (
                'id' => 48020,
                'area_id' => 411526,
                'name' => '潢川县',
            ),
            37 => 
            array (
                'id' => 48047,
                'area_id' => 411527,
                'name' => '淮滨县',
            ),
            38 => 
            array (
                'id' => 48074,
                'area_id' => 411528,
                'name' => '息　县',
            ),
            39 => 
            array (
                'id' => 48090,
                'area_id' => 411601,
                'name' => '市辖区',
            ),
            40 => 
            array (
                'id' => 48117,
                'area_id' => 411602,
                'name' => '川汇区',
            ),
            41 => 
            array (
                'id' => 48144,
                'area_id' => 411621,
                'name' => '扶沟县',
            ),
            42 => 
            array (
                'id' => 48171,
                'area_id' => 411622,
                'name' => '西华县',
            ),
            43 => 
            array (
                'id' => 48198,
                'area_id' => 411623,
                'name' => '商水县',
            ),
            44 => 
            array (
                'id' => 48225,
                'area_id' => 411624,
                'name' => '沈丘县',
            ),
            45 => 
            array (
                'id' => 48252,
                'area_id' => 411625,
                'name' => '郸城县',
            ),
            46 => 
            array (
                'id' => 48279,
                'area_id' => 411626,
                'name' => '淮阳县',
            ),
            47 => 
            array (
                'id' => 48306,
                'area_id' => 411627,
                'name' => '太康县',
            ),
            48 => 
            array (
                'id' => 48333,
                'area_id' => 411628,
                'name' => '鹿邑县',
            ),
            49 => 
            array (
                'id' => 48360,
                'area_id' => 411681,
                'name' => '项城市',
            ),
            50 => 
            array (
                'id' => 48376,
                'area_id' => 411701,
                'name' => '市辖区',
            ),
            51 => 
            array (
                'id' => 48403,
                'area_id' => 411702,
                'name' => '驿城区',
            ),
            52 => 
            array (
                'id' => 48430,
                'area_id' => 411721,
                'name' => '西平县',
            ),
            53 => 
            array (
                'id' => 48457,
                'area_id' => 411722,
                'name' => '上蔡县',
            ),
            54 => 
            array (
                'id' => 48484,
                'area_id' => 411723,
                'name' => '平舆县',
            ),
            55 => 
            array (
                'id' => 48511,
                'area_id' => 411724,
                'name' => '正阳县',
            ),
            56 => 
            array (
                'id' => 48538,
                'area_id' => 411725,
                'name' => '确山县',
            ),
            57 => 
            array (
                'id' => 48565,
                'area_id' => 411726,
                'name' => '泌阳县',
            ),
            58 => 
            array (
                'id' => 48592,
                'area_id' => 411727,
                'name' => '汝南县',
            ),
            59 => 
            array (
                'id' => 48619,
                'area_id' => 411728,
                'name' => '遂平县',
            ),
            60 => 
            array (
                'id' => 48646,
                'area_id' => 411729,
                'name' => '新蔡县',
            ),
            61 => 
            array (
                'id' => 48662,
                'area_id' => 420101,
                'name' => '市辖区',
            ),
            62 => 
            array (
                'id' => 48689,
                'area_id' => 420102,
                'name' => '江岸区',
            ),
            63 => 
            array (
                'id' => 48716,
                'area_id' => 420103,
                'name' => '江汉区',
            ),
            64 => 
            array (
                'id' => 48743,
                'area_id' => 420104,
                'name' => '乔口区',
            ),
            65 => 
            array (
                'id' => 48770,
                'area_id' => 420105,
                'name' => '汉阳区',
            ),
            66 => 
            array (
                'id' => 48797,
                'area_id' => 420106,
                'name' => '武昌区',
            ),
            67 => 
            array (
                'id' => 48824,
                'area_id' => 420107,
                'name' => '青山区',
            ),
            68 => 
            array (
                'id' => 48851,
                'area_id' => 420111,
                'name' => '洪山区',
            ),
            69 => 
            array (
                'id' => 48878,
                'area_id' => 420112,
                'name' => '东西湖区',
            ),
            70 => 
            array (
                'id' => 48905,
                'area_id' => 420113,
                'name' => '汉南区',
            ),
            71 => 
            array (
                'id' => 48932,
                'area_id' => 420114,
                'name' => '蔡甸区',
            ),
            72 => 
            array (
                'id' => 48959,
                'area_id' => 420115,
                'name' => '江夏区',
            ),
            73 => 
            array (
                'id' => 48986,
                'area_id' => 420116,
                'name' => '黄陂区',
            ),
            74 => 
            array (
                'id' => 49013,
                'area_id' => 420117,
                'name' => '新洲区',
            ),
            75 => 
            array (
                'id' => 49026,
                'area_id' => 420201,
                'name' => '市辖区',
            ),
            76 => 
            array (
                'id' => 49053,
                'area_id' => 420202,
                'name' => '黄石港区',
            ),
            77 => 
            array (
                'id' => 49080,
                'area_id' => 420203,
                'name' => '西塞山区',
            ),
            78 => 
            array (
                'id' => 49107,
                'area_id' => 420204,
                'name' => '下陆区',
            ),
            79 => 
            array (
                'id' => 49134,
                'area_id' => 420205,
                'name' => '铁山区',
            ),
            80 => 
            array (
                'id' => 49161,
                'area_id' => 420222,
                'name' => '阳新县',
            ),
            81 => 
            array (
                'id' => 49188,
                'area_id' => 420281,
                'name' => '大冶市',
            ),
            82 => 
            array (
                'id' => 49208,
                'area_id' => 420301,
                'name' => '市辖区',
            ),
            83 => 
            array (
                'id' => 49235,
                'area_id' => 420302,
                'name' => '茅箭区',
            ),
            84 => 
            array (
                'id' => 49262,
                'area_id' => 420303,
                'name' => '张湾区',
            ),
            85 => 
            array (
                'id' => 49289,
                'area_id' => 420321,
                'name' => '郧　县',
            ),
            86 => 
            array (
                'id' => 49316,
                'area_id' => 420322,
                'name' => '郧西县',
            ),
            87 => 
            array (
                'id' => 49343,
                'area_id' => 420323,
                'name' => '竹山县',
            ),
            88 => 
            array (
                'id' => 49370,
                'area_id' => 420324,
                'name' => '竹溪县',
            ),
            89 => 
            array (
                'id' => 49397,
                'area_id' => 420325,
                'name' => '房　县',
            ),
            90 => 
            array (
                'id' => 49424,
                'area_id' => 420381,
                'name' => '丹江口市',
            ),
            91 => 
            array (
                'id' => 49442,
                'area_id' => 420501,
                'name' => '市辖区',
            ),
            92 => 
            array (
                'id' => 49469,
                'area_id' => 420502,
                'name' => '西陵区',
            ),
            93 => 
            array (
                'id' => 49496,
                'area_id' => 420503,
                'name' => '伍家岗区',
            ),
            94 => 
            array (
                'id' => 49523,
                'area_id' => 420504,
                'name' => '点军区',
            ),
            95 => 
            array (
                'id' => 49550,
                'area_id' => 420505,
                'name' => '猇亭区',
            ),
            96 => 
            array (
                'id' => 49577,
                'area_id' => 420506,
                'name' => '夷陵区',
            ),
            97 => 
            array (
                'id' => 49604,
                'area_id' => 420525,
                'name' => '远安县',
            ),
            98 => 
            array (
                'id' => 49631,
                'area_id' => 420526,
                'name' => '兴山县',
            ),
            99 => 
            array (
                'id' => 49658,
                'area_id' => 420527,
                'name' => '秭归县',
            ),
            100 => 
            array (
                'id' => 49685,
                'area_id' => 420528,
                'name' => '长阳土家族自治县',
            ),
            101 => 
            array (
                'id' => 49712,
                'area_id' => 420529,
                'name' => '五峰土家族自治县',
            ),
            102 => 
            array (
                'id' => 49739,
                'area_id' => 420581,
                'name' => '宜都市',
            ),
            103 => 
            array (
                'id' => 49766,
                'area_id' => 420582,
                'name' => '当阳市',
            ),
            104 => 
            array (
                'id' => 49793,
                'area_id' => 420583,
                'name' => '枝江市',
            ),
            105 => 
            array (
                'id' => 49806,
                'area_id' => 420601,
                'name' => '市辖区',
            ),
            106 => 
            array (
                'id' => 49833,
                'area_id' => 420602,
                'name' => '襄城区',
            ),
            107 => 
            array (
                'id' => 49860,
                'area_id' => 420606,
                'name' => '樊城区',
            ),
            108 => 
            array (
                'id' => 49887,
                'area_id' => 420607,
                'name' => '襄阳区',
            ),
            109 => 
            array (
                'id' => 49914,
                'area_id' => 420624,
                'name' => '南漳县',
            ),
            110 => 
            array (
                'id' => 49941,
                'area_id' => 420625,
                'name' => '谷城县',
            ),
            111 => 
            array (
                'id' => 49968,
                'area_id' => 420626,
                'name' => '保康县',
            ),
            112 => 
            array (
                'id' => 49995,
                'area_id' => 420682,
                'name' => '老河口市',
            ),
            113 => 
            array (
                'id' => 50022,
                'area_id' => 420683,
                'name' => '枣阳市',
            ),
            114 => 
            array (
                'id' => 50049,
                'area_id' => 420684,
                'name' => '宜城市',
            ),
            115 => 
            array (
                'id' => 50066,
                'area_id' => 420701,
                'name' => '市辖区',
            ),
            116 => 
            array (
                'id' => 50093,
                'area_id' => 420702,
                'name' => '梁子湖区',
            ),
            117 => 
            array (
                'id' => 50120,
                'area_id' => 420703,
                'name' => '华容区',
            ),
            118 => 
            array (
                'id' => 50147,
                'area_id' => 420704,
                'name' => '鄂城区',
            ),
            119 => 
            array (
                'id' => 50170,
                'area_id' => 420801,
                'name' => '市辖区',
            ),
            120 => 
            array (
                'id' => 50197,
                'area_id' => 420802,
                'name' => '东宝区',
            ),
            121 => 
            array (
                'id' => 50224,
                'area_id' => 420804,
                'name' => '掇刀区',
            ),
            122 => 
            array (
                'id' => 50251,
                'area_id' => 420821,
                'name' => '京山县',
            ),
            123 => 
            array (
                'id' => 50278,
                'area_id' => 420822,
                'name' => '沙洋县',
            ),
            124 => 
            array (
                'id' => 50305,
                'area_id' => 420881,
                'name' => '钟祥市',
            ),
            125 => 
            array (
                'id' => 50326,
                'area_id' => 420901,
                'name' => '市辖区',
            ),
            126 => 
            array (
                'id' => 50353,
                'area_id' => 420902,
                'name' => '孝南区',
            ),
            127 => 
            array (
                'id' => 50380,
                'area_id' => 420921,
                'name' => '孝昌县',
            ),
            128 => 
            array (
                'id' => 50407,
                'area_id' => 420922,
                'name' => '大悟县',
            ),
            129 => 
            array (
                'id' => 50434,
                'area_id' => 420923,
                'name' => '云梦县',
            ),
            130 => 
            array (
                'id' => 50461,
                'area_id' => 420981,
                'name' => '应城市',
            ),
            131 => 
            array (
                'id' => 50488,
                'area_id' => 420982,
                'name' => '安陆市',
            ),
            132 => 
            array (
                'id' => 50515,
                'area_id' => 420984,
                'name' => '汉川市',
            ),
            133 => 
            array (
                'id' => 50534,
                'area_id' => 421001,
                'name' => '市辖区',
            ),
            134 => 
            array (
                'id' => 50561,
                'area_id' => 421002,
                'name' => '沙市区',
            ),
            135 => 
            array (
                'id' => 50588,
                'area_id' => 421003,
                'name' => '荆州区',
            ),
            136 => 
            array (
                'id' => 50615,
                'area_id' => 421022,
                'name' => '公安县',
            ),
            137 => 
            array (
                'id' => 50642,
                'area_id' => 421023,
                'name' => '监利县',
            ),
            138 => 
            array (
                'id' => 50669,
                'area_id' => 421024,
                'name' => '江陵县',
            ),
            139 => 
            array (
                'id' => 50696,
                'area_id' => 421081,
                'name' => '石首市',
            ),
            140 => 
            array (
                'id' => 50723,
                'area_id' => 421083,
                'name' => '洪湖市',
            ),
            141 => 
            array (
                'id' => 50750,
                'area_id' => 421087,
                'name' => '松滋市',
            ),
            142 => 
            array (
                'id' => 50768,
                'area_id' => 421101,
                'name' => '市辖区',
            ),
            143 => 
            array (
                'id' => 50795,
                'area_id' => 421102,
                'name' => '黄州区',
            ),
            144 => 
            array (
                'id' => 50822,
                'area_id' => 421121,
                'name' => '团风县',
            ),
            145 => 
            array (
                'id' => 50849,
                'area_id' => 421122,
                'name' => '红安县',
            ),
            146 => 
            array (
                'id' => 50876,
                'area_id' => 421123,
                'name' => '罗田县',
            ),
            147 => 
            array (
                'id' => 50903,
                'area_id' => 421124,
                'name' => '英山县',
            ),
            148 => 
            array (
                'id' => 50930,
                'area_id' => 421125,
                'name' => '浠水县',
            ),
            149 => 
            array (
                'id' => 50957,
                'area_id' => 421126,
                'name' => '蕲春县',
            ),
            150 => 
            array (
                'id' => 50984,
                'area_id' => 421127,
                'name' => '黄梅县',
            ),
            151 => 
            array (
                'id' => 51011,
                'area_id' => 421181,
                'name' => '麻城市',
            ),
            152 => 
            array (
                'id' => 51038,
                'area_id' => 421182,
                'name' => '武穴市',
            ),
            153 => 
            array (
                'id' => 51054,
                'area_id' => 421201,
                'name' => '市辖区',
            ),
            154 => 
            array (
                'id' => 51081,
                'area_id' => 421202,
                'name' => '咸安区',
            ),
            155 => 
            array (
                'id' => 51108,
                'area_id' => 421221,
                'name' => '嘉鱼县',
            ),
            156 => 
            array (
                'id' => 51135,
                'area_id' => 421222,
                'name' => '通城县',
            ),
            157 => 
            array (
                'id' => 51162,
                'area_id' => 421223,
                'name' => '崇阳县',
            ),
            158 => 
            array (
                'id' => 51189,
                'area_id' => 421224,
                'name' => '通山县',
            ),
            159 => 
            array (
                'id' => 51216,
                'area_id' => 421281,
                'name' => '赤壁市',
            ),
            160 => 
            array (
                'id' => 51236,
                'area_id' => 421301,
                'name' => '市辖区',
            ),
            161 => 
            array (
                'id' => 51263,
                'area_id' => 421302,
                'name' => '曾都区',
            ),
            162 => 
            array (
                'id' => 51290,
                'area_id' => 421381,
                'name' => '广水市',
            ),
            163 => 
            array (
                'id' => 51314,
                'area_id' => 422801,
                'name' => '恩施市',
            ),
            164 => 
            array (
                'id' => 51341,
                'area_id' => 422802,
                'name' => '利川市',
            ),
            165 => 
            array (
                'id' => 51368,
                'area_id' => 422822,
                'name' => '建始县',
            ),
            166 => 
            array (
                'id' => 51395,
                'area_id' => 422823,
                'name' => '巴东县',
            ),
            167 => 
            array (
                'id' => 51422,
                'area_id' => 422825,
                'name' => '宣恩县',
            ),
            168 => 
            array (
                'id' => 51449,
                'area_id' => 422826,
                'name' => '咸丰县',
            ),
            169 => 
            array (
                'id' => 51476,
                'area_id' => 422827,
                'name' => '来凤县',
            ),
            170 => 
            array (
                'id' => 51503,
                'area_id' => 422828,
                'name' => '鹤峰县',
            ),
            171 => 
            array (
                'id' => 51522,
                'area_id' => 429004,
                'name' => '仙桃市',
            ),
            172 => 
            array (
                'id' => 51549,
                'area_id' => 429005,
                'name' => '潜江市',
            ),
            173 => 
            array (
                'id' => 51576,
                'area_id' => 429006,
                'name' => '天门市',
            ),
            174 => 
            array (
                'id' => 51603,
                'area_id' => 429021,
                'name' => '神农架林区',
            ),
            175 => 
            array (
                'id' => 51626,
                'area_id' => 430101,
                'name' => '市辖区',
            ),
            176 => 
            array (
                'id' => 51653,
                'area_id' => 430102,
                'name' => '芙蓉区',
            ),
            177 => 
            array (
                'id' => 51680,
                'area_id' => 430103,
                'name' => '天心区',
            ),
            178 => 
            array (
                'id' => 51707,
                'area_id' => 430104,
                'name' => '岳麓区',
            ),
            179 => 
            array (
                'id' => 51734,
                'area_id' => 430105,
                'name' => '开福区',
            ),
            180 => 
            array (
                'id' => 51761,
                'area_id' => 430111,
                'name' => '雨花区',
            ),
            181 => 
            array (
                'id' => 51788,
                'area_id' => 430121,
                'name' => '长沙县',
            ),
            182 => 
            array (
                'id' => 51815,
                'area_id' => 430122,
                'name' => '望城县',
            ),
            183 => 
            array (
                'id' => 51842,
                'area_id' => 430124,
                'name' => '宁乡县',
            ),
            184 => 
            array (
                'id' => 51869,
                'area_id' => 430181,
                'name' => '浏阳市',
            ),
            185 => 
            array (
                'id' => 51886,
                'area_id' => 430201,
                'name' => '市辖区',
            ),
            186 => 
            array (
                'id' => 51913,
                'area_id' => 430202,
                'name' => '荷塘区',
            ),
            187 => 
            array (
                'id' => 51940,
                'area_id' => 430203,
                'name' => '芦淞区',
            ),
            188 => 
            array (
                'id' => 51967,
                'area_id' => 430204,
                'name' => '石峰区',
            ),
            189 => 
            array (
                'id' => 51994,
                'area_id' => 430211,
                'name' => '天元区',
            ),
            190 => 
            array (
                'id' => 52021,
                'area_id' => 430221,
                'name' => '株洲县',
            ),
            191 => 
            array (
                'id' => 52048,
                'area_id' => 430223,
                'name' => '攸　县',
            ),
            192 => 
            array (
                'id' => 52075,
                'area_id' => 430224,
                'name' => '茶陵县',
            ),
            193 => 
            array (
                'id' => 52102,
                'area_id' => 430225,
                'name' => '炎陵县',
            ),
            194 => 
            array (
                'id' => 52129,
                'area_id' => 430281,
                'name' => '醴陵市',
            ),
            195 => 
            array (
                'id' => 52146,
                'area_id' => 430301,
                'name' => '市辖区',
            ),
            196 => 
            array (
                'id' => 52173,
                'area_id' => 430302,
                'name' => '雨湖区',
            ),
            197 => 
            array (
                'id' => 52200,
                'area_id' => 430304,
                'name' => '岳塘区',
            ),
            198 => 
            array (
                'id' => 52227,
                'area_id' => 430321,
                'name' => '湘潭县',
            ),
            199 => 
            array (
                'id' => 52254,
                'area_id' => 430381,
                'name' => '湘乡市',
            ),
            200 => 
            array (
                'id' => 52281,
                'area_id' => 430382,
                'name' => '韶山市',
            ),
            201 => 
            array (
                'id' => 52302,
                'area_id' => 430401,
                'name' => '市辖区',
            ),
            202 => 
            array (
                'id' => 52329,
                'area_id' => 430405,
                'name' => '珠晖区',
            ),
            203 => 
            array (
                'id' => 52356,
                'area_id' => 430406,
                'name' => '雁峰区',
            ),
            204 => 
            array (
                'id' => 52383,
                'area_id' => 430407,
                'name' => '石鼓区',
            ),
            205 => 
            array (
                'id' => 52410,
                'area_id' => 430408,
                'name' => '蒸湘区',
            ),
            206 => 
            array (
                'id' => 52437,
                'area_id' => 430412,
                'name' => '南岳区',
            ),
            207 => 
            array (
                'id' => 52464,
                'area_id' => 430421,
                'name' => '衡阳县',
            ),
            208 => 
            array (
                'id' => 52491,
                'area_id' => 430422,
                'name' => '衡南县',
            ),
            209 => 
            array (
                'id' => 52518,
                'area_id' => 430423,
                'name' => '衡山县',
            ),
            210 => 
            array (
                'id' => 52545,
                'area_id' => 430424,
                'name' => '衡东县',
            ),
            211 => 
            array (
                'id' => 52572,
                'area_id' => 430426,
                'name' => '祁东县',
            ),
            212 => 
            array (
                'id' => 52599,
                'area_id' => 430481,
                'name' => '耒阳市',
            ),
            213 => 
            array (
                'id' => 52626,
                'area_id' => 430482,
                'name' => '常宁市',
            ),
            214 => 
            array (
                'id' => 52640,
                'area_id' => 430501,
                'name' => '市辖区',
            ),
            215 => 
            array (
                'id' => 52667,
                'area_id' => 430502,
                'name' => '双清区',
            ),
            216 => 
            array (
                'id' => 52694,
                'area_id' => 430503,
                'name' => '大祥区',
            ),
            217 => 
            array (
                'id' => 52721,
                'area_id' => 430511,
                'name' => '北塔区',
            ),
            218 => 
            array (
                'id' => 52748,
                'area_id' => 430521,
                'name' => '邵东县',
            ),
            219 => 
            array (
                'id' => 52775,
                'area_id' => 430522,
                'name' => '新邵县',
            ),
            220 => 
            array (
                'id' => 52802,
                'area_id' => 430523,
                'name' => '邵阳县',
            ),
            221 => 
            array (
                'id' => 52829,
                'area_id' => 430524,
                'name' => '隆回县',
            ),
            222 => 
            array (
                'id' => 52856,
                'area_id' => 430525,
                'name' => '洞口县',
            ),
            223 => 
            array (
                'id' => 52883,
                'area_id' => 430527,
                'name' => '绥宁县',
            ),
            224 => 
            array (
                'id' => 52910,
                'area_id' => 430528,
                'name' => '新宁县',
            ),
            225 => 
            array (
                'id' => 52937,
                'area_id' => 430529,
                'name' => '城步苗族自治县',
            ),
            226 => 
            array (
                'id' => 52964,
                'area_id' => 430581,
                'name' => '武冈市',
            ),
            227 => 
            array (
                'id' => 52978,
                'area_id' => 430601,
                'name' => '市辖区',
            ),
            228 => 
            array (
                'id' => 53005,
                'area_id' => 430602,
                'name' => '岳阳楼区',
            ),
            229 => 
            array (
                'id' => 53032,
                'area_id' => 430603,
                'name' => '云溪区',
            ),
            230 => 
            array (
                'id' => 53059,
                'area_id' => 430611,
                'name' => '君山区',
            ),
            231 => 
            array (
                'id' => 53086,
                'area_id' => 430621,
                'name' => '岳阳县',
            ),
            232 => 
            array (
                'id' => 53113,
                'area_id' => 430623,
                'name' => '华容县',
            ),
            233 => 
            array (
                'id' => 53140,
                'area_id' => 430624,
                'name' => '湘阴县',
            ),
            234 => 
            array (
                'id' => 53167,
                'area_id' => 430626,
                'name' => '平江县',
            ),
            235 => 
            array (
                'id' => 53194,
                'area_id' => 430681,
                'name' => '汨罗市',
            ),
            236 => 
            array (
                'id' => 53221,
                'area_id' => 430682,
                'name' => '临湘市',
            ),
            237 => 
            array (
                'id' => 53238,
                'area_id' => 430701,
                'name' => '市辖区',
            ),
            238 => 
            array (
                'id' => 53265,
                'area_id' => 430702,
                'name' => '武陵区',
            ),
            239 => 
            array (
                'id' => 53292,
                'area_id' => 430703,
                'name' => '鼎城区',
            ),
            240 => 
            array (
                'id' => 53319,
                'area_id' => 430721,
                'name' => '安乡县',
            ),
            241 => 
            array (
                'id' => 53346,
                'area_id' => 430722,
                'name' => '汉寿县',
            ),
            242 => 
            array (
                'id' => 53373,
                'area_id' => 430723,
                'name' => '澧　县',
            ),
            243 => 
            array (
                'id' => 53400,
                'area_id' => 430724,
                'name' => '临澧县',
            ),
            244 => 
            array (
                'id' => 53427,
                'area_id' => 430725,
                'name' => '桃源县',
            ),
            245 => 
            array (
                'id' => 53454,
                'area_id' => 430726,
                'name' => '石门县',
            ),
            246 => 
            array (
                'id' => 53481,
                'area_id' => 430781,
                'name' => '津市市',
            ),
            247 => 
            array (
                'id' => 53498,
                'area_id' => 430801,
                'name' => '市辖区',
            ),
            248 => 
            array (
                'id' => 53525,
                'area_id' => 430802,
                'name' => '永定区',
            ),
            249 => 
            array (
                'id' => 53552,
                'area_id' => 430811,
                'name' => '武陵源区',
            ),
            250 => 
            array (
                'id' => 53579,
                'area_id' => 430821,
                'name' => '慈利县',
            ),
            251 => 
            array (
                'id' => 53606,
                'area_id' => 430822,
                'name' => '桑植县',
            ),
            252 => 
            array (
                'id' => 53628,
                'area_id' => 430901,
                'name' => '市辖区',
            ),
            253 => 
            array (
                'id' => 53655,
                'area_id' => 430902,
                'name' => '资阳区',
            ),
            254 => 
            array (
                'id' => 53682,
                'area_id' => 430903,
                'name' => '赫山区',
            ),
            255 => 
            array (
                'id' => 53709,
                'area_id' => 430921,
                'name' => '南　县',
            ),
            256 => 
            array (
                'id' => 53736,
                'area_id' => 430922,
                'name' => '桃江县',
            ),
            257 => 
            array (
                'id' => 53763,
                'area_id' => 430923,
                'name' => '安化县',
            ),
            258 => 
            array (
                'id' => 53790,
                'area_id' => 430981,
                'name' => '沅江市',
            ),
            259 => 
            array (
                'id' => 53810,
                'area_id' => 431001,
                'name' => '市辖区',
            ),
            260 => 
            array (
                'id' => 53837,
                'area_id' => 431002,
                'name' => '北湖区',
            ),
            261 => 
            array (
                'id' => 53864,
                'area_id' => 431003,
                'name' => '苏仙区',
            ),
            262 => 
            array (
                'id' => 53891,
                'area_id' => 431021,
                'name' => '桂阳县',
            ),
            263 => 
            array (
                'id' => 53918,
                'area_id' => 431022,
                'name' => '宜章县',
            ),
            264 => 
            array (
                'id' => 53945,
                'area_id' => 431023,
                'name' => '永兴县',
            ),
            265 => 
            array (
                'id' => 53972,
                'area_id' => 431024,
                'name' => '嘉禾县',
            ),
            266 => 
            array (
                'id' => 53999,
                'area_id' => 431025,
                'name' => '临武县',
            ),
            267 => 
            array (
                'id' => 54026,
                'area_id' => 431026,
                'name' => '汝城县',
            ),
            268 => 
            array (
                'id' => 54053,
                'area_id' => 431027,
                'name' => '桂东县',
            ),
            269 => 
            array (
                'id' => 54080,
                'area_id' => 431028,
                'name' => '安仁县',
            ),
            270 => 
            array (
                'id' => 54107,
                'area_id' => 431081,
                'name' => '资兴市',
            ),
            271 => 
            array (
                'id' => 54122,
                'area_id' => 431101,
                'name' => '市辖区',
            ),
            272 => 
            array (
                'id' => 54149,
                'area_id' => 431102,
                'name' => '芝山区',
            ),
            273 => 
            array (
                'id' => 54176,
                'area_id' => 431103,
                'name' => '冷水滩区',
            ),
            274 => 
            array (
                'id' => 54203,
                'area_id' => 431121,
                'name' => '祁阳县',
            ),
            275 => 
            array (
                'id' => 54230,
                'area_id' => 431122,
                'name' => '东安县',
            ),
            276 => 
            array (
                'id' => 54257,
                'area_id' => 431123,
                'name' => '双牌县',
            ),
            277 => 
            array (
                'id' => 54284,
                'area_id' => 431124,
                'name' => '道　县',
            ),
            278 => 
            array (
                'id' => 54311,
                'area_id' => 431125,
                'name' => '江永县',
            ),
            279 => 
            array (
                'id' => 54338,
                'area_id' => 431126,
                'name' => '宁远县',
            ),
            280 => 
            array (
                'id' => 54365,
                'area_id' => 431127,
                'name' => '蓝山县',
            ),
            281 => 
            array (
                'id' => 54392,
                'area_id' => 431128,
                'name' => '新田县',
            ),
            282 => 
            array (
                'id' => 54419,
                'area_id' => 431129,
                'name' => '江华瑶族自治县',
            ),
            283 => 
            array (
                'id' => 54434,
                'area_id' => 431201,
                'name' => '市辖区',
            ),
            284 => 
            array (
                'id' => 54461,
                'area_id' => 431202,
                'name' => '鹤城区',
            ),
            285 => 
            array (
                'id' => 54488,
                'area_id' => 431221,
                'name' => '中方县',
            ),
            286 => 
            array (
                'id' => 54515,
                'area_id' => 431222,
                'name' => '沅陵县',
            ),
            287 => 
            array (
                'id' => 54542,
                'area_id' => 431223,
                'name' => '辰溪县',
            ),
            288 => 
            array (
                'id' => 54569,
                'area_id' => 431224,
                'name' => '溆浦县',
            ),
            289 => 
            array (
                'id' => 54596,
                'area_id' => 431225,
                'name' => '会同县',
            ),
            290 => 
            array (
                'id' => 54623,
                'area_id' => 431226,
                'name' => '麻阳苗族自治县',
            ),
            291 => 
            array (
                'id' => 54650,
                'area_id' => 431227,
                'name' => '新晃侗族自治县',
            ),
            292 => 
            array (
                'id' => 54677,
                'area_id' => 431228,
                'name' => '芷江侗族自治县',
            ),
            293 => 
            array (
                'id' => 54704,
                'area_id' => 431229,
                'name' => '靖州苗族侗族自治县',
            ),
            294 => 
            array (
                'id' => 54731,
                'area_id' => 431230,
                'name' => '通道侗族自治县',
            ),
            295 => 
            array (
                'id' => 54758,
                'area_id' => 431281,
                'name' => '洪江市',
            ),
            296 => 
            array (
                'id' => 54772,
                'area_id' => 431301,
                'name' => '市辖区',
            ),
            297 => 
            array (
                'id' => 54799,
                'area_id' => 431302,
                'name' => '娄星区',
            ),
            298 => 
            array (
                'id' => 54826,
                'area_id' => 431321,
                'name' => '双峰县',
            ),
            299 => 
            array (
                'id' => 54853,
                'area_id' => 431322,
                'name' => '新化县',
            ),
            300 => 
            array (
                'id' => 54880,
                'area_id' => 431381,
                'name' => '冷水江市',
            ),
            301 => 
            array (
                'id' => 54907,
                'area_id' => 431382,
                'name' => '涟源市',
            ),
            302 => 
            array (
                'id' => 54928,
                'area_id' => 433101,
                'name' => '吉首市',
            ),
            303 => 
            array (
                'id' => 54955,
                'area_id' => 433122,
                'name' => '泸溪县',
            ),
            304 => 
            array (
                'id' => 54982,
                'area_id' => 433123,
                'name' => '凤凰县',
            ),
            305 => 
            array (
                'id' => 55009,
                'area_id' => 433124,
                'name' => '花垣县',
            ),
            306 => 
            array (
                'id' => 55036,
                'area_id' => 433125,
                'name' => '保靖县',
            ),
            307 => 
            array (
                'id' => 55063,
                'area_id' => 433126,
                'name' => '古丈县',
            ),
            308 => 
            array (
                'id' => 55090,
                'area_id' => 433127,
                'name' => '永顺县',
            ),
            309 => 
            array (
                'id' => 55117,
                'area_id' => 433130,
                'name' => '龙山县',
            ),
            310 => 
            array (
                'id' => 55136,
                'area_id' => 440101,
                'name' => '市辖区',
            ),
            311 => 
            array (
                'id' => 55163,
                'area_id' => 440102,
                'name' => '东山区',
            ),
            312 => 
            array (
                'id' => 55190,
                'area_id' => 440103,
                'name' => '荔湾区',
            ),
            313 => 
            array (
                'id' => 55217,
                'area_id' => 440104,
                'name' => '越秀区',
            ),
            314 => 
            array (
                'id' => 55244,
                'area_id' => 440105,
                'name' => '海珠区',
            ),
            315 => 
            array (
                'id' => 55271,
                'area_id' => 440106,
                'name' => '天河区',
            ),
            316 => 
            array (
                'id' => 55298,
                'area_id' => 440107,
                'name' => '芳村区',
            ),
            317 => 
            array (
                'id' => 55325,
                'area_id' => 440111,
                'name' => '白云区',
            ),
            318 => 
            array (
                'id' => 55352,
                'area_id' => 440112,
                'name' => '黄埔区',
            ),
            319 => 
            array (
                'id' => 55379,
                'area_id' => 440113,
                'name' => '番禺区',
            ),
            320 => 
            array (
                'id' => 55406,
                'area_id' => 440114,
                'name' => '花都区',
            ),
            321 => 
            array (
                'id' => 55433,
                'area_id' => 440183,
                'name' => '增城市',
            ),
            322 => 
            array (
                'id' => 55460,
                'area_id' => 440184,
                'name' => '从化市',
            ),
            323 => 
            array (
                'id' => 55474,
                'area_id' => 440201,
                'name' => '市辖区',
            ),
            324 => 
            array (
                'id' => 55501,
                'area_id' => 440203,
                'name' => '武江区',
            ),
            325 => 
            array (
                'id' => 55528,
                'area_id' => 440204,
                'name' => '浈江区',
            ),
            326 => 
            array (
                'id' => 55555,
                'area_id' => 440205,
                'name' => '曲江区',
            ),
            327 => 
            array (
                'id' => 55582,
                'area_id' => 440222,
                'name' => '始兴县',
            ),
            328 => 
            array (
                'id' => 55609,
                'area_id' => 440224,
                'name' => '仁化县',
            ),
            329 => 
            array (
                'id' => 55636,
                'area_id' => 440229,
                'name' => '翁源县',
            ),
            330 => 
            array (
                'id' => 55663,
                'area_id' => 440232,
                'name' => '乳源瑶族自治县',
            ),
            331 => 
            array (
                'id' => 55690,
                'area_id' => 440233,
                'name' => '新丰县',
            ),
            332 => 
            array (
                'id' => 55717,
                'area_id' => 440281,
                'name' => '乐昌市',
            ),
            333 => 
            array (
                'id' => 55744,
                'area_id' => 440282,
                'name' => '南雄市',
            ),
            334 => 
            array (
                'id' => 55760,
                'area_id' => 440301,
                'name' => '市辖区',
            ),
            335 => 
            array (
                'id' => 55787,
                'area_id' => 440303,
                'name' => '罗湖区',
            ),
            336 => 
            array (
                'id' => 55814,
                'area_id' => 440304,
                'name' => '福田区',
            ),
            337 => 
            array (
                'id' => 55841,
                'area_id' => 440305,
                'name' => '南山区',
            ),
            338 => 
            array (
                'id' => 55868,
                'area_id' => 440306,
                'name' => '宝安区',
            ),
            339 => 
            array (
                'id' => 55895,
                'area_id' => 440307,
                'name' => '龙岗区',
            ),
            340 => 
            array (
                'id' => 55922,
                'area_id' => 440308,
                'name' => '盐田区',
            ),
            341 => 
            array (
                'id' => 55942,
                'area_id' => 440401,
                'name' => '市辖区',
            ),
            342 => 
            array (
                'id' => 55969,
                'area_id' => 440402,
                'name' => '香洲区',
            ),
            343 => 
            array (
                'id' => 55996,
                'area_id' => 440403,
                'name' => '斗门区',
            ),
            344 => 
            array (
                'id' => 56023,
                'area_id' => 440404,
                'name' => '金湾区',
            ),
            345 => 
            array (
                'id' => 56046,
                'area_id' => 440501,
                'name' => '市辖区',
            ),
            346 => 
            array (
                'id' => 56073,
                'area_id' => 440507,
                'name' => '龙湖区',
            ),
            347 => 
            array (
                'id' => 56100,
                'area_id' => 440511,
                'name' => '金平区',
            ),
            348 => 
            array (
                'id' => 56127,
                'area_id' => 440512,
                'name' => '濠江区',
            ),
            349 => 
            array (
                'id' => 56154,
                'area_id' => 440513,
                'name' => '潮阳区',
            ),
            350 => 
            array (
                'id' => 56181,
                'area_id' => 440514,
                'name' => '潮南区',
            ),
            351 => 
            array (
                'id' => 56208,
                'area_id' => 440515,
                'name' => '澄海区',
            ),
            352 => 
            array (
                'id' => 56235,
                'area_id' => 440523,
                'name' => '南澳县',
            ),
            353 => 
            array (
                'id' => 56254,
                'area_id' => 440601,
                'name' => '市辖区',
            ),
            354 => 
            array (
                'id' => 56281,
                'area_id' => 440604,
                'name' => '禅城区',
            ),
            355 => 
            array (
                'id' => 56308,
                'area_id' => 440605,
                'name' => '南海区',
            ),
            356 => 
            array (
                'id' => 56335,
                'area_id' => 440606,
                'name' => '顺德区',
            ),
            357 => 
            array (
                'id' => 56362,
                'area_id' => 440607,
                'name' => '三水区',
            ),
            358 => 
            array (
                'id' => 56389,
                'area_id' => 440608,
                'name' => '高明区',
            ),
            359 => 
            array (
                'id' => 56410,
                'area_id' => 440701,
                'name' => '市辖区',
            ),
            360 => 
            array (
                'id' => 56437,
                'area_id' => 440703,
                'name' => '蓬江区',
            ),
            361 => 
            array (
                'id' => 56464,
                'area_id' => 440704,
                'name' => '江海区',
            ),
            362 => 
            array (
                'id' => 56491,
                'area_id' => 440705,
                'name' => '新会区',
            ),
            363 => 
            array (
                'id' => 56518,
                'area_id' => 440781,
                'name' => '台山市',
            ),
            364 => 
            array (
                'id' => 56545,
                'area_id' => 440783,
                'name' => '开平市',
            ),
            365 => 
            array (
                'id' => 56572,
                'area_id' => 440784,
                'name' => '鹤山市',
            ),
            366 => 
            array (
                'id' => 56599,
                'area_id' => 440785,
                'name' => '恩平市',
            ),
            367 => 
            array (
                'id' => 56618,
                'area_id' => 440801,
                'name' => '市辖区',
            ),
            368 => 
            array (
                'id' => 56645,
                'area_id' => 440802,
                'name' => '赤坎区',
            ),
            369 => 
            array (
                'id' => 56672,
                'area_id' => 440803,
                'name' => '霞山区',
            ),
            370 => 
            array (
                'id' => 56699,
                'area_id' => 440804,
                'name' => '坡头区',
            ),
            371 => 
            array (
                'id' => 56726,
                'area_id' => 440811,
                'name' => '麻章区',
            ),
            372 => 
            array (
                'id' => 56753,
                'area_id' => 440823,
                'name' => '遂溪县',
            ),
            373 => 
            array (
                'id' => 56780,
                'area_id' => 440825,
                'name' => '徐闻县',
            ),
            374 => 
            array (
                'id' => 56807,
                'area_id' => 440881,
                'name' => '廉江市',
            ),
            375 => 
            array (
                'id' => 56834,
                'area_id' => 440882,
                'name' => '雷州市',
            ),
            376 => 
            array (
                'id' => 56861,
                'area_id' => 440883,
                'name' => '吴川市',
            ),
            377 => 
            array (
                'id' => 56878,
                'area_id' => 440901,
                'name' => '市辖区',
            ),
            378 => 
            array (
                'id' => 56905,
                'area_id' => 440902,
                'name' => '茂南区',
            ),
            379 => 
            array (
                'id' => 56932,
                'area_id' => 440903,
                'name' => '茂港区',
            ),
            380 => 
            array (
                'id' => 56959,
                'area_id' => 440923,
                'name' => '电白县',
            ),
            381 => 
            array (
                'id' => 56986,
                'area_id' => 440981,
                'name' => '高州市',
            ),
            382 => 
            array (
                'id' => 57013,
                'area_id' => 440982,
                'name' => '化州市',
            ),
            383 => 
            array (
                'id' => 57040,
                'area_id' => 440983,
                'name' => '信宜市',
            ),
            384 => 
            array (
                'id' => 57060,
                'area_id' => 441201,
                'name' => '市辖区',
            ),
            385 => 
            array (
                'id' => 57087,
                'area_id' => 441202,
                'name' => '端州区',
            ),
            386 => 
            array (
                'id' => 57114,
                'area_id' => 441203,
                'name' => '鼎湖区',
            ),
            387 => 
            array (
                'id' => 57141,
                'area_id' => 441223,
                'name' => '广宁县',
            ),
            388 => 
            array (
                'id' => 57168,
                'area_id' => 441224,
                'name' => '怀集县',
            ),
            389 => 
            array (
                'id' => 57195,
                'area_id' => 441225,
                'name' => '封开县',
            ),
            390 => 
            array (
                'id' => 57222,
                'area_id' => 441226,
                'name' => '德庆县',
            ),
            391 => 
            array (
                'id' => 57249,
                'area_id' => 441283,
                'name' => '高要市',
            ),
            392 => 
            array (
                'id' => 57276,
                'area_id' => 441284,
                'name' => '四会市',
            ),
            393 => 
            array (
                'id' => 57294,
                'area_id' => 441301,
                'name' => '市辖区',
            ),
            394 => 
            array (
                'id' => 57321,
                'area_id' => 441302,
                'name' => '惠城区',
            ),
            395 => 
            array (
                'id' => 57348,
                'area_id' => 441303,
                'name' => '惠阳区',
            ),
            396 => 
            array (
                'id' => 57375,
                'area_id' => 441322,
                'name' => '博罗县',
            ),
            397 => 
            array (
                'id' => 57402,
                'area_id' => 441323,
                'name' => '惠东县',
            ),
            398 => 
            array (
                'id' => 57429,
                'area_id' => 441324,
                'name' => '龙门县',
            ),
            399 => 
            array (
                'id' => 57450,
                'area_id' => 441401,
                'name' => '市辖区',
            ),
            400 => 
            array (
                'id' => 57477,
                'area_id' => 441402,
                'name' => '梅江区',
            ),
            401 => 
            array (
                'id' => 57504,
                'area_id' => 441421,
                'name' => '梅　县',
            ),
            402 => 
            array (
                'id' => 57531,
                'area_id' => 441422,
                'name' => '大埔县',
            ),
            403 => 
            array (
                'id' => 57558,
                'area_id' => 441423,
                'name' => '丰顺县',
            ),
            404 => 
            array (
                'id' => 57585,
                'area_id' => 441424,
                'name' => '五华县',
            ),
            405 => 
            array (
                'id' => 57612,
                'area_id' => 441426,
                'name' => '平远县',
            ),
            406 => 
            array (
                'id' => 57639,
                'area_id' => 441427,
                'name' => '蕉岭县',
            ),
            407 => 
            array (
                'id' => 57666,
                'area_id' => 441481,
                'name' => '兴宁市',
            ),
            408 => 
            array (
                'id' => 57684,
                'area_id' => 441501,
                'name' => '市辖区',
            ),
            409 => 
            array (
                'id' => 57711,
                'area_id' => 441502,
                'name' => '城　区',
            ),
            410 => 
            array (
                'id' => 57738,
                'area_id' => 441521,
                'name' => '海丰县',
            ),
            411 => 
            array (
                'id' => 57765,
                'area_id' => 441523,
                'name' => '陆河县',
            ),
            412 => 
            array (
                'id' => 57792,
                'area_id' => 441581,
                'name' => '陆丰市',
            ),
            413 => 
            array (
                'id' => 57814,
                'area_id' => 441601,
                'name' => '市辖区',
            ),
            414 => 
            array (
                'id' => 57841,
                'area_id' => 441602,
                'name' => '源城区',
            ),
            415 => 
            array (
                'id' => 57868,
                'area_id' => 441621,
                'name' => '紫金县',
            ),
            416 => 
            array (
                'id' => 57895,
                'area_id' => 441622,
                'name' => '龙川县',
            ),
            417 => 
            array (
                'id' => 57922,
                'area_id' => 441623,
                'name' => '连平县',
            ),
            418 => 
            array (
                'id' => 57949,
                'area_id' => 441624,
                'name' => '和平县',
            ),
            419 => 
            array (
                'id' => 57976,
                'area_id' => 441625,
                'name' => '东源县',
            ),
            420 => 
            array (
                'id' => 57996,
                'area_id' => 441701,
                'name' => '市辖区',
            ),
            421 => 
            array (
                'id' => 58023,
                'area_id' => 441702,
                'name' => '江城区',
            ),
            422 => 
            array (
                'id' => 58050,
                'area_id' => 441721,
                'name' => '阳西县',
            ),
            423 => 
            array (
                'id' => 58077,
                'area_id' => 441723,
                'name' => '阳东县',
            ),
            424 => 
            array (
                'id' => 58104,
                'area_id' => 441781,
                'name' => '阳春市',
            ),
            425 => 
            array (
                'id' => 58126,
                'area_id' => 441801,
                'name' => '市辖区',
            ),
            426 => 
            array (
                'id' => 58153,
                'area_id' => 441802,
                'name' => '清城区',
            ),
            427 => 
            array (
                'id' => 58180,
                'area_id' => 441821,
                'name' => '佛冈县',
            ),
            428 => 
            array (
                'id' => 58207,
                'area_id' => 441823,
                'name' => '阳山县',
            ),
            429 => 
            array (
                'id' => 58234,
                'area_id' => 441825,
                'name' => '连山壮族瑶族自治县',
            ),
            430 => 
            array (
                'id' => 58261,
                'area_id' => 441826,
                'name' => '连南瑶族自治县',
            ),
            431 => 
            array (
                'id' => 58288,
                'area_id' => 441827,
                'name' => '清新县',
            ),
            432 => 
            array (
                'id' => 58315,
                'area_id' => 441881,
                'name' => '英德市',
            ),
            433 => 
            array (
                'id' => 58342,
                'area_id' => 441882,
                'name' => '连州市',
            ),
            434 => 
            array (
                'id' => 58360,
                'area_id' => 910006,
                'name' => '东莞市',
            ),
            435 => 
            array (
                'id' => 58386,
                'area_id' => 910005,
                'name' => '中山市',
            ),
            436 => 
            array (
                'id' => 58412,
                'area_id' => 445101,
                'name' => '市辖区',
            ),
            437 => 
            array (
                'id' => 58439,
                'area_id' => 445102,
                'name' => '湘桥区',
            ),
            438 => 
            array (
                'id' => 58466,
                'area_id' => 445121,
                'name' => '潮安县',
            ),
            439 => 
            array (
                'id' => 58493,
                'area_id' => 445122,
                'name' => '饶平县',
            ),
            440 => 
            array (
                'id' => 58516,
                'area_id' => 445201,
                'name' => '市辖区',
            ),
            441 => 
            array (
                'id' => 58543,
                'area_id' => 445202,
                'name' => '榕城区',
            ),
            442 => 
            array (
                'id' => 58570,
                'area_id' => 445221,
                'name' => '揭东县',
            ),
            443 => 
            array (
                'id' => 58597,
                'area_id' => 445222,
                'name' => '揭西县',
            ),
            444 => 
            array (
                'id' => 58624,
                'area_id' => 445224,
                'name' => '惠来县',
            ),
            445 => 
            array (
                'id' => 58651,
                'area_id' => 445281,
                'name' => '普宁市',
            ),
            446 => 
            array (
                'id' => 58672,
                'area_id' => 445301,
                'name' => '市辖区',
            ),
            447 => 
            array (
                'id' => 58699,
                'area_id' => 445302,
                'name' => '云城区',
            ),
            448 => 
            array (
                'id' => 58726,
                'area_id' => 445321,
                'name' => '新兴县',
            ),
            449 => 
            array (
                'id' => 58753,
                'area_id' => 445322,
                'name' => '郁南县',
            ),
            450 => 
            array (
                'id' => 58780,
                'area_id' => 445323,
                'name' => '云安县',
            ),
            451 => 
            array (
                'id' => 58807,
                'area_id' => 445381,
                'name' => '罗定市',
            ),
            452 => 
            array (
                'id' => 58828,
                'area_id' => 450101,
                'name' => '市辖区',
            ),
            453 => 
            array (
                'id' => 58855,
                'area_id' => 450102,
                'name' => '兴宁区',
            ),
            454 => 
            array (
                'id' => 58882,
                'area_id' => 450103,
                'name' => '青秀区',
            ),
            455 => 
            array (
                'id' => 58909,
                'area_id' => 450105,
                'name' => '江南区',
            ),
            456 => 
            array (
                'id' => 58936,
                'area_id' => 450107,
                'name' => '西乡塘区',
            ),
            457 => 
            array (
                'id' => 58963,
                'area_id' => 450108,
                'name' => '良庆区',
            ),
            458 => 
            array (
                'id' => 58990,
                'area_id' => 450109,
                'name' => '邕宁区',
            ),
            459 => 
            array (
                'id' => 59017,
                'area_id' => 450122,
                'name' => '武鸣县',
            ),
            460 => 
            array (
                'id' => 59044,
                'area_id' => 450123,
                'name' => '隆安县',
            ),
            461 => 
            array (
                'id' => 59071,
                'area_id' => 450124,
                'name' => '马山县',
            ),
            462 => 
            array (
                'id' => 59098,
                'area_id' => 450125,
                'name' => '上林县',
            ),
            463 => 
            array (
                'id' => 59125,
                'area_id' => 450126,
                'name' => '宾阳县',
            ),
            464 => 
            array (
                'id' => 59152,
                'area_id' => 450127,
                'name' => '横　县',
            ),
            465 => 
            array (
                'id' => 59166,
                'area_id' => 450201,
                'name' => '市辖区',
            ),
            466 => 
            array (
                'id' => 59193,
                'area_id' => 450202,
                'name' => '城中区',
            ),
            467 => 
            array (
                'id' => 59220,
                'area_id' => 450203,
                'name' => '鱼峰区',
            ),
            468 => 
            array (
                'id' => 59247,
                'area_id' => 450204,
                'name' => '柳南区',
            ),
            469 => 
            array (
                'id' => 59274,
                'area_id' => 450205,
                'name' => '柳北区',
            ),
            470 => 
            array (
                'id' => 59301,
                'area_id' => 450221,
                'name' => '柳江县',
            ),
            471 => 
            array (
                'id' => 59328,
                'area_id' => 450222,
                'name' => '柳城县',
            ),
            472 => 
            array (
                'id' => 59355,
                'area_id' => 450223,
                'name' => '鹿寨县',
            ),
            473 => 
            array (
                'id' => 59382,
                'area_id' => 450224,
                'name' => '融安县',
            ),
            474 => 
            array (
                'id' => 59409,
                'area_id' => 450225,
                'name' => '融水苗族自治县',
            ),
            475 => 
            array (
                'id' => 59436,
                'area_id' => 450226,
                'name' => '三江侗族自治县',
            ),
            476 => 
            array (
                'id' => 59452,
                'area_id' => 450301,
                'name' => '市辖区',
            ),
            477 => 
            array (
                'id' => 59479,
                'area_id' => 450302,
                'name' => '秀峰区',
            ),
            478 => 
            array (
                'id' => 59506,
                'area_id' => 450303,
                'name' => '叠彩区',
            ),
            479 => 
            array (
                'id' => 59533,
                'area_id' => 450304,
                'name' => '象山区',
            ),
            480 => 
            array (
                'id' => 59560,
                'area_id' => 450305,
                'name' => '七星区',
            ),
            481 => 
            array (
                'id' => 59587,
                'area_id' => 450311,
                'name' => '雁山区',
            ),
            482 => 
            array (
                'id' => 59614,
                'area_id' => 450321,
                'name' => '阳朔县',
            ),
            483 => 
            array (
                'id' => 59641,
                'area_id' => 450322,
                'name' => '临桂县',
            ),
            484 => 
            array (
                'id' => 59668,
                'area_id' => 450323,
                'name' => '灵川县',
            ),
            485 => 
            array (
                'id' => 59695,
                'area_id' => 450324,
                'name' => '全州县',
            ),
            486 => 
            array (
                'id' => 59722,
                'area_id' => 450325,
                'name' => '兴安县',
            ),
            487 => 
            array (
                'id' => 59749,
                'area_id' => 450326,
                'name' => '永福县',
            ),
            488 => 
            array (
                'id' => 59776,
                'area_id' => 450327,
                'name' => '灌阳县',
            ),
            489 => 
            array (
                'id' => 59803,
                'area_id' => 450328,
                'name' => '龙胜各族自治县',
            ),
            490 => 
            array (
                'id' => 59830,
                'area_id' => 450329,
                'name' => '资源县',
            ),
            491 => 
            array (
                'id' => 59857,
                'area_id' => 450330,
                'name' => '平乐县',
            ),
            492 => 
            array (
                'id' => 59884,
                'area_id' => 450331,
                'name' => '荔蒲县',
            ),
            493 => 
            array (
                'id' => 59911,
                'area_id' => 450332,
                'name' => '恭城瑶族自治县',
            ),
            494 => 
            array (
                'id' => 59920,
                'area_id' => 450401,
                'name' => '市辖区',
            ),
            495 => 
            array (
                'id' => 59947,
                'area_id' => 450403,
                'name' => '万秀区',
            ),
            496 => 
            array (
                'id' => 59974,
                'area_id' => 450404,
                'name' => '蝶山区',
            ),
            497 => 
            array (
                'id' => 60001,
                'area_id' => 450405,
                'name' => '长洲区',
            ),
            498 => 
            array (
                'id' => 60028,
                'area_id' => 450421,
                'name' => '苍梧县',
            ),
            499 => 
            array (
                'id' => 60055,
                'area_id' => 450422,
                'name' => '藤　县',
            ),
        ));
        \DB::table('areas')->insert(array (
            0 => 
            array (
                'id' => 60082,
                'area_id' => 450423,
                'name' => '蒙山县',
            ),
            1 => 
            array (
                'id' => 60109,
                'area_id' => 450481,
                'name' => '岑溪市',
            ),
            2 => 
            array (
                'id' => 60128,
                'area_id' => 450501,
                'name' => '市辖区',
            ),
            3 => 
            array (
                'id' => 60155,
                'area_id' => 450502,
                'name' => '海城区',
            ),
            4 => 
            array (
                'id' => 60182,
                'area_id' => 450503,
                'name' => '银海区',
            ),
            5 => 
            array (
                'id' => 60209,
                'area_id' => 450512,
                'name' => '铁山港区',
            ),
            6 => 
            array (
                'id' => 60236,
                'area_id' => 450521,
                'name' => '合浦县',
            ),
            7 => 
            array (
                'id' => 60258,
                'area_id' => 450601,
                'name' => '市辖区',
            ),
            8 => 
            array (
                'id' => 60285,
                'area_id' => 450602,
                'name' => '港口区',
            ),
            9 => 
            array (
                'id' => 60312,
                'area_id' => 450603,
                'name' => '防城区',
            ),
            10 => 
            array (
                'id' => 60339,
                'area_id' => 450621,
                'name' => '上思县',
            ),
            11 => 
            array (
                'id' => 60366,
                'area_id' => 450681,
                'name' => '东兴市',
            ),
            12 => 
            array (
                'id' => 60388,
                'area_id' => 450701,
                'name' => '市辖区',
            ),
            13 => 
            array (
                'id' => 60415,
                'area_id' => 450702,
                'name' => '钦南区',
            ),
            14 => 
            array (
                'id' => 60442,
                'area_id' => 450703,
                'name' => '钦北区',
            ),
            15 => 
            array (
                'id' => 60469,
                'area_id' => 450721,
                'name' => '灵山县',
            ),
            16 => 
            array (
                'id' => 60496,
                'area_id' => 450722,
                'name' => '浦北县',
            ),
            17 => 
            array (
                'id' => 60518,
                'area_id' => 450801,
                'name' => '市辖区',
            ),
            18 => 
            array (
                'id' => 60545,
                'area_id' => 450802,
                'name' => '港北区',
            ),
            19 => 
            array (
                'id' => 60572,
                'area_id' => 450803,
                'name' => '港南区',
            ),
            20 => 
            array (
                'id' => 60599,
                'area_id' => 450804,
                'name' => '覃塘区',
            ),
            21 => 
            array (
                'id' => 60626,
                'area_id' => 450821,
                'name' => '平南县',
            ),
            22 => 
            array (
                'id' => 60653,
                'area_id' => 450881,
                'name' => '桂平市',
            ),
            23 => 
            array (
                'id' => 60674,
                'area_id' => 450901,
                'name' => '市辖区',
            ),
            24 => 
            array (
                'id' => 60701,
                'area_id' => 450902,
                'name' => '玉州区',
            ),
            25 => 
            array (
                'id' => 60728,
                'area_id' => 450921,
                'name' => '容　县',
            ),
            26 => 
            array (
                'id' => 60755,
                'area_id' => 450922,
                'name' => '陆川县',
            ),
            27 => 
            array (
                'id' => 60782,
                'area_id' => 450923,
                'name' => '博白县',
            ),
            28 => 
            array (
                'id' => 60809,
                'area_id' => 450924,
                'name' => '兴业县',
            ),
            29 => 
            array (
                'id' => 60836,
                'area_id' => 450981,
                'name' => '北流市',
            ),
            30 => 
            array (
                'id' => 60856,
                'area_id' => 451001,
                'name' => '市辖区',
            ),
            31 => 
            array (
                'id' => 60883,
                'area_id' => 451002,
                'name' => '右江区',
            ),
            32 => 
            array (
                'id' => 60910,
                'area_id' => 451021,
                'name' => '田阳县',
            ),
            33 => 
            array (
                'id' => 60937,
                'area_id' => 451022,
                'name' => '田东县',
            ),
            34 => 
            array (
                'id' => 60964,
                'area_id' => 451023,
                'name' => '平果县',
            ),
            35 => 
            array (
                'id' => 60991,
                'area_id' => 451024,
                'name' => '德保县',
            ),
            36 => 
            array (
                'id' => 61018,
                'area_id' => 451025,
                'name' => '靖西县',
            ),
            37 => 
            array (
                'id' => 61045,
                'area_id' => 451026,
                'name' => '那坡县',
            ),
            38 => 
            array (
                'id' => 61072,
                'area_id' => 451027,
                'name' => '凌云县',
            ),
            39 => 
            array (
                'id' => 61099,
                'area_id' => 451028,
                'name' => '乐业县',
            ),
            40 => 
            array (
                'id' => 61126,
                'area_id' => 451029,
                'name' => '田林县',
            ),
            41 => 
            array (
                'id' => 61153,
                'area_id' => 451030,
                'name' => '西林县',
            ),
            42 => 
            array (
                'id' => 61180,
                'area_id' => 451031,
                'name' => '隆林各族自治县',
            ),
            43 => 
            array (
                'id' => 61194,
                'area_id' => 451101,
                'name' => '市辖区',
            ),
            44 => 
            array (
                'id' => 61221,
                'area_id' => 451102,
                'name' => '八步区',
            ),
            45 => 
            array (
                'id' => 61248,
                'area_id' => 451121,
                'name' => '昭平县',
            ),
            46 => 
            array (
                'id' => 61275,
                'area_id' => 451122,
                'name' => '钟山县',
            ),
            47 => 
            array (
                'id' => 61302,
                'area_id' => 451123,
                'name' => '富川瑶族自治县',
            ),
            48 => 
            array (
                'id' => 61324,
                'area_id' => 451201,
                'name' => '市辖区',
            ),
            49 => 
            array (
                'id' => 61351,
                'area_id' => 451202,
                'name' => '金城江区',
            ),
            50 => 
            array (
                'id' => 61378,
                'area_id' => 451221,
                'name' => '南丹县',
            ),
            51 => 
            array (
                'id' => 61405,
                'area_id' => 451222,
                'name' => '天峨县',
            ),
            52 => 
            array (
                'id' => 61432,
                'area_id' => 451223,
                'name' => '凤山县',
            ),
            53 => 
            array (
                'id' => 61459,
                'area_id' => 451224,
                'name' => '东兰县',
            ),
            54 => 
            array (
                'id' => 61486,
                'area_id' => 451225,
                'name' => '罗城仫佬族自治县',
            ),
            55 => 
            array (
                'id' => 61513,
                'area_id' => 451226,
                'name' => '环江毛南族自治县',
            ),
            56 => 
            array (
                'id' => 61540,
                'area_id' => 451227,
                'name' => '巴马瑶族自治县',
            ),
            57 => 
            array (
                'id' => 61567,
                'area_id' => 451228,
                'name' => '都安瑶族自治县',
            ),
            58 => 
            array (
                'id' => 61594,
                'area_id' => 451229,
                'name' => '大化瑶族自治县',
            ),
            59 => 
            array (
                'id' => 61621,
                'area_id' => 451281,
                'name' => '宜州市',
            ),
            60 => 
            array (
                'id' => 61636,
                'area_id' => 451301,
                'name' => '市辖区',
            ),
            61 => 
            array (
                'id' => 61663,
                'area_id' => 451302,
                'name' => '兴宾区',
            ),
            62 => 
            array (
                'id' => 61690,
                'area_id' => 451321,
                'name' => '忻城县',
            ),
            63 => 
            array (
                'id' => 61717,
                'area_id' => 451322,
                'name' => '象州县',
            ),
            64 => 
            array (
                'id' => 61744,
                'area_id' => 451323,
                'name' => '武宣县',
            ),
            65 => 
            array (
                'id' => 61771,
                'area_id' => 451324,
                'name' => '金秀瑶族自治县',
            ),
            66 => 
            array (
                'id' => 61798,
                'area_id' => 451381,
                'name' => '合山市',
            ),
            67 => 
            array (
                'id' => 61818,
                'area_id' => 451401,
                'name' => '市辖区',
            ),
            68 => 
            array (
                'id' => 61845,
                'area_id' => 451402,
                'name' => '江洲区',
            ),
            69 => 
            array (
                'id' => 61872,
                'area_id' => 451421,
                'name' => '扶绥县',
            ),
            70 => 
            array (
                'id' => 61899,
                'area_id' => 451422,
                'name' => '宁明县',
            ),
            71 => 
            array (
                'id' => 61926,
                'area_id' => 451423,
                'name' => '龙州县',
            ),
            72 => 
            array (
                'id' => 61953,
                'area_id' => 451424,
                'name' => '大新县',
            ),
            73 => 
            array (
                'id' => 61980,
                'area_id' => 451425,
                'name' => '天等县',
            ),
            74 => 
            array (
                'id' => 62007,
                'area_id' => 451481,
                'name' => '凭祥市',
            ),
            75 => 
            array (
                'id' => 62026,
                'area_id' => 460101,
                'name' => '市辖区',
            ),
            76 => 
            array (
                'id' => 62053,
                'area_id' => 460105,
                'name' => '秀英区',
            ),
            77 => 
            array (
                'id' => 62080,
                'area_id' => 460106,
                'name' => '龙华区',
            ),
            78 => 
            array (
                'id' => 62107,
                'area_id' => 460107,
                'name' => '琼山区',
            ),
            79 => 
            array (
                'id' => 62134,
                'area_id' => 460108,
                'name' => '美兰区',
            ),
            80 => 
            array (
                'id' => 62156,
                'area_id' => 460201,
                'name' => '市辖区',
            ),
            81 => 
            array (
                'id' => 62182,
                'area_id' => 469001,
                'name' => '五指山市',
            ),
            82 => 
            array (
                'id' => 62209,
                'area_id' => 469002,
                'name' => '琼海市',
            ),
            83 => 
            array (
                'id' => 62236,
                'area_id' => 469003,
                'name' => '儋州市',
            ),
            84 => 
            array (
                'id' => 62263,
                'area_id' => 469005,
                'name' => '文昌市',
            ),
            85 => 
            array (
                'id' => 62290,
                'area_id' => 469006,
                'name' => '万宁市',
            ),
            86 => 
            array (
                'id' => 62317,
                'area_id' => 469007,
                'name' => '东方市',
            ),
            87 => 
            array (
                'id' => 62344,
                'area_id' => 469025,
                'name' => '定安县',
            ),
            88 => 
            array (
                'id' => 62371,
                'area_id' => 469026,
                'name' => '屯昌县',
            ),
            89 => 
            array (
                'id' => 62398,
                'area_id' => 469027,
                'name' => '澄迈县',
            ),
            90 => 
            array (
                'id' => 62425,
                'area_id' => 469028,
                'name' => '临高县',
            ),
            91 => 
            array (
                'id' => 62452,
                'area_id' => 469030,
                'name' => '白沙黎族自治县',
            ),
            92 => 
            array (
                'id' => 62479,
                'area_id' => 469031,
                'name' => '昌江黎族自治县',
            ),
            93 => 
            array (
                'id' => 62506,
                'area_id' => 469033,
                'name' => '乐东黎族自治县',
            ),
            94 => 
            array (
                'id' => 62533,
                'area_id' => 469034,
                'name' => '陵水黎族自治县',
            ),
            95 => 
            array (
                'id' => 62560,
                'area_id' => 469035,
                'name' => '保亭黎族苗族自治县',
            ),
            96 => 
            array (
                'id' => 62587,
                'area_id' => 469036,
                'name' => '琼中黎族苗族自治县',
            ),
            97 => 
            array (
                'id' => 62614,
                'area_id' => 469037,
                'name' => '西沙群岛',
            ),
            98 => 
            array (
                'id' => 62641,
                'area_id' => 469038,
                'name' => '南沙群岛',
            ),
            99 => 
            array (
                'id' => 62668,
                'area_id' => 469039,
                'name' => '中沙群岛的岛礁及其海域',
            ),
            100 => 
            array (
                'id' => 62676,
                'area_id' => 500101,
                'name' => '万州区',
            ),
            101 => 
            array (
                'id' => 62703,
                'area_id' => 500102,
                'name' => '涪陵区',
            ),
            102 => 
            array (
                'id' => 62730,
                'area_id' => 500103,
                'name' => '渝中区',
            ),
            103 => 
            array (
                'id' => 62757,
                'area_id' => 500104,
                'name' => '大渡口区',
            ),
            104 => 
            array (
                'id' => 62784,
                'area_id' => 500105,
                'name' => '江北区',
            ),
            105 => 
            array (
                'id' => 62811,
                'area_id' => 500106,
                'name' => '沙坪坝区',
            ),
            106 => 
            array (
                'id' => 62838,
                'area_id' => 500107,
                'name' => '九龙坡区',
            ),
            107 => 
            array (
                'id' => 62865,
                'area_id' => 500108,
                'name' => '南岸区',
            ),
            108 => 
            array (
                'id' => 62892,
                'area_id' => 500109,
                'name' => '北碚区',
            ),
            109 => 
            array (
                'id' => 62919,
                'area_id' => 500110,
                'name' => '万盛区',
            ),
            110 => 
            array (
                'id' => 62946,
                'area_id' => 500111,
                'name' => '双桥区',
            ),
            111 => 
            array (
                'id' => 62973,
                'area_id' => 500112,
                'name' => '渝北区',
            ),
            112 => 
            array (
                'id' => 63000,
                'area_id' => 500113,
                'name' => '巴南区',
            ),
            113 => 
            array (
                'id' => 63027,
                'area_id' => 500114,
                'name' => '黔江区',
            ),
            114 => 
            array (
                'id' => 63054,
                'area_id' => 500115,
                'name' => '长寿区',
            ),
            115 => 
            array (
                'id' => 63066,
                'area_id' => 500222,
                'name' => '綦江县',
            ),
            116 => 
            array (
                'id' => 63093,
                'area_id' => 500223,
                'name' => '潼南县',
            ),
            117 => 
            array (
                'id' => 63120,
                'area_id' => 500224,
                'name' => '铜梁县',
            ),
            118 => 
            array (
                'id' => 63147,
                'area_id' => 500225,
                'name' => '大足县',
            ),
            119 => 
            array (
                'id' => 63174,
                'area_id' => 500226,
                'name' => '荣昌县',
            ),
            120 => 
            array (
                'id' => 63201,
                'area_id' => 500227,
                'name' => '璧山县',
            ),
            121 => 
            array (
                'id' => 63228,
                'area_id' => 500228,
                'name' => '梁平县',
            ),
            122 => 
            array (
                'id' => 63255,
                'area_id' => 500229,
                'name' => '城口县',
            ),
            123 => 
            array (
                'id' => 63282,
                'area_id' => 500230,
                'name' => '丰都县',
            ),
            124 => 
            array (
                'id' => 63309,
                'area_id' => 500231,
                'name' => '垫江县',
            ),
            125 => 
            array (
                'id' => 63336,
                'area_id' => 500232,
                'name' => '武隆县',
            ),
            126 => 
            array (
                'id' => 63363,
                'area_id' => 500233,
                'name' => '忠　县',
            ),
            127 => 
            array (
                'id' => 63390,
                'area_id' => 500234,
                'name' => '开　县',
            ),
            128 => 
            array (
                'id' => 63417,
                'area_id' => 500235,
                'name' => '云阳县',
            ),
            129 => 
            array (
                'id' => 63444,
                'area_id' => 500236,
                'name' => '奉节县',
            ),
            130 => 
            array (
                'id' => 63471,
                'area_id' => 500237,
                'name' => '巫山县',
            ),
            131 => 
            array (
                'id' => 63498,
                'area_id' => 500238,
                'name' => '巫溪县',
            ),
            132 => 
            array (
                'id' => 63525,
                'area_id' => 500240,
                'name' => '石柱土家族自治县',
            ),
            133 => 
            array (
                'id' => 63552,
                'area_id' => 500241,
                'name' => '秀山土家族苗族自治县',
            ),
            134 => 
            array (
                'id' => 63579,
                'area_id' => 500242,
                'name' => '酉阳土家族苗族自治县',
            ),
            135 => 
            array (
                'id' => 63606,
                'area_id' => 500243,
                'name' => '彭水苗族土家族自治县',
            ),
            136 => 
            array (
                'id' => 63612,
                'area_id' => 500381,
                'name' => '江津市',
            ),
            137 => 
            array (
                'id' => 63639,
                'area_id' => 500382,
                'name' => '合川市',
            ),
            138 => 
            array (
                'id' => 63666,
                'area_id' => 500383,
                'name' => '永川市',
            ),
            139 => 
            array (
                'id' => 63693,
                'area_id' => 500384,
                'name' => '南川市',
            ),
            140 => 
            array (
                'id' => 63716,
                'area_id' => 510101,
                'name' => '市辖区',
            ),
            141 => 
            array (
                'id' => 63743,
                'area_id' => 510104,
                'name' => '锦江区',
            ),
            142 => 
            array (
                'id' => 63770,
                'area_id' => 510105,
                'name' => '青羊区',
            ),
            143 => 
            array (
                'id' => 63797,
                'area_id' => 510106,
                'name' => '金牛区',
            ),
            144 => 
            array (
                'id' => 63824,
                'area_id' => 510107,
                'name' => '武侯区',
            ),
            145 => 
            array (
                'id' => 63851,
                'area_id' => 510108,
                'name' => '成华区',
            ),
            146 => 
            array (
                'id' => 63878,
                'area_id' => 510112,
                'name' => '龙泉驿区',
            ),
            147 => 
            array (
                'id' => 63905,
                'area_id' => 510113,
                'name' => '青白江区',
            ),
            148 => 
            array (
                'id' => 63932,
                'area_id' => 510114,
                'name' => '新都区',
            ),
            149 => 
            array (
                'id' => 63959,
                'area_id' => 510115,
                'name' => '温江区',
            ),
            150 => 
            array (
                'id' => 63986,
                'area_id' => 510121,
                'name' => '金堂县',
            ),
            151 => 
            array (
                'id' => 64013,
                'area_id' => 510122,
                'name' => '双流县',
            ),
            152 => 
            array (
                'id' => 64040,
                'area_id' => 510124,
                'name' => '郫　县',
            ),
            153 => 
            array (
                'id' => 64067,
                'area_id' => 510129,
                'name' => '大邑县',
            ),
            154 => 
            array (
                'id' => 64094,
                'area_id' => 510131,
                'name' => '蒲江县',
            ),
            155 => 
            array (
                'id' => 64121,
                'area_id' => 510132,
                'name' => '新津县',
            ),
            156 => 
            array (
                'id' => 64148,
                'area_id' => 510181,
                'name' => '都江堰市',
            ),
            157 => 
            array (
                'id' => 64175,
                'area_id' => 510182,
                'name' => '彭州市',
            ),
            158 => 
            array (
                'id' => 64202,
                'area_id' => 510183,
                'name' => '邛崃市',
            ),
            159 => 
            array (
                'id' => 64229,
                'area_id' => 510184,
                'name' => '崇州市',
            ),
            160 => 
            array (
                'id' => 64236,
                'area_id' => 510301,
                'name' => '市辖区',
            ),
            161 => 
            array (
                'id' => 64263,
                'area_id' => 510302,
                'name' => '自流井区',
            ),
            162 => 
            array (
                'id' => 64290,
                'area_id' => 510303,
                'name' => '贡井区',
            ),
            163 => 
            array (
                'id' => 64317,
                'area_id' => 510304,
                'name' => '大安区',
            ),
            164 => 
            array (
                'id' => 64344,
                'area_id' => 510311,
                'name' => '沿滩区',
            ),
            165 => 
            array (
                'id' => 64371,
                'area_id' => 510321,
                'name' => '荣　县',
            ),
            166 => 
            array (
                'id' => 64398,
                'area_id' => 510322,
                'name' => '富顺县',
            ),
            167 => 
            array (
                'id' => 64418,
                'area_id' => 510401,
                'name' => '市辖区',
            ),
            168 => 
            array (
                'id' => 64445,
                'area_id' => 510402,
                'name' => '东　区',
            ),
            169 => 
            array (
                'id' => 64472,
                'area_id' => 510403,
                'name' => '西　区',
            ),
            170 => 
            array (
                'id' => 64499,
                'area_id' => 510411,
                'name' => '仁和区',
            ),
            171 => 
            array (
                'id' => 64526,
                'area_id' => 510421,
                'name' => '米易县',
            ),
            172 => 
            array (
                'id' => 64553,
                'area_id' => 510422,
                'name' => '盐边县',
            ),
            173 => 
            array (
                'id' => 64574,
                'area_id' => 510501,
                'name' => '市辖区',
            ),
            174 => 
            array (
                'id' => 64601,
                'area_id' => 510502,
                'name' => '江阳区',
            ),
            175 => 
            array (
                'id' => 64628,
                'area_id' => 510503,
                'name' => '纳溪区',
            ),
            176 => 
            array (
                'id' => 64655,
                'area_id' => 510504,
                'name' => '龙马潭区',
            ),
            177 => 
            array (
                'id' => 64682,
                'area_id' => 510521,
                'name' => '泸　县',
            ),
            178 => 
            array (
                'id' => 64709,
                'area_id' => 510522,
                'name' => '合江县',
            ),
            179 => 
            array (
                'id' => 64736,
                'area_id' => 510524,
                'name' => '叙永县',
            ),
            180 => 
            array (
                'id' => 64763,
                'area_id' => 510525,
                'name' => '古蔺县',
            ),
            181 => 
            array (
                'id' => 64782,
                'area_id' => 510601,
                'name' => '市辖区',
            ),
            182 => 
            array (
                'id' => 64809,
                'area_id' => 510603,
                'name' => '旌阳区',
            ),
            183 => 
            array (
                'id' => 64836,
                'area_id' => 510623,
                'name' => '中江县',
            ),
            184 => 
            array (
                'id' => 64863,
                'area_id' => 510626,
                'name' => '罗江县',
            ),
            185 => 
            array (
                'id' => 64890,
                'area_id' => 510681,
                'name' => '广汉市',
            ),
            186 => 
            array (
                'id' => 64917,
                'area_id' => 510682,
                'name' => '什邡市',
            ),
            187 => 
            array (
                'id' => 64944,
                'area_id' => 510683,
                'name' => '绵竹市',
            ),
            188 => 
            array (
                'id' => 64964,
                'area_id' => 510701,
                'name' => '市辖区',
            ),
            189 => 
            array (
                'id' => 64991,
                'area_id' => 510703,
                'name' => '涪城区',
            ),
            190 => 
            array (
                'id' => 65018,
                'area_id' => 510704,
                'name' => '游仙区',
            ),
            191 => 
            array (
                'id' => 65045,
                'area_id' => 510722,
                'name' => '三台县',
            ),
            192 => 
            array (
                'id' => 65072,
                'area_id' => 510723,
                'name' => '盐亭县',
            ),
            193 => 
            array (
                'id' => 65099,
                'area_id' => 510724,
                'name' => '安　县',
            ),
            194 => 
            array (
                'id' => 65126,
                'area_id' => 510725,
                'name' => '梓潼县',
            ),
            195 => 
            array (
                'id' => 65153,
                'area_id' => 510726,
                'name' => '北川羌族自治县',
            ),
            196 => 
            array (
                'id' => 65180,
                'area_id' => 510727,
                'name' => '平武县',
            ),
            197 => 
            array (
                'id' => 65207,
                'area_id' => 510781,
                'name' => '江油市',
            ),
            198 => 
            array (
                'id' => 65224,
                'area_id' => 510801,
                'name' => '市辖区',
            ),
            199 => 
            array (
                'id' => 65251,
                'area_id' => 510802,
                'name' => '市中区',
            ),
            200 => 
            array (
                'id' => 65278,
                'area_id' => 510811,
                'name' => '元坝区',
            ),
            201 => 
            array (
                'id' => 65305,
                'area_id' => 510812,
                'name' => '朝天区',
            ),
            202 => 
            array (
                'id' => 65332,
                'area_id' => 510821,
                'name' => '旺苍县',
            ),
            203 => 
            array (
                'id' => 65359,
                'area_id' => 510822,
                'name' => '青川县',
            ),
            204 => 
            array (
                'id' => 65386,
                'area_id' => 510823,
                'name' => '剑阁县',
            ),
            205 => 
            array (
                'id' => 65413,
                'area_id' => 510824,
                'name' => '苍溪县',
            ),
            206 => 
            array (
                'id' => 65432,
                'area_id' => 510901,
                'name' => '市辖区',
            ),
            207 => 
            array (
                'id' => 65459,
                'area_id' => 510903,
                'name' => '船山区',
            ),
            208 => 
            array (
                'id' => 65486,
                'area_id' => 510904,
                'name' => '安居区',
            ),
            209 => 
            array (
                'id' => 65513,
                'area_id' => 510921,
                'name' => '蓬溪县',
            ),
            210 => 
            array (
                'id' => 65540,
                'area_id' => 510922,
                'name' => '射洪县',
            ),
            211 => 
            array (
                'id' => 65567,
                'area_id' => 510923,
                'name' => '大英县',
            ),
            212 => 
            array (
                'id' => 65588,
                'area_id' => 511001,
                'name' => '市辖区',
            ),
            213 => 
            array (
                'id' => 65615,
                'area_id' => 511002,
                'name' => '市中区',
            ),
            214 => 
            array (
                'id' => 65642,
                'area_id' => 511011,
                'name' => '东兴区',
            ),
            215 => 
            array (
                'id' => 65669,
                'area_id' => 511024,
                'name' => '威远县',
            ),
            216 => 
            array (
                'id' => 65696,
                'area_id' => 511025,
                'name' => '资中县',
            ),
            217 => 
            array (
                'id' => 65723,
                'area_id' => 511028,
                'name' => '隆昌县',
            ),
            218 => 
            array (
                'id' => 65744,
                'area_id' => 511101,
                'name' => '市辖区',
            ),
            219 => 
            array (
                'id' => 65771,
                'area_id' => 511102,
                'name' => '市中区',
            ),
            220 => 
            array (
                'id' => 65798,
                'area_id' => 511111,
                'name' => '沙湾区',
            ),
            221 => 
            array (
                'id' => 65825,
                'area_id' => 511112,
                'name' => '五通桥区',
            ),
            222 => 
            array (
                'id' => 65852,
                'area_id' => 511113,
                'name' => '金口河区',
            ),
            223 => 
            array (
                'id' => 65879,
                'area_id' => 511123,
                'name' => '犍为县',
            ),
            224 => 
            array (
                'id' => 65906,
                'area_id' => 511124,
                'name' => '井研县',
            ),
            225 => 
            array (
                'id' => 65933,
                'area_id' => 511126,
                'name' => '夹江县',
            ),
            226 => 
            array (
                'id' => 65960,
                'area_id' => 511129,
                'name' => '沐川县',
            ),
            227 => 
            array (
                'id' => 65987,
                'area_id' => 511132,
                'name' => '峨边彝族自治县',
            ),
            228 => 
            array (
                'id' => 66014,
                'area_id' => 511133,
                'name' => '马边彝族自治县',
            ),
            229 => 
            array (
                'id' => 66041,
                'area_id' => 511181,
                'name' => '峨眉山市',
            ),
            230 => 
            array (
                'id' => 66056,
                'area_id' => 511301,
                'name' => '市辖区',
            ),
            231 => 
            array (
                'id' => 66083,
                'area_id' => 511302,
                'name' => '顺庆区',
            ),
            232 => 
            array (
                'id' => 66110,
                'area_id' => 511303,
                'name' => '高坪区',
            ),
            233 => 
            array (
                'id' => 66137,
                'area_id' => 511304,
                'name' => '嘉陵区',
            ),
            234 => 
            array (
                'id' => 66164,
                'area_id' => 511321,
                'name' => '南部县',
            ),
            235 => 
            array (
                'id' => 66191,
                'area_id' => 511322,
                'name' => '营山县',
            ),
            236 => 
            array (
                'id' => 66218,
                'area_id' => 511323,
                'name' => '蓬安县',
            ),
            237 => 
            array (
                'id' => 66245,
                'area_id' => 511324,
                'name' => '仪陇县',
            ),
            238 => 
            array (
                'id' => 66272,
                'area_id' => 511325,
                'name' => '西充县',
            ),
            239 => 
            array (
                'id' => 66299,
                'area_id' => 511381,
                'name' => '阆中市',
            ),
            240 => 
            array (
                'id' => 66316,
                'area_id' => 511401,
                'name' => '市辖区',
            ),
            241 => 
            array (
                'id' => 66343,
                'area_id' => 511402,
                'name' => '东坡区',
            ),
            242 => 
            array (
                'id' => 66370,
                'area_id' => 511421,
                'name' => '仁寿县',
            ),
            243 => 
            array (
                'id' => 66397,
                'area_id' => 511422,
                'name' => '彭山县',
            ),
            244 => 
            array (
                'id' => 66424,
                'area_id' => 511423,
                'name' => '洪雅县',
            ),
            245 => 
            array (
                'id' => 66451,
                'area_id' => 511424,
                'name' => '丹棱县',
            ),
            246 => 
            array (
                'id' => 66478,
                'area_id' => 511425,
                'name' => '青神县',
            ),
            247 => 
            array (
                'id' => 66498,
                'area_id' => 511501,
                'name' => '市辖区',
            ),
            248 => 
            array (
                'id' => 66525,
                'area_id' => 511502,
                'name' => '翠屏区',
            ),
            249 => 
            array (
                'id' => 66552,
                'area_id' => 511521,
                'name' => '宜宾县',
            ),
            250 => 
            array (
                'id' => 66579,
                'area_id' => 511522,
                'name' => '南溪县',
            ),
            251 => 
            array (
                'id' => 66606,
                'area_id' => 511523,
                'name' => '江安县',
            ),
            252 => 
            array (
                'id' => 66633,
                'area_id' => 511524,
                'name' => '长宁县',
            ),
            253 => 
            array (
                'id' => 66660,
                'area_id' => 511525,
                'name' => '高　县',
            ),
            254 => 
            array (
                'id' => 66687,
                'area_id' => 511526,
                'name' => '珙　县',
            ),
            255 => 
            array (
                'id' => 66714,
                'area_id' => 511527,
                'name' => '筠连县',
            ),
            256 => 
            array (
                'id' => 66741,
                'area_id' => 511528,
                'name' => '兴文县',
            ),
            257 => 
            array (
                'id' => 66768,
                'area_id' => 511529,
                'name' => '屏山县',
            ),
            258 => 
            array (
                'id' => 66784,
                'area_id' => 511601,
                'name' => '市辖区',
            ),
            259 => 
            array (
                'id' => 66811,
                'area_id' => 511602,
                'name' => '广安区',
            ),
            260 => 
            array (
                'id' => 66838,
                'area_id' => 511621,
                'name' => '岳池县',
            ),
            261 => 
            array (
                'id' => 66865,
                'area_id' => 511622,
                'name' => '武胜县',
            ),
            262 => 
            array (
                'id' => 66892,
                'area_id' => 511623,
                'name' => '邻水县',
            ),
            263 => 
            array (
                'id' => 66919,
                'area_id' => 511681,
                'name' => '华莹市',
            ),
            264 => 
            array (
                'id' => 66940,
                'area_id' => 511701,
                'name' => '市辖区',
            ),
            265 => 
            array (
                'id' => 66967,
                'area_id' => 511702,
                'name' => '通川区',
            ),
            266 => 
            array (
                'id' => 66994,
                'area_id' => 511721,
                'name' => '达　县',
            ),
            267 => 
            array (
                'id' => 67021,
                'area_id' => 511722,
                'name' => '宣汉县',
            ),
            268 => 
            array (
                'id' => 67048,
                'area_id' => 511723,
                'name' => '开江县',
            ),
            269 => 
            array (
                'id' => 67075,
                'area_id' => 511724,
                'name' => '大竹县',
            ),
            270 => 
            array (
                'id' => 67102,
                'area_id' => 511725,
                'name' => '渠　县',
            ),
            271 => 
            array (
                'id' => 67129,
                'area_id' => 511781,
                'name' => '万源市',
            ),
            272 => 
            array (
                'id' => 67148,
                'area_id' => 511801,
                'name' => '市辖区',
            ),
            273 => 
            array (
                'id' => 67175,
                'area_id' => 511802,
                'name' => '雨城区',
            ),
            274 => 
            array (
                'id' => 67202,
                'area_id' => 511821,
                'name' => '名山县',
            ),
            275 => 
            array (
                'id' => 67229,
                'area_id' => 511822,
                'name' => '荥经县',
            ),
            276 => 
            array (
                'id' => 67256,
                'area_id' => 511823,
                'name' => '汉源县',
            ),
            277 => 
            array (
                'id' => 67283,
                'area_id' => 511824,
                'name' => '石棉县',
            ),
            278 => 
            array (
                'id' => 67310,
                'area_id' => 511825,
                'name' => '天全县',
            ),
            279 => 
            array (
                'id' => 67337,
                'area_id' => 511826,
                'name' => '芦山县',
            ),
            280 => 
            array (
                'id' => 67364,
                'area_id' => 511827,
                'name' => '宝兴县',
            ),
            281 => 
            array (
                'id' => 67382,
                'area_id' => 511901,
                'name' => '市辖区',
            ),
            282 => 
            array (
                'id' => 67409,
                'area_id' => 511902,
                'name' => '巴州区',
            ),
            283 => 
            array (
                'id' => 67436,
                'area_id' => 511921,
                'name' => '通江县',
            ),
            284 => 
            array (
                'id' => 67463,
                'area_id' => 511922,
                'name' => '南江县',
            ),
            285 => 
            array (
                'id' => 67490,
                'area_id' => 511923,
                'name' => '平昌县',
            ),
            286 => 
            array (
                'id' => 67512,
                'area_id' => 512001,
                'name' => '市辖区',
            ),
            287 => 
            array (
                'id' => 67539,
                'area_id' => 512002,
                'name' => '雁江区',
            ),
            288 => 
            array (
                'id' => 67566,
                'area_id' => 512021,
                'name' => '安岳县',
            ),
            289 => 
            array (
                'id' => 67593,
                'area_id' => 512022,
                'name' => '乐至县',
            ),
            290 => 
            array (
                'id' => 67620,
                'area_id' => 512081,
                'name' => '简阳市',
            ),
            291 => 
            array (
                'id' => 67642,
                'area_id' => 513221,
                'name' => '汶川县',
            ),
            292 => 
            array (
                'id' => 67669,
                'area_id' => 513222,
                'name' => '理　县',
            ),
            293 => 
            array (
                'id' => 67696,
                'area_id' => 513223,
                'name' => '茂　县',
            ),
            294 => 
            array (
                'id' => 67723,
                'area_id' => 513224,
                'name' => '松潘县',
            ),
            295 => 
            array (
                'id' => 67750,
                'area_id' => 513225,
                'name' => '九寨沟县',
            ),
            296 => 
            array (
                'id' => 67777,
                'area_id' => 513226,
                'name' => '金川县',
            ),
            297 => 
            array (
                'id' => 67804,
                'area_id' => 513227,
                'name' => '小金县',
            ),
            298 => 
            array (
                'id' => 67831,
                'area_id' => 513228,
                'name' => '黑水县',
            ),
            299 => 
            array (
                'id' => 67858,
                'area_id' => 513229,
                'name' => '马尔康县',
            ),
            300 => 
            array (
                'id' => 67885,
                'area_id' => 513230,
                'name' => '壤塘县',
            ),
            301 => 
            array (
                'id' => 67912,
                'area_id' => 513231,
                'name' => '阿坝县',
            ),
            302 => 
            array (
                'id' => 67939,
                'area_id' => 513232,
                'name' => '若尔盖县',
            ),
            303 => 
            array (
                'id' => 67966,
                'area_id' => 513233,
                'name' => '红原县',
            ),
            304 => 
            array (
                'id' => 67980,
                'area_id' => 513321,
                'name' => '康定县',
            ),
            305 => 
            array (
                'id' => 68007,
                'area_id' => 513322,
                'name' => '泸定县',
            ),
            306 => 
            array (
                'id' => 68034,
                'area_id' => 513323,
                'name' => '丹巴县',
            ),
            307 => 
            array (
                'id' => 68061,
                'area_id' => 513324,
                'name' => '九龙县',
            ),
            308 => 
            array (
                'id' => 68088,
                'area_id' => 513325,
                'name' => '雅江县',
            ),
            309 => 
            array (
                'id' => 68115,
                'area_id' => 513326,
                'name' => '道孚县',
            ),
            310 => 
            array (
                'id' => 68142,
                'area_id' => 513327,
                'name' => '炉霍县',
            ),
            311 => 
            array (
                'id' => 68169,
                'area_id' => 513328,
                'name' => '甘孜县',
            ),
            312 => 
            array (
                'id' => 68196,
                'area_id' => 513329,
                'name' => '新龙县',
            ),
            313 => 
            array (
                'id' => 68223,
                'area_id' => 513330,
                'name' => '德格县',
            ),
            314 => 
            array (
                'id' => 68250,
                'area_id' => 513331,
                'name' => '白玉县',
            ),
            315 => 
            array (
                'id' => 68277,
                'area_id' => 513332,
                'name' => '石渠县',
            ),
            316 => 
            array (
                'id' => 68304,
                'area_id' => 513333,
                'name' => '色达县',
            ),
            317 => 
            array (
                'id' => 68331,
                'area_id' => 513334,
                'name' => '理塘县',
            ),
            318 => 
            array (
                'id' => 68358,
                'area_id' => 513335,
                'name' => '巴塘县',
            ),
            319 => 
            array (
                'id' => 68385,
                'area_id' => 513336,
                'name' => '乡城县',
            ),
            320 => 
            array (
                'id' => 68412,
                'area_id' => 513337,
                'name' => '稻城县',
            ),
            321 => 
            array (
                'id' => 68439,
                'area_id' => 513338,
                'name' => '得荣县',
            ),
            322 => 
            array (
                'id' => 68448,
                'area_id' => 513401,
                'name' => '西昌市',
            ),
            323 => 
            array (
                'id' => 68475,
                'area_id' => 513422,
                'name' => '木里藏族自治县',
            ),
            324 => 
            array (
                'id' => 68502,
                'area_id' => 513423,
                'name' => '盐源县',
            ),
            325 => 
            array (
                'id' => 68529,
                'area_id' => 513424,
                'name' => '德昌县',
            ),
            326 => 
            array (
                'id' => 68556,
                'area_id' => 513425,
                'name' => '会理县',
            ),
            327 => 
            array (
                'id' => 68583,
                'area_id' => 513426,
                'name' => '会东县',
            ),
            328 => 
            array (
                'id' => 68610,
                'area_id' => 513427,
                'name' => '宁南县',
            ),
            329 => 
            array (
                'id' => 68637,
                'area_id' => 513428,
                'name' => '普格县',
            ),
            330 => 
            array (
                'id' => 68664,
                'area_id' => 513429,
                'name' => '布拖县',
            ),
            331 => 
            array (
                'id' => 68691,
                'area_id' => 513430,
                'name' => '金阳县',
            ),
            332 => 
            array (
                'id' => 68718,
                'area_id' => 513431,
                'name' => '昭觉县',
            ),
            333 => 
            array (
                'id' => 68745,
                'area_id' => 513432,
                'name' => '喜德县',
            ),
            334 => 
            array (
                'id' => 68772,
                'area_id' => 513433,
                'name' => '冕宁县',
            ),
            335 => 
            array (
                'id' => 68799,
                'area_id' => 513434,
                'name' => '越西县',
            ),
            336 => 
            array (
                'id' => 68826,
                'area_id' => 513435,
                'name' => '甘洛县',
            ),
            337 => 
            array (
                'id' => 68853,
                'area_id' => 513436,
                'name' => '美姑县',
            ),
            338 => 
            array (
                'id' => 68880,
                'area_id' => 513437,
                'name' => '雷波县',
            ),
            339 => 
            array (
                'id' => 68890,
                'area_id' => 520101,
                'name' => '市辖区',
            ),
            340 => 
            array (
                'id' => 68917,
                'area_id' => 520102,
                'name' => '南明区',
            ),
            341 => 
            array (
                'id' => 68944,
                'area_id' => 520103,
                'name' => '云岩区',
            ),
            342 => 
            array (
                'id' => 68971,
                'area_id' => 520111,
                'name' => '花溪区',
            ),
            343 => 
            array (
                'id' => 68998,
                'area_id' => 520112,
                'name' => '乌当区',
            ),
            344 => 
            array (
                'id' => 69025,
                'area_id' => 520113,
                'name' => '白云区',
            ),
            345 => 
            array (
                'id' => 69052,
                'area_id' => 520114,
                'name' => '小河区',
            ),
            346 => 
            array (
                'id' => 69079,
                'area_id' => 520121,
                'name' => '开阳县',
            ),
            347 => 
            array (
                'id' => 69106,
                'area_id' => 520122,
                'name' => '息烽县',
            ),
            348 => 
            array (
                'id' => 69133,
                'area_id' => 520123,
                'name' => '修文县',
            ),
            349 => 
            array (
                'id' => 69160,
                'area_id' => 520181,
                'name' => '清镇市',
            ),
            350 => 
            array (
                'id' => 69176,
                'area_id' => 520201,
                'name' => '钟山区',
            ),
            351 => 
            array (
                'id' => 69203,
                'area_id' => 520203,
                'name' => '六枝特区',
            ),
            352 => 
            array (
                'id' => 69230,
                'area_id' => 520221,
                'name' => '水城县',
            ),
            353 => 
            array (
                'id' => 69257,
                'area_id' => 520222,
                'name' => '盘　县',
            ),
            354 => 
            array (
                'id' => 69280,
                'area_id' => 520301,
                'name' => '市辖区',
            ),
            355 => 
            array (
                'id' => 69307,
                'area_id' => 520302,
                'name' => '红花岗区',
            ),
            356 => 
            array (
                'id' => 69334,
                'area_id' => 520303,
                'name' => '汇川区',
            ),
            357 => 
            array (
                'id' => 69361,
                'area_id' => 520321,
                'name' => '遵义县',
            ),
            358 => 
            array (
                'id' => 69388,
                'area_id' => 520322,
                'name' => '桐梓县',
            ),
            359 => 
            array (
                'id' => 69415,
                'area_id' => 520323,
                'name' => '绥阳县',
            ),
            360 => 
            array (
                'id' => 69442,
                'area_id' => 520324,
                'name' => '正安县',
            ),
            361 => 
            array (
                'id' => 69469,
                'area_id' => 520325,
                'name' => '道真仡佬族苗族自治县',
            ),
            362 => 
            array (
                'id' => 69496,
                'area_id' => 520326,
                'name' => '务川仡佬族苗族自治县',
            ),
            363 => 
            array (
                'id' => 69523,
                'area_id' => 520327,
                'name' => '凤冈县',
            ),
            364 => 
            array (
                'id' => 69550,
                'area_id' => 520328,
                'name' => '湄潭县',
            ),
            365 => 
            array (
                'id' => 69577,
                'area_id' => 520329,
                'name' => '余庆县',
            ),
            366 => 
            array (
                'id' => 69604,
                'area_id' => 520330,
                'name' => '习水县',
            ),
            367 => 
            array (
                'id' => 69631,
                'area_id' => 520381,
                'name' => '赤水市',
            ),
            368 => 
            array (
                'id' => 69658,
                'area_id' => 520382,
                'name' => '仁怀市',
            ),
            369 => 
            array (
                'id' => 69670,
                'area_id' => 520401,
                'name' => '市辖区',
            ),
            370 => 
            array (
                'id' => 69697,
                'area_id' => 520402,
                'name' => '西秀区',
            ),
            371 => 
            array (
                'id' => 69724,
                'area_id' => 520421,
                'name' => '平坝县',
            ),
            372 => 
            array (
                'id' => 69751,
                'area_id' => 520422,
                'name' => '普定县',
            ),
            373 => 
            array (
                'id' => 69778,
                'area_id' => 520423,
                'name' => '镇宁布依族苗族自治县',
            ),
            374 => 
            array (
                'id' => 69805,
                'area_id' => 520424,
                'name' => '关岭布依族苗族自治县',
            ),
            375 => 
            array (
                'id' => 69832,
                'area_id' => 520425,
                'name' => '紫云苗族布依族自治县',
            ),
            376 => 
            array (
                'id' => 69852,
                'area_id' => 522201,
                'name' => '铜仁市',
            ),
            377 => 
            array (
                'id' => 69879,
                'area_id' => 522222,
                'name' => '江口县',
            ),
            378 => 
            array (
                'id' => 69906,
                'area_id' => 522223,
                'name' => '玉屏侗族自治县',
            ),
            379 => 
            array (
                'id' => 69933,
                'area_id' => 522224,
                'name' => '石阡县',
            ),
            380 => 
            array (
                'id' => 69960,
                'area_id' => 522225,
                'name' => '思南县',
            ),
            381 => 
            array (
                'id' => 69987,
                'area_id' => 522226,
                'name' => '印江土家族苗族自治县',
            ),
            382 => 
            array (
                'id' => 70014,
                'area_id' => 522227,
                'name' => '德江县',
            ),
            383 => 
            array (
                'id' => 70041,
                'area_id' => 522228,
                'name' => '沿河土家族自治县',
            ),
            384 => 
            array (
                'id' => 70068,
                'area_id' => 522229,
                'name' => '松桃苗族自治县',
            ),
            385 => 
            array (
                'id' => 70095,
                'area_id' => 522230,
                'name' => '万山特区',
            ),
            386 => 
            array (
                'id' => 70112,
                'area_id' => 522301,
                'name' => '兴义市',
            ),
            387 => 
            array (
                'id' => 70139,
                'area_id' => 522322,
                'name' => '兴仁县',
            ),
            388 => 
            array (
                'id' => 70166,
                'area_id' => 522323,
                'name' => '普安县',
            ),
            389 => 
            array (
                'id' => 70193,
                'area_id' => 522324,
                'name' => '晴隆县',
            ),
            390 => 
            array (
                'id' => 70220,
                'area_id' => 522325,
                'name' => '贞丰县',
            ),
            391 => 
            array (
                'id' => 70247,
                'area_id' => 522326,
                'name' => '望谟县',
            ),
            392 => 
            array (
                'id' => 70274,
                'area_id' => 522327,
                'name' => '册亨县',
            ),
            393 => 
            array (
                'id' => 70301,
                'area_id' => 522328,
                'name' => '安龙县',
            ),
            394 => 
            array (
                'id' => 70320,
                'area_id' => 522401,
                'name' => '毕节市',
            ),
            395 => 
            array (
                'id' => 70347,
                'area_id' => 522422,
                'name' => '大方县',
            ),
            396 => 
            array (
                'id' => 70374,
                'area_id' => 522423,
                'name' => '黔西县',
            ),
            397 => 
            array (
                'id' => 70401,
                'area_id' => 522424,
                'name' => '金沙县',
            ),
            398 => 
            array (
                'id' => 70428,
                'area_id' => 522425,
                'name' => '织金县',
            ),
            399 => 
            array (
                'id' => 70455,
                'area_id' => 522426,
                'name' => '纳雍县',
            ),
            400 => 
            array (
                'id' => 70482,
                'area_id' => 522427,
                'name' => '威宁彝族回族苗族自治县',
            ),
            401 => 
            array (
                'id' => 70509,
                'area_id' => 522428,
                'name' => '赫章县',
            ),
            402 => 
            array (
                'id' => 70528,
                'area_id' => 522601,
                'name' => '凯里市',
            ),
            403 => 
            array (
                'id' => 70555,
                'area_id' => 522622,
                'name' => '黄平县',
            ),
            404 => 
            array (
                'id' => 70582,
                'area_id' => 522623,
                'name' => '施秉县',
            ),
            405 => 
            array (
                'id' => 70609,
                'area_id' => 522624,
                'name' => '三穗县',
            ),
            406 => 
            array (
                'id' => 70636,
                'area_id' => 522625,
                'name' => '镇远县',
            ),
            407 => 
            array (
                'id' => 70663,
                'area_id' => 522626,
                'name' => '岑巩县',
            ),
            408 => 
            array (
                'id' => 70690,
                'area_id' => 522627,
                'name' => '天柱县',
            ),
            409 => 
            array (
                'id' => 70717,
                'area_id' => 522628,
                'name' => '锦屏县',
            ),
            410 => 
            array (
                'id' => 70744,
                'area_id' => 522629,
                'name' => '剑河县',
            ),
            411 => 
            array (
                'id' => 70771,
                'area_id' => 522630,
                'name' => '台江县',
            ),
            412 => 
            array (
                'id' => 70798,
                'area_id' => 522631,
                'name' => '黎平县',
            ),
            413 => 
            array (
                'id' => 70825,
                'area_id' => 522632,
                'name' => '榕江县',
            ),
            414 => 
            array (
                'id' => 70852,
                'area_id' => 522633,
                'name' => '从江县',
            ),
            415 => 
            array (
                'id' => 70879,
                'area_id' => 522634,
                'name' => '雷山县',
            ),
            416 => 
            array (
                'id' => 70906,
                'area_id' => 522635,
                'name' => '麻江县',
            ),
            417 => 
            array (
                'id' => 70933,
                'area_id' => 522636,
                'name' => '丹寨县',
            ),
            418 => 
            array (
                'id' => 70944,
                'area_id' => 522701,
                'name' => '都匀市',
            ),
            419 => 
            array (
                'id' => 70971,
                'area_id' => 522702,
                'name' => '福泉市',
            ),
            420 => 
            array (
                'id' => 70998,
                'area_id' => 522722,
                'name' => '荔波县',
            ),
            421 => 
            array (
                'id' => 71025,
                'area_id' => 522723,
                'name' => '贵定县',
            ),
            422 => 
            array (
                'id' => 71052,
                'area_id' => 522725,
                'name' => '瓮安县',
            ),
            423 => 
            array (
                'id' => 71079,
                'area_id' => 522726,
                'name' => '独山县',
            ),
            424 => 
            array (
                'id' => 71106,
                'area_id' => 522727,
                'name' => '平塘县',
            ),
            425 => 
            array (
                'id' => 71133,
                'area_id' => 522728,
                'name' => '罗甸县',
            ),
            426 => 
            array (
                'id' => 71160,
                'area_id' => 522729,
                'name' => '长顺县',
            ),
            427 => 
            array (
                'id' => 71187,
                'area_id' => 522730,
                'name' => '龙里县',
            ),
            428 => 
            array (
                'id' => 71214,
                'area_id' => 522731,
                'name' => '惠水县',
            ),
            429 => 
            array (
                'id' => 71241,
                'area_id' => 522732,
                'name' => '三都水族自治县',
            ),
            430 => 
            array (
                'id' => 71256,
                'area_id' => 530101,
                'name' => '市辖区',
            ),
            431 => 
            array (
                'id' => 71283,
                'area_id' => 530102,
                'name' => '五华区',
            ),
            432 => 
            array (
                'id' => 71310,
                'area_id' => 530103,
                'name' => '盘龙区',
            ),
            433 => 
            array (
                'id' => 71337,
                'area_id' => 530111,
                'name' => '官渡区',
            ),
            434 => 
            array (
                'id' => 71364,
                'area_id' => 530112,
                'name' => '西山区',
            ),
            435 => 
            array (
                'id' => 71391,
                'area_id' => 530113,
                'name' => '东川区',
            ),
            436 => 
            array (
                'id' => 71418,
                'area_id' => 530121,
                'name' => '呈贡县',
            ),
            437 => 
            array (
                'id' => 71445,
                'area_id' => 530122,
                'name' => '晋宁县',
            ),
            438 => 
            array (
                'id' => 71472,
                'area_id' => 530124,
                'name' => '富民县',
            ),
            439 => 
            array (
                'id' => 71499,
                'area_id' => 530125,
                'name' => '宜良县',
            ),
            440 => 
            array (
                'id' => 71526,
                'area_id' => 530126,
                'name' => '石林彝族自治县',
            ),
            441 => 
            array (
                'id' => 71553,
                'area_id' => 530127,
                'name' => '嵩明县',
            ),
            442 => 
            array (
                'id' => 71580,
                'area_id' => 530128,
                'name' => '禄劝彝族苗族自治县',
            ),
            443 => 
            array (
                'id' => 71607,
                'area_id' => 530129,
                'name' => '寻甸回族彝族自治县',
            ),
            444 => 
            array (
                'id' => 71634,
                'area_id' => 530181,
                'name' => '安宁市',
            ),
            445 => 
            array (
                'id' => 71646,
                'area_id' => 530301,
                'name' => '市辖区',
            ),
            446 => 
            array (
                'id' => 71673,
                'area_id' => 530302,
                'name' => '麒麟区',
            ),
            447 => 
            array (
                'id' => 71700,
                'area_id' => 530321,
                'name' => '马龙县',
            ),
            448 => 
            array (
                'id' => 71727,
                'area_id' => 530322,
                'name' => '陆良县',
            ),
            449 => 
            array (
                'id' => 71754,
                'area_id' => 530323,
                'name' => '师宗县',
            ),
            450 => 
            array (
                'id' => 71781,
                'area_id' => 530324,
                'name' => '罗平县',
            ),
            451 => 
            array (
                'id' => 71808,
                'area_id' => 530325,
                'name' => '富源县',
            ),
            452 => 
            array (
                'id' => 71835,
                'area_id' => 530326,
                'name' => '会泽县',
            ),
            453 => 
            array (
                'id' => 71862,
                'area_id' => 530328,
                'name' => '沾益县',
            ),
            454 => 
            array (
                'id' => 71889,
                'area_id' => 530381,
                'name' => '宣威市',
            ),
            455 => 
            array (
                'id' => 71906,
                'area_id' => 530401,
                'name' => '市辖区',
            ),
            456 => 
            array (
                'id' => 71933,
                'area_id' => 530402,
                'name' => '红塔区',
            ),
            457 => 
            array (
                'id' => 71960,
                'area_id' => 530421,
                'name' => '江川县',
            ),
            458 => 
            array (
                'id' => 71987,
                'area_id' => 530422,
                'name' => '澄江县',
            ),
            459 => 
            array (
                'id' => 72014,
                'area_id' => 530423,
                'name' => '通海县',
            ),
            460 => 
            array (
                'id' => 72041,
                'area_id' => 530424,
                'name' => '华宁县',
            ),
            461 => 
            array (
                'id' => 72068,
                'area_id' => 530425,
                'name' => '易门县',
            ),
            462 => 
            array (
                'id' => 72095,
                'area_id' => 530426,
                'name' => '峨山彝族自治县',
            ),
            463 => 
            array (
                'id' => 72122,
                'area_id' => 530427,
                'name' => '新平彝族傣族自治县',
            ),
            464 => 
            array (
                'id' => 72149,
                'area_id' => 530428,
                'name' => '元江哈尼族彝族傣族自治县',
            ),
            465 => 
            array (
                'id' => 72166,
                'area_id' => 530501,
                'name' => '市辖区',
            ),
            466 => 
            array (
                'id' => 72193,
                'area_id' => 530502,
                'name' => '隆阳区',
            ),
            467 => 
            array (
                'id' => 72220,
                'area_id' => 530521,
                'name' => '施甸县',
            ),
            468 => 
            array (
                'id' => 72247,
                'area_id' => 530522,
                'name' => '腾冲县',
            ),
            469 => 
            array (
                'id' => 72274,
                'area_id' => 530523,
                'name' => '龙陵县',
            ),
            470 => 
            array (
                'id' => 72301,
                'area_id' => 530524,
                'name' => '昌宁县',
            ),
            471 => 
            array (
                'id' => 72322,
                'area_id' => 530601,
                'name' => '市辖区',
            ),
            472 => 
            array (
                'id' => 72349,
                'area_id' => 530602,
                'name' => '昭阳区',
            ),
            473 => 
            array (
                'id' => 72376,
                'area_id' => 530621,
                'name' => '鲁甸县',
            ),
            474 => 
            array (
                'id' => 72403,
                'area_id' => 530622,
                'name' => '巧家县',
            ),
            475 => 
            array (
                'id' => 72430,
                'area_id' => 530623,
                'name' => '盐津县',
            ),
            476 => 
            array (
                'id' => 72457,
                'area_id' => 530624,
                'name' => '大关县',
            ),
            477 => 
            array (
                'id' => 72484,
                'area_id' => 530625,
                'name' => '永善县',
            ),
            478 => 
            array (
                'id' => 72511,
                'area_id' => 530626,
                'name' => '绥江县',
            ),
            479 => 
            array (
                'id' => 72538,
                'area_id' => 530627,
                'name' => '镇雄县',
            ),
            480 => 
            array (
                'id' => 72565,
                'area_id' => 530628,
                'name' => '彝良县',
            ),
            481 => 
            array (
                'id' => 72592,
                'area_id' => 530629,
                'name' => '威信县',
            ),
            482 => 
            array (
                'id' => 72619,
                'area_id' => 530630,
                'name' => '水富县',
            ),
            483 => 
            array (
                'id' => 72634,
                'area_id' => 530701,
                'name' => '市辖区',
            ),
            484 => 
            array (
                'id' => 72661,
                'area_id' => 530702,
                'name' => '古城区',
            ),
            485 => 
            array (
                'id' => 72688,
                'area_id' => 530721,
                'name' => '玉龙纳西族自治县',
            ),
            486 => 
            array (
                'id' => 72715,
                'area_id' => 530722,
                'name' => '永胜县',
            ),
            487 => 
            array (
                'id' => 72742,
                'area_id' => 530723,
                'name' => '华坪县',
            ),
            488 => 
            array (
                'id' => 72769,
                'area_id' => 530724,
                'name' => '宁蒗彝族自治县',
            ),
            489 => 
            array (
                'id' => 72790,
                'area_id' => 530801,
                'name' => '市辖区',
            ),
            490 => 
            array (
                'id' => 72817,
                'area_id' => 530802,
                'name' => '翠云区',
            ),
            491 => 
            array (
                'id' => 72844,
                'area_id' => 530821,
                'name' => '普洱哈尼族彝族自治县',
            ),
            492 => 
            array (
                'id' => 72871,
                'area_id' => 530822,
                'name' => '墨江哈尼族自治县',
            ),
            493 => 
            array (
                'id' => 72898,
                'area_id' => 530823,
                'name' => '景东彝族自治县',
            ),
            494 => 
            array (
                'id' => 72925,
                'area_id' => 530824,
                'name' => '景谷傣族彝族自治县',
            ),
            495 => 
            array (
                'id' => 72952,
                'area_id' => 530825,
                'name' => '镇沅彝族哈尼族拉祜族自治县',
            ),
            496 => 
            array (
                'id' => 72979,
                'area_id' => 530826,
                'name' => '江城哈尼族彝族自治县',
            ),
            497 => 
            array (
                'id' => 73006,
                'area_id' => 530827,
                'name' => '孟连傣族拉祜族佤族自治县',
            ),
            498 => 
            array (
                'id' => 73033,
                'area_id' => 530828,
                'name' => '澜沧拉祜族自治县',
            ),
            499 => 
            array (
                'id' => 73060,
                'area_id' => 530829,
                'name' => '西盟佤族自治县',
            ),
        ));
        \DB::table('areas')->insert(array (
            0 => 
            array (
                'id' => 73076,
                'area_id' => 530901,
                'name' => '市辖区',
            ),
            1 => 
            array (
                'id' => 73103,
                'area_id' => 530902,
                'name' => '临翔区',
            ),
            2 => 
            array (
                'id' => 73130,
                'area_id' => 530921,
                'name' => '凤庆县',
            ),
            3 => 
            array (
                'id' => 73157,
                'area_id' => 530922,
                'name' => '云　县',
            ),
            4 => 
            array (
                'id' => 73184,
                'area_id' => 530923,
                'name' => '永德县',
            ),
            5 => 
            array (
                'id' => 73211,
                'area_id' => 530924,
                'name' => '镇康县',
            ),
            6 => 
            array (
                'id' => 73238,
                'area_id' => 530925,
                'name' => '双江拉祜族佤族布朗族傣族自治县',
            ),
            7 => 
            array (
                'id' => 73265,
                'area_id' => 530926,
                'name' => '耿马傣族佤族自治县',
            ),
            8 => 
            array (
                'id' => 73292,
                'area_id' => 530927,
                'name' => '沧源佤族自治县',
            ),
            9 => 
            array (
                'id' => 73310,
                'area_id' => 532301,
                'name' => '楚雄市',
            ),
            10 => 
            array (
                'id' => 73337,
                'area_id' => 532322,
                'name' => '双柏县',
            ),
            11 => 
            array (
                'id' => 73364,
                'area_id' => 532323,
                'name' => '牟定县',
            ),
            12 => 
            array (
                'id' => 73391,
                'area_id' => 532324,
                'name' => '南华县',
            ),
            13 => 
            array (
                'id' => 73418,
                'area_id' => 532325,
                'name' => '姚安县',
            ),
            14 => 
            array (
                'id' => 73445,
                'area_id' => 532326,
                'name' => '大姚县',
            ),
            15 => 
            array (
                'id' => 73472,
                'area_id' => 532327,
                'name' => '永仁县',
            ),
            16 => 
            array (
                'id' => 73499,
                'area_id' => 532328,
                'name' => '元谋县',
            ),
            17 => 
            array (
                'id' => 73526,
                'area_id' => 532329,
                'name' => '武定县',
            ),
            18 => 
            array (
                'id' => 73553,
                'area_id' => 532331,
                'name' => '禄丰县',
            ),
            19 => 
            array (
                'id' => 73570,
                'area_id' => 532501,
                'name' => '个旧市',
            ),
            20 => 
            array (
                'id' => 73597,
                'area_id' => 532502,
                'name' => '开远市',
            ),
            21 => 
            array (
                'id' => 73624,
                'area_id' => 532522,
                'name' => '蒙自县',
            ),
            22 => 
            array (
                'id' => 73651,
                'area_id' => 532523,
                'name' => '屏边苗族自治县',
            ),
            23 => 
            array (
                'id' => 73678,
                'area_id' => 532524,
                'name' => '建水县',
            ),
            24 => 
            array (
                'id' => 73705,
                'area_id' => 532525,
                'name' => '石屏县',
            ),
            25 => 
            array (
                'id' => 73732,
                'area_id' => 532526,
                'name' => '弥勒县',
            ),
            26 => 
            array (
                'id' => 73759,
                'area_id' => 532527,
                'name' => '泸西县',
            ),
            27 => 
            array (
                'id' => 73786,
                'area_id' => 532528,
                'name' => '元阳县',
            ),
            28 => 
            array (
                'id' => 73813,
                'area_id' => 532529,
                'name' => '红河县',
            ),
            29 => 
            array (
                'id' => 73840,
                'area_id' => 532530,
                'name' => '金平苗族瑶族傣族自治县',
            ),
            30 => 
            array (
                'id' => 73867,
                'area_id' => 532531,
                'name' => '绿春县',
            ),
            31 => 
            array (
                'id' => 73894,
                'area_id' => 532532,
                'name' => '河口瑶族自治县',
            ),
            32 => 
            array (
                'id' => 73908,
                'area_id' => 532621,
                'name' => '文山县',
            ),
            33 => 
            array (
                'id' => 73935,
                'area_id' => 532622,
                'name' => '砚山县',
            ),
            34 => 
            array (
                'id' => 73962,
                'area_id' => 532623,
                'name' => '西畴县',
            ),
            35 => 
            array (
                'id' => 73989,
                'area_id' => 532624,
                'name' => '麻栗坡县',
            ),
            36 => 
            array (
                'id' => 74016,
                'area_id' => 532625,
                'name' => '马关县',
            ),
            37 => 
            array (
                'id' => 74043,
                'area_id' => 532626,
                'name' => '丘北县',
            ),
            38 => 
            array (
                'id' => 74070,
                'area_id' => 532627,
                'name' => '广南县',
            ),
            39 => 
            array (
                'id' => 74097,
                'area_id' => 532628,
                'name' => '富宁县',
            ),
            40 => 
            array (
                'id' => 74116,
                'area_id' => 532801,
                'name' => '景洪市',
            ),
            41 => 
            array (
                'id' => 74143,
                'area_id' => 532822,
                'name' => '勐海县',
            ),
            42 => 
            array (
                'id' => 74170,
                'area_id' => 532823,
                'name' => '勐腊县',
            ),
            43 => 
            array (
                'id' => 74194,
                'area_id' => 532901,
                'name' => '大理市',
            ),
            44 => 
            array (
                'id' => 74221,
                'area_id' => 532922,
                'name' => '漾濞彝族自治县',
            ),
            45 => 
            array (
                'id' => 74248,
                'area_id' => 532923,
                'name' => '祥云县',
            ),
            46 => 
            array (
                'id' => 74275,
                'area_id' => 532924,
                'name' => '宾川县',
            ),
            47 => 
            array (
                'id' => 74302,
                'area_id' => 532925,
                'name' => '弥渡县',
            ),
            48 => 
            array (
                'id' => 74329,
                'area_id' => 532926,
                'name' => '南涧彝族自治县',
            ),
            49 => 
            array (
                'id' => 74356,
                'area_id' => 532927,
                'name' => '巍山彝族回族自治县',
            ),
            50 => 
            array (
                'id' => 74383,
                'area_id' => 532928,
                'name' => '永平县',
            ),
            51 => 
            array (
                'id' => 74410,
                'area_id' => 532929,
                'name' => '云龙县',
            ),
            52 => 
            array (
                'id' => 74437,
                'area_id' => 532930,
                'name' => '洱源县',
            ),
            53 => 
            array (
                'id' => 74464,
                'area_id' => 532931,
                'name' => '剑川县',
            ),
            54 => 
            array (
                'id' => 74491,
                'area_id' => 532932,
                'name' => '鹤庆县',
            ),
            55 => 
            array (
                'id' => 74506,
                'area_id' => 533102,
                'name' => '瑞丽市',
            ),
            56 => 
            array (
                'id' => 74533,
                'area_id' => 533103,
                'name' => '潞西市',
            ),
            57 => 
            array (
                'id' => 74560,
                'area_id' => 533122,
                'name' => '梁河县',
            ),
            58 => 
            array (
                'id' => 74587,
                'area_id' => 533123,
                'name' => '盈江县',
            ),
            59 => 
            array (
                'id' => 74614,
                'area_id' => 533124,
                'name' => '陇川县',
            ),
            60 => 
            array (
                'id' => 74636,
                'area_id' => 533321,
                'name' => '泸水县',
            ),
            61 => 
            array (
                'id' => 74663,
                'area_id' => 533323,
                'name' => '福贡县',
            ),
            62 => 
            array (
                'id' => 74690,
                'area_id' => 533324,
                'name' => '贡山独龙族怒族自治县',
            ),
            63 => 
            array (
                'id' => 74717,
                'area_id' => 533325,
                'name' => '兰坪白族普米族自治县',
            ),
            64 => 
            array (
                'id' => 74740,
                'area_id' => 533421,
                'name' => '香格里拉县',
            ),
            65 => 
            array (
                'id' => 74767,
                'area_id' => 533422,
                'name' => '德钦县',
            ),
            66 => 
            array (
                'id' => 74794,
                'area_id' => 533423,
                'name' => '维西傈僳族自治县',
            ),
            67 => 
            array (
                'id' => 74818,
                'area_id' => 540101,
                'name' => '市辖区',
            ),
            68 => 
            array (
                'id' => 74845,
                'area_id' => 540102,
                'name' => '城关区',
            ),
            69 => 
            array (
                'id' => 74872,
                'area_id' => 540121,
                'name' => '林周县',
            ),
            70 => 
            array (
                'id' => 74899,
                'area_id' => 540122,
                'name' => '当雄县',
            ),
            71 => 
            array (
                'id' => 74926,
                'area_id' => 540123,
                'name' => '尼木县',
            ),
            72 => 
            array (
                'id' => 74953,
                'area_id' => 540124,
                'name' => '曲水县',
            ),
            73 => 
            array (
                'id' => 74980,
                'area_id' => 540125,
                'name' => '堆龙德庆县',
            ),
            74 => 
            array (
                'id' => 75007,
                'area_id' => 540126,
                'name' => '达孜县',
            ),
            75 => 
            array (
                'id' => 75034,
                'area_id' => 540127,
                'name' => '墨竹工卡县',
            ),
            76 => 
            array (
                'id' => 75052,
                'area_id' => 542121,
                'name' => '昌都县',
            ),
            77 => 
            array (
                'id' => 75079,
                'area_id' => 542122,
                'name' => '江达县',
            ),
            78 => 
            array (
                'id' => 75106,
                'area_id' => 542123,
                'name' => '贡觉县',
            ),
            79 => 
            array (
                'id' => 75133,
                'area_id' => 542124,
                'name' => '类乌齐县',
            ),
            80 => 
            array (
                'id' => 75160,
                'area_id' => 542125,
                'name' => '丁青县',
            ),
            81 => 
            array (
                'id' => 75187,
                'area_id' => 542126,
                'name' => '察雅县',
            ),
            82 => 
            array (
                'id' => 75214,
                'area_id' => 542127,
                'name' => '八宿县',
            ),
            83 => 
            array (
                'id' => 75241,
                'area_id' => 542128,
                'name' => '左贡县',
            ),
            84 => 
            array (
                'id' => 75268,
                'area_id' => 542129,
                'name' => '芒康县',
            ),
            85 => 
            array (
                'id' => 75295,
                'area_id' => 542132,
                'name' => '洛隆县',
            ),
            86 => 
            array (
                'id' => 75322,
                'area_id' => 542133,
                'name' => '边坝县',
            ),
            87 => 
            array (
                'id' => 75338,
                'area_id' => 542221,
                'name' => '乃东县',
            ),
            88 => 
            array (
                'id' => 75365,
                'area_id' => 542222,
                'name' => '扎囊县',
            ),
            89 => 
            array (
                'id' => 75392,
                'area_id' => 542223,
                'name' => '贡嘎县',
            ),
            90 => 
            array (
                'id' => 75419,
                'area_id' => 542224,
                'name' => '桑日县',
            ),
            91 => 
            array (
                'id' => 75446,
                'area_id' => 542225,
                'name' => '琼结县',
            ),
            92 => 
            array (
                'id' => 75473,
                'area_id' => 542226,
                'name' => '曲松县',
            ),
            93 => 
            array (
                'id' => 75500,
                'area_id' => 542227,
                'name' => '措美县',
            ),
            94 => 
            array (
                'id' => 75527,
                'area_id' => 542228,
                'name' => '洛扎县',
            ),
            95 => 
            array (
                'id' => 75554,
                'area_id' => 542229,
                'name' => '加查县',
            ),
            96 => 
            array (
                'id' => 75581,
                'area_id' => 542231,
                'name' => '隆子县',
            ),
            97 => 
            array (
                'id' => 75608,
                'area_id' => 542232,
                'name' => '错那县',
            ),
            98 => 
            array (
                'id' => 75635,
                'area_id' => 542233,
                'name' => '浪卡子县',
            ),
            99 => 
            array (
                'id' => 75650,
                'area_id' => 542301,
                'name' => '日喀则市',
            ),
            100 => 
            array (
                'id' => 75677,
                'area_id' => 542322,
                'name' => '南木林县',
            ),
            101 => 
            array (
                'id' => 75704,
                'area_id' => 542323,
                'name' => '江孜县',
            ),
            102 => 
            array (
                'id' => 75731,
                'area_id' => 542324,
                'name' => '定日县',
            ),
            103 => 
            array (
                'id' => 75758,
                'area_id' => 542325,
                'name' => '萨迦县',
            ),
            104 => 
            array (
                'id' => 75785,
                'area_id' => 542326,
                'name' => '拉孜县',
            ),
            105 => 
            array (
                'id' => 75812,
                'area_id' => 542327,
                'name' => '昂仁县',
            ),
            106 => 
            array (
                'id' => 75839,
                'area_id' => 542328,
                'name' => '谢通门县',
            ),
            107 => 
            array (
                'id' => 75866,
                'area_id' => 542329,
                'name' => '白朗县',
            ),
            108 => 
            array (
                'id' => 75893,
                'area_id' => 542330,
                'name' => '仁布县',
            ),
            109 => 
            array (
                'id' => 75920,
                'area_id' => 542331,
                'name' => '康马县',
            ),
            110 => 
            array (
                'id' => 75947,
                'area_id' => 542332,
                'name' => '定结县',
            ),
            111 => 
            array (
                'id' => 75974,
                'area_id' => 542333,
                'name' => '仲巴县',
            ),
            112 => 
            array (
                'id' => 76001,
                'area_id' => 542334,
                'name' => '亚东县',
            ),
            113 => 
            array (
                'id' => 76028,
                'area_id' => 542335,
                'name' => '吉隆县',
            ),
            114 => 
            array (
                'id' => 76055,
                'area_id' => 542336,
                'name' => '聂拉木县',
            ),
            115 => 
            array (
                'id' => 76082,
                'area_id' => 542337,
                'name' => '萨嘎县',
            ),
            116 => 
            array (
                'id' => 76109,
                'area_id' => 542338,
                'name' => '岗巴县',
            ),
            117 => 
            array (
                'id' => 76118,
                'area_id' => 542421,
                'name' => '那曲县',
            ),
            118 => 
            array (
                'id' => 76145,
                'area_id' => 542422,
                'name' => '嘉黎县',
            ),
            119 => 
            array (
                'id' => 76172,
                'area_id' => 542423,
                'name' => '比如县',
            ),
            120 => 
            array (
                'id' => 76199,
                'area_id' => 542424,
                'name' => '聂荣县',
            ),
            121 => 
            array (
                'id' => 76226,
                'area_id' => 542425,
                'name' => '安多县',
            ),
            122 => 
            array (
                'id' => 76253,
                'area_id' => 542426,
                'name' => '申扎县',
            ),
            123 => 
            array (
                'id' => 76280,
                'area_id' => 542427,
                'name' => '索　县',
            ),
            124 => 
            array (
                'id' => 76307,
                'area_id' => 542428,
                'name' => '班戈县',
            ),
            125 => 
            array (
                'id' => 76334,
                'area_id' => 542429,
                'name' => '巴青县',
            ),
            126 => 
            array (
                'id' => 76361,
                'area_id' => 542430,
                'name' => '尼玛县',
            ),
            127 => 
            array (
                'id' => 76378,
                'area_id' => 542521,
                'name' => '普兰县',
            ),
            128 => 
            array (
                'id' => 76405,
                'area_id' => 542522,
                'name' => '札达县',
            ),
            129 => 
            array (
                'id' => 76432,
                'area_id' => 542523,
                'name' => '噶尔县',
            ),
            130 => 
            array (
                'id' => 76459,
                'area_id' => 542524,
                'name' => '日土县',
            ),
            131 => 
            array (
                'id' => 76486,
                'area_id' => 542525,
                'name' => '革吉县',
            ),
            132 => 
            array (
                'id' => 76513,
                'area_id' => 542526,
                'name' => '改则县',
            ),
            133 => 
            array (
                'id' => 76540,
                'area_id' => 542527,
                'name' => '措勤县',
            ),
            134 => 
            array (
                'id' => 76560,
                'area_id' => 542621,
                'name' => '林芝县',
            ),
            135 => 
            array (
                'id' => 76587,
                'area_id' => 542622,
                'name' => '工布江达县',
            ),
            136 => 
            array (
                'id' => 76614,
                'area_id' => 542623,
                'name' => '米林县',
            ),
            137 => 
            array (
                'id' => 76641,
                'area_id' => 542624,
                'name' => '墨脱县',
            ),
            138 => 
            array (
                'id' => 76668,
                'area_id' => 542625,
                'name' => '波密县',
            ),
            139 => 
            array (
                'id' => 76695,
                'area_id' => 542626,
                'name' => '察隅县',
            ),
            140 => 
            array (
                'id' => 76722,
                'area_id' => 542627,
                'name' => '朗　县',
            ),
            141 => 
            array (
                'id' => 76742,
                'area_id' => 610101,
                'name' => '市辖区',
            ),
            142 => 
            array (
                'id' => 76769,
                'area_id' => 610102,
                'name' => '新城区',
            ),
            143 => 
            array (
                'id' => 76796,
                'area_id' => 610103,
                'name' => '碑林区',
            ),
            144 => 
            array (
                'id' => 76823,
                'area_id' => 610104,
                'name' => '莲湖区',
            ),
            145 => 
            array (
                'id' => 76850,
                'area_id' => 610111,
                'name' => '灞桥区',
            ),
            146 => 
            array (
                'id' => 76877,
                'area_id' => 610112,
                'name' => '未央区',
            ),
            147 => 
            array (
                'id' => 76904,
                'area_id' => 610113,
                'name' => '雁塔区',
            ),
            148 => 
            array (
                'id' => 76931,
                'area_id' => 610114,
                'name' => '阎良区',
            ),
            149 => 
            array (
                'id' => 76958,
                'area_id' => 610115,
                'name' => '临潼区',
            ),
            150 => 
            array (
                'id' => 76985,
                'area_id' => 610116,
                'name' => '长安区',
            ),
            151 => 
            array (
                'id' => 77012,
                'area_id' => 610122,
                'name' => '蓝田县',
            ),
            152 => 
            array (
                'id' => 77039,
                'area_id' => 610124,
                'name' => '周至县',
            ),
            153 => 
            array (
                'id' => 77066,
                'area_id' => 610125,
                'name' => '户　县',
            ),
            154 => 
            array (
                'id' => 77093,
                'area_id' => 610126,
                'name' => '高陵县',
            ),
            155 => 
            array (
                'id' => 77106,
                'area_id' => 610201,
                'name' => '市辖区',
            ),
            156 => 
            array (
                'id' => 77133,
                'area_id' => 610202,
                'name' => '王益区',
            ),
            157 => 
            array (
                'id' => 77160,
                'area_id' => 610203,
                'name' => '印台区',
            ),
            158 => 
            array (
                'id' => 77187,
                'area_id' => 610204,
                'name' => '耀州区',
            ),
            159 => 
            array (
                'id' => 77214,
                'area_id' => 610222,
                'name' => '宜君县',
            ),
            160 => 
            array (
                'id' => 77236,
                'area_id' => 610301,
                'name' => '市辖区',
            ),
            161 => 
            array (
                'id' => 77263,
                'area_id' => 610302,
                'name' => '渭滨区',
            ),
            162 => 
            array (
                'id' => 77290,
                'area_id' => 610303,
                'name' => '金台区',
            ),
            163 => 
            array (
                'id' => 77317,
                'area_id' => 610304,
                'name' => '陈仓区',
            ),
            164 => 
            array (
                'id' => 77344,
                'area_id' => 610322,
                'name' => '凤翔县',
            ),
            165 => 
            array (
                'id' => 77371,
                'area_id' => 610323,
                'name' => '岐山县',
            ),
            166 => 
            array (
                'id' => 77398,
                'area_id' => 610324,
                'name' => '扶风县',
            ),
            167 => 
            array (
                'id' => 77425,
                'area_id' => 610326,
                'name' => '眉　县',
            ),
            168 => 
            array (
                'id' => 77452,
                'area_id' => 610327,
                'name' => '陇　县',
            ),
            169 => 
            array (
                'id' => 77479,
                'area_id' => 610328,
                'name' => '千阳县',
            ),
            170 => 
            array (
                'id' => 77506,
                'area_id' => 610329,
                'name' => '麟游县',
            ),
            171 => 
            array (
                'id' => 77533,
                'area_id' => 610330,
                'name' => '凤　县',
            ),
            172 => 
            array (
                'id' => 77560,
                'area_id' => 610331,
                'name' => '太白县',
            ),
            173 => 
            array (
                'id' => 77574,
                'area_id' => 610401,
                'name' => '市辖区',
            ),
            174 => 
            array (
                'id' => 77601,
                'area_id' => 610402,
                'name' => '秦都区',
            ),
            175 => 
            array (
                'id' => 77628,
                'area_id' => 610403,
                'name' => '杨凌区',
            ),
            176 => 
            array (
                'id' => 77655,
                'area_id' => 610404,
                'name' => '渭城区',
            ),
            177 => 
            array (
                'id' => 77682,
                'area_id' => 610422,
                'name' => '三原县',
            ),
            178 => 
            array (
                'id' => 77709,
                'area_id' => 610423,
                'name' => '泾阳县',
            ),
            179 => 
            array (
                'id' => 77736,
                'area_id' => 610424,
                'name' => '乾　县',
            ),
            180 => 
            array (
                'id' => 77763,
                'area_id' => 610425,
                'name' => '礼泉县',
            ),
            181 => 
            array (
                'id' => 77790,
                'area_id' => 610426,
                'name' => '永寿县',
            ),
            182 => 
            array (
                'id' => 77817,
                'area_id' => 610427,
                'name' => '彬　县',
            ),
            183 => 
            array (
                'id' => 77844,
                'area_id' => 610428,
                'name' => '长武县',
            ),
            184 => 
            array (
                'id' => 77871,
                'area_id' => 610429,
                'name' => '旬邑县',
            ),
            185 => 
            array (
                'id' => 77898,
                'area_id' => 610430,
                'name' => '淳化县',
            ),
            186 => 
            array (
                'id' => 77925,
                'area_id' => 610431,
                'name' => '武功县',
            ),
            187 => 
            array (
                'id' => 77952,
                'area_id' => 610481,
                'name' => '兴平市',
            ),
            188 => 
            array (
                'id' => 77964,
                'area_id' => 610501,
                'name' => '市辖区',
            ),
            189 => 
            array (
                'id' => 77991,
                'area_id' => 610502,
                'name' => '临渭区',
            ),
            190 => 
            array (
                'id' => 78018,
                'area_id' => 610521,
                'name' => '华　县',
            ),
            191 => 
            array (
                'id' => 78045,
                'area_id' => 610522,
                'name' => '潼关县',
            ),
            192 => 
            array (
                'id' => 78072,
                'area_id' => 610523,
                'name' => '大荔县',
            ),
            193 => 
            array (
                'id' => 78099,
                'area_id' => 610524,
                'name' => '合阳县',
            ),
            194 => 
            array (
                'id' => 78126,
                'area_id' => 610525,
                'name' => '澄城县',
            ),
            195 => 
            array (
                'id' => 78153,
                'area_id' => 610526,
                'name' => '蒲城县',
            ),
            196 => 
            array (
                'id' => 78180,
                'area_id' => 610527,
                'name' => '白水县',
            ),
            197 => 
            array (
                'id' => 78207,
                'area_id' => 610528,
                'name' => '富平县',
            ),
            198 => 
            array (
                'id' => 78234,
                'area_id' => 610581,
                'name' => '韩城市',
            ),
            199 => 
            array (
                'id' => 78261,
                'area_id' => 610582,
                'name' => '华阴市',
            ),
            200 => 
            array (
                'id' => 78276,
                'area_id' => 610601,
                'name' => '市辖区',
            ),
            201 => 
            array (
                'id' => 78303,
                'area_id' => 610602,
                'name' => '宝塔区',
            ),
            202 => 
            array (
                'id' => 78330,
                'area_id' => 610621,
                'name' => '延长县',
            ),
            203 => 
            array (
                'id' => 78357,
                'area_id' => 610622,
                'name' => '延川县',
            ),
            204 => 
            array (
                'id' => 78384,
                'area_id' => 610623,
                'name' => '子长县',
            ),
            205 => 
            array (
                'id' => 78411,
                'area_id' => 610624,
                'name' => '安塞县',
            ),
            206 => 
            array (
                'id' => 78438,
                'area_id' => 610625,
                'name' => '志丹县',
            ),
            207 => 
            array (
                'id' => 78465,
                'area_id' => 610626,
                'name' => '吴旗县',
            ),
            208 => 
            array (
                'id' => 78492,
                'area_id' => 610627,
                'name' => '甘泉县',
            ),
            209 => 
            array (
                'id' => 78519,
                'area_id' => 610628,
                'name' => '富　县',
            ),
            210 => 
            array (
                'id' => 78546,
                'area_id' => 610629,
                'name' => '洛川县',
            ),
            211 => 
            array (
                'id' => 78573,
                'area_id' => 610630,
                'name' => '宜川县',
            ),
            212 => 
            array (
                'id' => 78600,
                'area_id' => 610631,
                'name' => '黄龙县',
            ),
            213 => 
            array (
                'id' => 78627,
                'area_id' => 610632,
                'name' => '黄陵县',
            ),
            214 => 
            array (
                'id' => 78640,
                'area_id' => 610701,
                'name' => '市辖区',
            ),
            215 => 
            array (
                'id' => 78667,
                'area_id' => 610702,
                'name' => '汉台区',
            ),
            216 => 
            array (
                'id' => 78694,
                'area_id' => 610721,
                'name' => '南郑县',
            ),
            217 => 
            array (
                'id' => 78721,
                'area_id' => 610722,
                'name' => '城固县',
            ),
            218 => 
            array (
                'id' => 78748,
                'area_id' => 610723,
                'name' => '洋　县',
            ),
            219 => 
            array (
                'id' => 78775,
                'area_id' => 610724,
                'name' => '西乡县',
            ),
            220 => 
            array (
                'id' => 78802,
                'area_id' => 610725,
                'name' => '勉　县',
            ),
            221 => 
            array (
                'id' => 78829,
                'area_id' => 610726,
                'name' => '宁强县',
            ),
            222 => 
            array (
                'id' => 78856,
                'area_id' => 610727,
                'name' => '略阳县',
            ),
            223 => 
            array (
                'id' => 78883,
                'area_id' => 610728,
                'name' => '镇巴县',
            ),
            224 => 
            array (
                'id' => 78910,
                'area_id' => 610729,
                'name' => '留坝县',
            ),
            225 => 
            array (
                'id' => 78937,
                'area_id' => 610730,
                'name' => '佛坪县',
            ),
            226 => 
            array (
                'id' => 78952,
                'area_id' => 610801,
                'name' => '市辖区',
            ),
            227 => 
            array (
                'id' => 78979,
                'area_id' => 610802,
                'name' => '榆阳区',
            ),
            228 => 
            array (
                'id' => 79006,
                'area_id' => 610821,
                'name' => '神木县',
            ),
            229 => 
            array (
                'id' => 79033,
                'area_id' => 610822,
                'name' => '府谷县',
            ),
            230 => 
            array (
                'id' => 79060,
                'area_id' => 610823,
                'name' => '横山县',
            ),
            231 => 
            array (
                'id' => 79087,
                'area_id' => 610824,
                'name' => '靖边县',
            ),
            232 => 
            array (
                'id' => 79114,
                'area_id' => 610825,
                'name' => '定边县',
            ),
            233 => 
            array (
                'id' => 79141,
                'area_id' => 610826,
                'name' => '绥德县',
            ),
            234 => 
            array (
                'id' => 79168,
                'area_id' => 610827,
                'name' => '米脂县',
            ),
            235 => 
            array (
                'id' => 79195,
                'area_id' => 610828,
                'name' => '佳　县',
            ),
            236 => 
            array (
                'id' => 79222,
                'area_id' => 610829,
                'name' => '吴堡县',
            ),
            237 => 
            array (
                'id' => 79249,
                'area_id' => 610830,
                'name' => '清涧县',
            ),
            238 => 
            array (
                'id' => 79276,
                'area_id' => 610831,
                'name' => '子洲县',
            ),
            239 => 
            array (
                'id' => 79290,
                'area_id' => 610901,
                'name' => '市辖区',
            ),
            240 => 
            array (
                'id' => 79317,
                'area_id' => 610902,
                'name' => '汉滨区',
            ),
            241 => 
            array (
                'id' => 79344,
                'area_id' => 610921,
                'name' => '汉阴县',
            ),
            242 => 
            array (
                'id' => 79371,
                'area_id' => 610922,
                'name' => '石泉县',
            ),
            243 => 
            array (
                'id' => 79398,
                'area_id' => 610923,
                'name' => '宁陕县',
            ),
            244 => 
            array (
                'id' => 79425,
                'area_id' => 610924,
                'name' => '紫阳县',
            ),
            245 => 
            array (
                'id' => 79452,
                'area_id' => 610925,
                'name' => '岚皋县',
            ),
            246 => 
            array (
                'id' => 79479,
                'area_id' => 610926,
                'name' => '平利县',
            ),
            247 => 
            array (
                'id' => 79506,
                'area_id' => 610927,
                'name' => '镇坪县',
            ),
            248 => 
            array (
                'id' => 79533,
                'area_id' => 610928,
                'name' => '旬阳县',
            ),
            249 => 
            array (
                'id' => 79560,
                'area_id' => 610929,
                'name' => '白河县',
            ),
            250 => 
            array (
                'id' => 79576,
                'area_id' => 611001,
                'name' => '市辖区',
            ),
            251 => 
            array (
                'id' => 79603,
                'area_id' => 611002,
                'name' => '商州区',
            ),
            252 => 
            array (
                'id' => 79630,
                'area_id' => 611021,
                'name' => '洛南县',
            ),
            253 => 
            array (
                'id' => 79657,
                'area_id' => 611022,
                'name' => '丹凤县',
            ),
            254 => 
            array (
                'id' => 79684,
                'area_id' => 611023,
                'name' => '商南县',
            ),
            255 => 
            array (
                'id' => 79711,
                'area_id' => 611024,
                'name' => '山阳县',
            ),
            256 => 
            array (
                'id' => 79738,
                'area_id' => 611025,
                'name' => '镇安县',
            ),
            257 => 
            array (
                'id' => 79765,
                'area_id' => 611026,
                'name' => '柞水县',
            ),
            258 => 
            array (
                'id' => 79784,
                'area_id' => 620101,
                'name' => '市辖区',
            ),
            259 => 
            array (
                'id' => 79811,
                'area_id' => 620102,
                'name' => '城关区',
            ),
            260 => 
            array (
                'id' => 79838,
                'area_id' => 620103,
                'name' => '七里河区',
            ),
            261 => 
            array (
                'id' => 79865,
                'area_id' => 620104,
                'name' => '西固区',
            ),
            262 => 
            array (
                'id' => 79892,
                'area_id' => 620105,
                'name' => '安宁区',
            ),
            263 => 
            array (
                'id' => 79919,
                'area_id' => 620111,
                'name' => '红古区',
            ),
            264 => 
            array (
                'id' => 79946,
                'area_id' => 620121,
                'name' => '永登县',
            ),
            265 => 
            array (
                'id' => 79973,
                'area_id' => 620122,
                'name' => '皋兰县',
            ),
            266 => 
            array (
                'id' => 80000,
                'area_id' => 620123,
                'name' => '榆中县',
            ),
            267 => 
            array (
                'id' => 80018,
                'area_id' => 620201,
                'name' => '市辖区',
            ),
            268 => 
            array (
                'id' => 80044,
                'area_id' => 620301,
                'name' => '市辖区',
            ),
            269 => 
            array (
                'id' => 80071,
                'area_id' => 620302,
                'name' => '金川区',
            ),
            270 => 
            array (
                'id' => 80098,
                'area_id' => 620321,
                'name' => '永昌县',
            ),
            271 => 
            array (
                'id' => 80122,
                'area_id' => 620401,
                'name' => '市辖区',
            ),
            272 => 
            array (
                'id' => 80149,
                'area_id' => 620402,
                'name' => '白银区',
            ),
            273 => 
            array (
                'id' => 80176,
                'area_id' => 620403,
                'name' => '平川区',
            ),
            274 => 
            array (
                'id' => 80203,
                'area_id' => 620421,
                'name' => '靖远县',
            ),
            275 => 
            array (
                'id' => 80230,
                'area_id' => 620422,
                'name' => '会宁县',
            ),
            276 => 
            array (
                'id' => 80257,
                'area_id' => 620423,
                'name' => '景泰县',
            ),
            277 => 
            array (
                'id' => 80278,
                'area_id' => 620501,
                'name' => '市辖区',
            ),
            278 => 
            array (
                'id' => 80305,
                'area_id' => 620502,
                'name' => '秦城区',
            ),
            279 => 
            array (
                'id' => 80332,
                'area_id' => 620503,
                'name' => '北道区',
            ),
            280 => 
            array (
                'id' => 80359,
                'area_id' => 620521,
                'name' => '清水县',
            ),
            281 => 
            array (
                'id' => 80386,
                'area_id' => 620522,
                'name' => '秦安县',
            ),
            282 => 
            array (
                'id' => 80413,
                'area_id' => 620523,
                'name' => '甘谷县',
            ),
            283 => 
            array (
                'id' => 80440,
                'area_id' => 620524,
                'name' => '武山县',
            ),
            284 => 
            array (
                'id' => 80467,
                'area_id' => 620525,
                'name' => '张家川回族自治县',
            ),
            285 => 
            array (
                'id' => 80486,
                'area_id' => 620601,
                'name' => '市辖区',
            ),
            286 => 
            array (
                'id' => 80513,
                'area_id' => 620602,
                'name' => '凉州区',
            ),
            287 => 
            array (
                'id' => 80540,
                'area_id' => 620621,
                'name' => '民勤县',
            ),
            288 => 
            array (
                'id' => 80567,
                'area_id' => 620622,
                'name' => '古浪县',
            ),
            289 => 
            array (
                'id' => 80594,
                'area_id' => 620623,
                'name' => '天祝藏族自治县',
            ),
            290 => 
            array (
                'id' => 80616,
                'area_id' => 620701,
                'name' => '市辖区',
            ),
            291 => 
            array (
                'id' => 80643,
                'area_id' => 620702,
                'name' => '甘州区',
            ),
            292 => 
            array (
                'id' => 80670,
                'area_id' => 620721,
                'name' => '肃南裕固族自治县',
            ),
            293 => 
            array (
                'id' => 80697,
                'area_id' => 620722,
                'name' => '民乐县',
            ),
            294 => 
            array (
                'id' => 80724,
                'area_id' => 620723,
                'name' => '临泽县',
            ),
            295 => 
            array (
                'id' => 80751,
                'area_id' => 620724,
                'name' => '高台县',
            ),
            296 => 
            array (
                'id' => 80778,
                'area_id' => 620725,
                'name' => '山丹县',
            ),
            297 => 
            array (
                'id' => 80798,
                'area_id' => 620801,
                'name' => '市辖区',
            ),
            298 => 
            array (
                'id' => 80825,
                'area_id' => 620802,
                'name' => '崆峒区',
            ),
            299 => 
            array (
                'id' => 80852,
                'area_id' => 620821,
                'name' => '泾川县',
            ),
            300 => 
            array (
                'id' => 80879,
                'area_id' => 620822,
                'name' => '灵台县',
            ),
            301 => 
            array (
                'id' => 80906,
                'area_id' => 620823,
                'name' => '崇信县',
            ),
            302 => 
            array (
                'id' => 80933,
                'area_id' => 620824,
                'name' => '华亭县',
            ),
            303 => 
            array (
                'id' => 80960,
                'area_id' => 620825,
                'name' => '庄浪县',
            ),
            304 => 
            array (
                'id' => 80987,
                'area_id' => 620826,
                'name' => '静宁县',
            ),
            305 => 
            array (
                'id' => 81006,
                'area_id' => 620901,
                'name' => '市辖区',
            ),
            306 => 
            array (
                'id' => 81033,
                'area_id' => 620902,
                'name' => '肃州区',
            ),
            307 => 
            array (
                'id' => 81060,
                'area_id' => 620921,
                'name' => '金塔县',
            ),
            308 => 
            array (
                'id' => 81087,
                'area_id' => 620922,
                'name' => '安西县',
            ),
            309 => 
            array (
                'id' => 81114,
                'area_id' => 620923,
                'name' => '肃北蒙古族自治县',
            ),
            310 => 
            array (
                'id' => 81141,
                'area_id' => 620924,
                'name' => '阿克塞哈萨克族自治县',
            ),
            311 => 
            array (
                'id' => 81168,
                'area_id' => 620981,
                'name' => '玉门市',
            ),
            312 => 
            array (
                'id' => 81195,
                'area_id' => 620982,
                'name' => '敦煌市',
            ),
            313 => 
            array (
                'id' => 81214,
                'area_id' => 621001,
                'name' => '市辖区',
            ),
            314 => 
            array (
                'id' => 81241,
                'area_id' => 621002,
                'name' => '西峰区',
            ),
            315 => 
            array (
                'id' => 81268,
                'area_id' => 621021,
                'name' => '庆城县',
            ),
            316 => 
            array (
                'id' => 81295,
                'area_id' => 621022,
                'name' => '环　县',
            ),
            317 => 
            array (
                'id' => 81322,
                'area_id' => 621023,
                'name' => '华池县',
            ),
            318 => 
            array (
                'id' => 81349,
                'area_id' => 621024,
                'name' => '合水县',
            ),
            319 => 
            array (
                'id' => 81376,
                'area_id' => 621025,
                'name' => '正宁县',
            ),
            320 => 
            array (
                'id' => 81403,
                'area_id' => 621026,
                'name' => '宁　县',
            ),
            321 => 
            array (
                'id' => 81430,
                'area_id' => 621027,
                'name' => '镇原县',
            ),
            322 => 
            array (
                'id' => 81448,
                'area_id' => 621101,
                'name' => '市辖区',
            ),
            323 => 
            array (
                'id' => 81475,
                'area_id' => 621102,
                'name' => '安定区',
            ),
            324 => 
            array (
                'id' => 81502,
                'area_id' => 621121,
                'name' => '通渭县',
            ),
            325 => 
            array (
                'id' => 81529,
                'area_id' => 621122,
                'name' => '陇西县',
            ),
            326 => 
            array (
                'id' => 81556,
                'area_id' => 621123,
                'name' => '渭源县',
            ),
            327 => 
            array (
                'id' => 81583,
                'area_id' => 621124,
                'name' => '临洮县',
            ),
            328 => 
            array (
                'id' => 81610,
                'area_id' => 621125,
                'name' => '漳　县',
            ),
            329 => 
            array (
                'id' => 81637,
                'area_id' => 621126,
                'name' => '岷　县',
            ),
            330 => 
            array (
                'id' => 81656,
                'area_id' => 621201,
                'name' => '市辖区',
            ),
            331 => 
            array (
                'id' => 81683,
                'area_id' => 621202,
                'name' => '武都区',
            ),
            332 => 
            array (
                'id' => 81710,
                'area_id' => 621221,
                'name' => '成　县',
            ),
            333 => 
            array (
                'id' => 81737,
                'area_id' => 621222,
                'name' => '文　县',
            ),
            334 => 
            array (
                'id' => 81764,
                'area_id' => 621223,
                'name' => '宕昌县',
            ),
            335 => 
            array (
                'id' => 81791,
                'area_id' => 621224,
                'name' => '康　县',
            ),
            336 => 
            array (
                'id' => 81818,
                'area_id' => 621225,
                'name' => '西和县',
            ),
            337 => 
            array (
                'id' => 81845,
                'area_id' => 621226,
                'name' => '礼　县',
            ),
            338 => 
            array (
                'id' => 81872,
                'area_id' => 621227,
                'name' => '徽　县',
            ),
            339 => 
            array (
                'id' => 81899,
                'area_id' => 621228,
                'name' => '两当县',
            ),
            340 => 
            array (
                'id' => 81916,
                'area_id' => 622901,
                'name' => '临夏市',
            ),
            341 => 
            array (
                'id' => 81943,
                'area_id' => 622921,
                'name' => '临夏县',
            ),
            342 => 
            array (
                'id' => 81970,
                'area_id' => 622922,
                'name' => '康乐县',
            ),
            343 => 
            array (
                'id' => 81997,
                'area_id' => 622923,
                'name' => '永靖县',
            ),
            344 => 
            array (
                'id' => 82024,
                'area_id' => 622924,
                'name' => '广河县',
            ),
            345 => 
            array (
                'id' => 82051,
                'area_id' => 622925,
                'name' => '和政县',
            ),
            346 => 
            array (
                'id' => 82078,
                'area_id' => 622926,
                'name' => '东乡族自治县',
            ),
            347 => 
            array (
                'id' => 82105,
                'area_id' => 622927,
                'name' => '积石山保安族东乡族撒拉族自治县',
            ),
            348 => 
            array (
                'id' => 82124,
                'area_id' => 623001,
                'name' => '合作市',
            ),
            349 => 
            array (
                'id' => 82151,
                'area_id' => 623021,
                'name' => '临潭县',
            ),
            350 => 
            array (
                'id' => 82178,
                'area_id' => 623022,
                'name' => '卓尼县',
            ),
            351 => 
            array (
                'id' => 82205,
                'area_id' => 623023,
                'name' => '舟曲县',
            ),
            352 => 
            array (
                'id' => 82232,
                'area_id' => 623024,
                'name' => '迭部县',
            ),
            353 => 
            array (
                'id' => 82259,
                'area_id' => 623025,
                'name' => '玛曲县',
            ),
            354 => 
            array (
                'id' => 82286,
                'area_id' => 623026,
                'name' => '碌曲县',
            ),
            355 => 
            array (
                'id' => 82313,
                'area_id' => 623027,
                'name' => '夏河县',
            ),
            356 => 
            array (
                'id' => 82332,
                'area_id' => 630101,
                'name' => '市辖区',
            ),
            357 => 
            array (
                'id' => 82359,
                'area_id' => 630102,
                'name' => '城东区',
            ),
            358 => 
            array (
                'id' => 82386,
                'area_id' => 630103,
                'name' => '城中区',
            ),
            359 => 
            array (
                'id' => 82413,
                'area_id' => 630104,
                'name' => '城西区',
            ),
            360 => 
            array (
                'id' => 82440,
                'area_id' => 630105,
                'name' => '城北区',
            ),
            361 => 
            array (
                'id' => 82467,
                'area_id' => 630121,
                'name' => '大通回族土族自治县',
            ),
            362 => 
            array (
                'id' => 82494,
                'area_id' => 630122,
                'name' => '湟中县',
            ),
            363 => 
            array (
                'id' => 82521,
                'area_id' => 630123,
                'name' => '湟源县',
            ),
            364 => 
            array (
                'id' => 82540,
                'area_id' => 632121,
                'name' => '平安县',
            ),
            365 => 
            array (
                'id' => 82567,
                'area_id' => 632122,
                'name' => '民和回族土族自治县',
            ),
            366 => 
            array (
                'id' => 82594,
                'area_id' => 632123,
                'name' => '乐都县',
            ),
            367 => 
            array (
                'id' => 82621,
                'area_id' => 632126,
                'name' => '互助土族自治县',
            ),
            368 => 
            array (
                'id' => 82648,
                'area_id' => 632127,
                'name' => '化隆回族自治县',
            ),
            369 => 
            array (
                'id' => 82675,
                'area_id' => 632128,
                'name' => '循化撒拉族自治县',
            ),
            370 => 
            array (
                'id' => 82696,
                'area_id' => 632221,
                'name' => '门源回族自治县',
            ),
            371 => 
            array (
                'id' => 82723,
                'area_id' => 632222,
                'name' => '祁连县',
            ),
            372 => 
            array (
                'id' => 82750,
                'area_id' => 632223,
                'name' => '海晏县',
            ),
            373 => 
            array (
                'id' => 82777,
                'area_id' => 632224,
                'name' => '刚察县',
            ),
            374 => 
            array (
                'id' => 82800,
                'area_id' => 632321,
                'name' => '同仁县',
            ),
            375 => 
            array (
                'id' => 82827,
                'area_id' => 632322,
                'name' => '尖扎县',
            ),
            376 => 
            array (
                'id' => 82854,
                'area_id' => 632323,
                'name' => '泽库县',
            ),
            377 => 
            array (
                'id' => 82881,
                'area_id' => 632324,
                'name' => '河南蒙古族自治县',
            ),
            378 => 
            array (
                'id' => 82904,
                'area_id' => 632521,
                'name' => '共和县',
            ),
            379 => 
            array (
                'id' => 82931,
                'area_id' => 632522,
                'name' => '同德县',
            ),
            380 => 
            array (
                'id' => 82958,
                'area_id' => 632523,
                'name' => '贵德县',
            ),
            381 => 
            array (
                'id' => 82985,
                'area_id' => 632524,
                'name' => '兴海县',
            ),
            382 => 
            array (
                'id' => 83012,
                'area_id' => 632525,
                'name' => '贵南县',
            ),
            383 => 
            array (
                'id' => 83034,
                'area_id' => 632621,
                'name' => '玛沁县',
            ),
            384 => 
            array (
                'id' => 83061,
                'area_id' => 632622,
                'name' => '班玛县',
            ),
            385 => 
            array (
                'id' => 83088,
                'area_id' => 632623,
                'name' => '甘德县',
            ),
            386 => 
            array (
                'id' => 83115,
                'area_id' => 632624,
                'name' => '达日县',
            ),
            387 => 
            array (
                'id' => 83142,
                'area_id' => 632625,
                'name' => '久治县',
            ),
            388 => 
            array (
                'id' => 83169,
                'area_id' => 632626,
                'name' => '玛多县',
            ),
            389 => 
            array (
                'id' => 83190,
                'area_id' => 632721,
                'name' => '玉树县',
            ),
            390 => 
            array (
                'id' => 83217,
                'area_id' => 632722,
                'name' => '杂多县',
            ),
            391 => 
            array (
                'id' => 83244,
                'area_id' => 632723,
                'name' => '称多县',
            ),
            392 => 
            array (
                'id' => 83271,
                'area_id' => 632724,
                'name' => '治多县',
            ),
            393 => 
            array (
                'id' => 83298,
                'area_id' => 632725,
                'name' => '囊谦县',
            ),
            394 => 
            array (
                'id' => 83325,
                'area_id' => 632726,
                'name' => '曲麻莱县',
            ),
            395 => 
            array (
                'id' => 83346,
                'area_id' => 632801,
                'name' => '格尔木市',
            ),
            396 => 
            array (
                'id' => 83373,
                'area_id' => 632802,
                'name' => '德令哈市',
            ),
            397 => 
            array (
                'id' => 83400,
                'area_id' => 632821,
                'name' => '乌兰县',
            ),
            398 => 
            array (
                'id' => 83427,
                'area_id' => 632822,
                'name' => '都兰县',
            ),
            399 => 
            array (
                'id' => 83454,
                'area_id' => 632823,
                'name' => '天峻县',
            ),
            400 => 
            array (
                'id' => 83476,
                'area_id' => 640101,
                'name' => '市辖区',
            ),
            401 => 
            array (
                'id' => 83503,
                'area_id' => 640104,
                'name' => '兴庆区',
            ),
            402 => 
            array (
                'id' => 83530,
                'area_id' => 640105,
                'name' => '西夏区',
            ),
            403 => 
            array (
                'id' => 83557,
                'area_id' => 640106,
                'name' => '金凤区',
            ),
            404 => 
            array (
                'id' => 83584,
                'area_id' => 640121,
                'name' => '永宁县',
            ),
            405 => 
            array (
                'id' => 83611,
                'area_id' => 640122,
                'name' => '贺兰县',
            ),
            406 => 
            array (
                'id' => 83638,
                'area_id' => 640181,
                'name' => '灵武市',
            ),
            407 => 
            array (
                'id' => 83658,
                'area_id' => 640201,
                'name' => '市辖区',
            ),
            408 => 
            array (
                'id' => 83685,
                'area_id' => 640202,
                'name' => '大武口区',
            ),
            409 => 
            array (
                'id' => 83712,
                'area_id' => 640205,
                'name' => '惠农区',
            ),
            410 => 
            array (
                'id' => 83739,
                'area_id' => 640221,
                'name' => '平罗县',
            ),
            411 => 
            array (
                'id' => 83762,
                'area_id' => 640301,
                'name' => '市辖区',
            ),
            412 => 
            array (
                'id' => 83789,
                'area_id' => 640302,
                'name' => '利通区',
            ),
            413 => 
            array (
                'id' => 83816,
                'area_id' => 640323,
                'name' => '盐池县',
            ),
            414 => 
            array (
                'id' => 83843,
                'area_id' => 640324,
                'name' => '同心县',
            ),
            415 => 
            array (
                'id' => 83870,
                'area_id' => 640381,
                'name' => '青铜峡市',
            ),
            416 => 
            array (
                'id' => 83892,
                'area_id' => 640401,
                'name' => '市辖区',
            ),
            417 => 
            array (
                'id' => 83919,
                'area_id' => 640402,
                'name' => '原州区',
            ),
            418 => 
            array (
                'id' => 83946,
                'area_id' => 640422,
                'name' => '西吉县',
            ),
            419 => 
            array (
                'id' => 83973,
                'area_id' => 640423,
                'name' => '隆德县',
            ),
            420 => 
            array (
                'id' => 84000,
                'area_id' => 640424,
                'name' => '泾源县',
            ),
            421 => 
            array (
                'id' => 84027,
                'area_id' => 640425,
                'name' => '彭阳县',
            ),
            422 => 
            array (
                'id' => 84048,
                'area_id' => 640501,
                'name' => '市辖区',
            ),
            423 => 
            array (
                'id' => 84075,
                'area_id' => 640502,
                'name' => '沙坡头区',
            ),
            424 => 
            array (
                'id' => 84102,
                'area_id' => 640521,
                'name' => '中宁县',
            ),
            425 => 
            array (
                'id' => 84129,
                'area_id' => 640522,
                'name' => '海原县',
            ),
            426 => 
            array (
                'id' => 84152,
                'area_id' => 650101,
                'name' => '市辖区',
            ),
            427 => 
            array (
                'id' => 84179,
                'area_id' => 650102,
                'name' => '天山区',
            ),
            428 => 
            array (
                'id' => 84206,
                'area_id' => 650103,
                'name' => '沙依巴克区',
            ),
            429 => 
            array (
                'id' => 84233,
                'area_id' => 650104,
                'name' => '新市区',
            ),
            430 => 
            array (
                'id' => 84260,
                'area_id' => 650105,
                'name' => '水磨沟区',
            ),
            431 => 
            array (
                'id' => 84287,
                'area_id' => 650106,
                'name' => '头屯河区',
            ),
            432 => 
            array (
                'id' => 84314,
                'area_id' => 650107,
                'name' => '达坂城区',
            ),
            433 => 
            array (
                'id' => 84341,
                'area_id' => 650108,
                'name' => '东山区',
            ),
            434 => 
            array (
                'id' => 84368,
                'area_id' => 650121,
                'name' => '乌鲁木齐县',
            ),
            435 => 
            array (
                'id' => 84386,
                'area_id' => 650201,
                'name' => '市辖区',
            ),
            436 => 
            array (
                'id' => 84413,
                'area_id' => 650202,
                'name' => '独山子区',
            ),
            437 => 
            array (
                'id' => 84440,
                'area_id' => 650203,
                'name' => '克拉玛依区',
            ),
            438 => 
            array (
                'id' => 84467,
                'area_id' => 650204,
                'name' => '白碱滩区',
            ),
            439 => 
            array (
                'id' => 84494,
                'area_id' => 650205,
                'name' => '乌尔禾区',
            ),
            440 => 
            array (
                'id' => 84516,
                'area_id' => 652101,
                'name' => '吐鲁番市',
            ),
            441 => 
            array (
                'id' => 84543,
                'area_id' => 652122,
                'name' => '鄯善县',
            ),
            442 => 
            array (
                'id' => 84570,
                'area_id' => 652123,
                'name' => '托克逊县',
            ),
            443 => 
            array (
                'id' => 84594,
                'area_id' => 652201,
                'name' => '哈密市',
            ),
            444 => 
            array (
                'id' => 84621,
                'area_id' => 652222,
                'name' => '巴里坤哈萨克自治县',
            ),
            445 => 
            array (
                'id' => 84648,
                'area_id' => 652223,
                'name' => '伊吾县',
            ),
            446 => 
            array (
                'id' => 84672,
                'area_id' => 652301,
                'name' => '昌吉市',
            ),
            447 => 
            array (
                'id' => 84699,
                'area_id' => 652302,
                'name' => '阜康市',
            ),
            448 => 
            array (
                'id' => 84726,
                'area_id' => 652303,
                'name' => '米泉市',
            ),
            449 => 
            array (
                'id' => 84753,
                'area_id' => 652323,
                'name' => '呼图壁县',
            ),
            450 => 
            array (
                'id' => 84780,
                'area_id' => 652324,
                'name' => '玛纳斯县',
            ),
            451 => 
            array (
                'id' => 84807,
                'area_id' => 652325,
                'name' => '奇台县',
            ),
            452 => 
            array (
                'id' => 84834,
                'area_id' => 652327,
                'name' => '吉木萨尔县',
            ),
            453 => 
            array (
                'id' => 84861,
                'area_id' => 652328,
                'name' => '木垒哈萨克自治县',
            ),
            454 => 
            array (
                'id' => 84880,
                'area_id' => 652701,
                'name' => '博乐市',
            ),
            455 => 
            array (
                'id' => 84907,
                'area_id' => 652722,
                'name' => '精河县',
            ),
            456 => 
            array (
                'id' => 84934,
                'area_id' => 652723,
                'name' => '温泉县',
            ),
            457 => 
            array (
                'id' => 84958,
                'area_id' => 652801,
                'name' => '库尔勒市',
            ),
            458 => 
            array (
                'id' => 84985,
                'area_id' => 652822,
                'name' => '轮台县',
            ),
            459 => 
            array (
                'id' => 85012,
                'area_id' => 652823,
                'name' => '尉犁县',
            ),
            460 => 
            array (
                'id' => 85039,
                'area_id' => 652824,
                'name' => '若羌县',
            ),
            461 => 
            array (
                'id' => 85066,
                'area_id' => 652825,
                'name' => '且末县',
            ),
            462 => 
            array (
                'id' => 85093,
                'area_id' => 652826,
                'name' => '焉耆回族自治县',
            ),
            463 => 
            array (
                'id' => 85120,
                'area_id' => 652827,
                'name' => '和静县',
            ),
            464 => 
            array (
                'id' => 85147,
                'area_id' => 652828,
                'name' => '和硕县',
            ),
            465 => 
            array (
                'id' => 85174,
                'area_id' => 652829,
                'name' => '博湖县',
            ),
            466 => 
            array (
                'id' => 85192,
                'area_id' => 652901,
                'name' => '阿克苏市',
            ),
            467 => 
            array (
                'id' => 85219,
                'area_id' => 652922,
                'name' => '温宿县',
            ),
            468 => 
            array (
                'id' => 85246,
                'area_id' => 652923,
                'name' => '库车县',
            ),
            469 => 
            array (
                'id' => 85273,
                'area_id' => 652924,
                'name' => '沙雅县',
            ),
            470 => 
            array (
                'id' => 85300,
                'area_id' => 652925,
                'name' => '新和县',
            ),
            471 => 
            array (
                'id' => 85327,
                'area_id' => 652926,
                'name' => '拜城县',
            ),
            472 => 
            array (
                'id' => 85354,
                'area_id' => 652927,
                'name' => '乌什县',
            ),
            473 => 
            array (
                'id' => 85381,
                'area_id' => 652928,
                'name' => '阿瓦提县',
            ),
            474 => 
            array (
                'id' => 85408,
                'area_id' => 652929,
                'name' => '柯坪县',
            ),
            475 => 
            array (
                'id' => 85426,
                'area_id' => 653001,
                'name' => '阿图什市',
            ),
            476 => 
            array (
                'id' => 85453,
                'area_id' => 653022,
                'name' => '阿克陶县',
            ),
            477 => 
            array (
                'id' => 85480,
                'area_id' => 653023,
                'name' => '阿合奇县',
            ),
            478 => 
            array (
                'id' => 85507,
                'area_id' => 653024,
                'name' => '乌恰县',
            ),
            479 => 
            array (
                'id' => 85530,
                'area_id' => 653101,
                'name' => '喀什市',
            ),
            480 => 
            array (
                'id' => 85557,
                'area_id' => 653121,
                'name' => '疏附县',
            ),
            481 => 
            array (
                'id' => 85584,
                'area_id' => 653122,
                'name' => '疏勒县',
            ),
            482 => 
            array (
                'id' => 85611,
                'area_id' => 653123,
                'name' => '英吉沙县',
            ),
            483 => 
            array (
                'id' => 85638,
                'area_id' => 653124,
                'name' => '泽普县',
            ),
            484 => 
            array (
                'id' => 85665,
                'area_id' => 653125,
                'name' => '莎车县',
            ),
            485 => 
            array (
                'id' => 85692,
                'area_id' => 653126,
                'name' => '叶城县',
            ),
            486 => 
            array (
                'id' => 85719,
                'area_id' => 653127,
                'name' => '麦盖提县',
            ),
            487 => 
            array (
                'id' => 85746,
                'area_id' => 653128,
                'name' => '岳普湖县',
            ),
            488 => 
            array (
                'id' => 85773,
                'area_id' => 653129,
                'name' => '伽师县',
            ),
            489 => 
            array (
                'id' => 85800,
                'area_id' => 653130,
                'name' => '巴楚县',
            ),
            490 => 
            array (
                'id' => 85827,
                'area_id' => 653131,
                'name' => '塔什库尔干塔吉克自治县',
            ),
            491 => 
            array (
                'id' => 85842,
                'area_id' => 653201,
                'name' => '和田市',
            ),
            492 => 
            array (
                'id' => 85869,
                'area_id' => 653221,
                'name' => '和田县',
            ),
            493 => 
            array (
                'id' => 85896,
                'area_id' => 653222,
                'name' => '墨玉县',
            ),
            494 => 
            array (
                'id' => 85923,
                'area_id' => 653223,
                'name' => '皮山县',
            ),
            495 => 
            array (
                'id' => 85950,
                'area_id' => 653224,
                'name' => '洛浦县',
            ),
            496 => 
            array (
                'id' => 85977,
                'area_id' => 653225,
                'name' => '策勒县',
            ),
            497 => 
            array (
                'id' => 86004,
                'area_id' => 653226,
                'name' => '于田县',
            ),
            498 => 
            array (
                'id' => 86031,
                'area_id' => 653227,
                'name' => '民丰县',
            ),
            499 => 
            array (
                'id' => 86050,
                'area_id' => 654002,
                'name' => '伊宁市',
            ),
        ));
        \DB::table('areas')->insert(array (
            0 => 
            array (
                'id' => 86077,
                'area_id' => 654003,
                'name' => '奎屯市',
            ),
            1 => 
            array (
                'id' => 86104,
                'area_id' => 654021,
                'name' => '伊宁县',
            ),
            2 => 
            array (
                'id' => 86131,
                'area_id' => 654022,
                'name' => '察布查尔锡伯自治县',
            ),
            3 => 
            array (
                'id' => 86158,
                'area_id' => 654023,
                'name' => '霍城县',
            ),
            4 => 
            array (
                'id' => 86185,
                'area_id' => 654024,
                'name' => '巩留县',
            ),
            5 => 
            array (
                'id' => 86212,
                'area_id' => 654025,
                'name' => '新源县',
            ),
            6 => 
            array (
                'id' => 86239,
                'area_id' => 654026,
                'name' => '昭苏县',
            ),
            7 => 
            array (
                'id' => 86266,
                'area_id' => 654027,
                'name' => '特克斯县',
            ),
            8 => 
            array (
                'id' => 86293,
                'area_id' => 654028,
                'name' => '尼勒克县',
            ),
            9 => 
            array (
                'id' => 86310,
                'area_id' => 654201,
                'name' => '塔城市',
            ),
            10 => 
            array (
                'id' => 86337,
                'area_id' => 654202,
                'name' => '乌苏市',
            ),
            11 => 
            array (
                'id' => 86364,
                'area_id' => 654221,
                'name' => '额敏县',
            ),
            12 => 
            array (
                'id' => 86391,
                'area_id' => 654223,
                'name' => '沙湾县',
            ),
            13 => 
            array (
                'id' => 86418,
                'area_id' => 654224,
                'name' => '托里县',
            ),
            14 => 
            array (
                'id' => 86445,
                'area_id' => 654225,
                'name' => '裕民县',
            ),
            15 => 
            array (
                'id' => 86472,
                'area_id' => 654226,
                'name' => '和布克赛尔蒙古自治县',
            ),
            16 => 
            array (
                'id' => 86492,
                'area_id' => 654301,
                'name' => '阿勒泰市',
            ),
            17 => 
            array (
                'id' => 86519,
                'area_id' => 654321,
                'name' => '布尔津县',
            ),
            18 => 
            array (
                'id' => 86546,
                'area_id' => 654322,
                'name' => '富蕴县',
            ),
            19 => 
            array (
                'id' => 86573,
                'area_id' => 654323,
                'name' => '福海县',
            ),
            20 => 
            array (
                'id' => 86600,
                'area_id' => 654324,
                'name' => '哈巴河县',
            ),
            21 => 
            array (
                'id' => 86627,
                'area_id' => 654325,
                'name' => '青河县',
            ),
            22 => 
            array (
                'id' => 86654,
                'area_id' => 654326,
                'name' => '吉木乃县',
            ),
            23 => 
            array (
                'id' => 86674,
                'area_id' => 659001,
                'name' => '石河子市',
            ),
            24 => 
            array (
                'id' => 86701,
                'area_id' => 659002,
                'name' => '阿拉尔市',
            ),
            25 => 
            array (
                'id' => 86728,
                'area_id' => 659003,
                'name' => '图木舒克市',
            ),
            26 => 
            array (
                'id' => 86755,
                'area_id' => 659004,
                'name' => '五家渠市',
            ),
            27 => 
            array (
                'id' => 86778,
                'area_id' => 710002,
                'name' => '台北县',
            ),
            28 => 
            array (
                'id' => 86804,
                'area_id' => 710004,
                'name' => '花莲县',
            ),
            29 => 
            array (
                'id' => 86830,
                'area_id' => 810002,
                'name' => '中西区',
            ),
            30 => 
            array (
                'id' => 86857,
                'area_id' => 810003,
                'name' => '九龙城区',
            ),
            31 => 
            array (
                'id' => 86884,
                'area_id' => 810004,
                'name' => '南区',
            ),
            32 => 
            array (
                'id' => 86911,
                'area_id' => 810005,
                'name' => '黄大仙区',
            ),
            33 => 
            array (
                'id' => 86938,
                'area_id' => 810006,
                'name' => '油尖旺区',
            ),
            34 => 
            array (
                'id' => 86965,
                'area_id' => 810007,
                'name' => '葵青区',
            ),
            35 => 
            array (
                'id' => 86992,
                'area_id' => 810008,
                'name' => '西贡区',
            ),
            36 => 
            array (
                'id' => 87019,
                'area_id' => 810009,
                'name' => '屯门区',
            ),
            37 => 
            array (
                'id' => 87046,
                'area_id' => 810010,
                'name' => '荃湾区',
            ),
            38 => 
            array (
                'id' => 87073,
                'area_id' => 810011,
                'name' => '东区',
            ),
            39 => 
            array (
                'id' => 87100,
                'area_id' => 810012,
                'name' => '观塘区',
            ),
            40 => 
            array (
                'id' => 87127,
                'area_id' => 810013,
                'name' => '深水步区',
            ),
            41 => 
            array (
                'id' => 87154,
                'area_id' => 810014,
                'name' => '湾仔区',
            ),
            42 => 
            array (
                'id' => 87181,
                'area_id' => 810015,
                'name' => '离岛区',
            ),
            43 => 
            array (
                'id' => 87208,
                'area_id' => 810016,
                'name' => '北区',
            ),
            44 => 
            array (
                'id' => 87235,
                'area_id' => 810017,
                'name' => '沙田区',
            ),
            45 => 
            array (
                'id' => 87262,
                'area_id' => 810018,
                'name' => '大埔区',
            ),
            46 => 
            array (
                'id' => 87289,
                'area_id' => 810019,
                'name' => '元朗区',
            ),
            47 => 
            array (
                'id' => 87298,
                'area_id' => 820002,
                'name' => '澳门',
            ),
        ));
        
        
    }
}