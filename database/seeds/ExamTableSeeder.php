<?php

use Illuminate\Database\Seeder;

class ExamTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('exam')->delete();

        //添加问诊单
        \DB::table('exam')->insert([
            [
                'id' => '1',
                'title' => '系统默认问诊单',
                'doctor_id' => '0',
                'type' => '0',
            ],
            [
                'id' => '2',
                'title' => '成人男',
                'doctor_id' => '0',
                'type' => '1',
            ],
            [
                'id' => '3',
                'title' => '成人女',
                'doctor_id' => '0',
                'type' => '2',
            ],
            [
                'id' => '4',
                'title' => '小儿',
                'doctor_id' => '0',
                'type' => '3',
            ],
        ]);

        //添加问诊单题目
        \DB::table('exam_options')->insert([
            [
                'exam_id' => '1',
                'title' => '姓名', //题目
                'type' => 'text', //类型
                'option' => null,
                'must' => '1',//必选
                'sort' => '1',//排序
            ],
            [
                'exam_id' => '1',
                'title' => '年龄', //题目
                'type' => 'text', //类型
                'option' => null,
                'must' => '1',//必选
                'sort' => '2',//排序
            ],
            [
                'exam_id' => '1',
                'title' => '性别', //题目
                'type' => 'radio', //类型
                'option' => json_encode([['title' => '男'], ['title' => '女']]),
                'must' => '1',//必选
                'sort' => '3',//排序
            ],
            [
                'exam_id' => '1',
                'title' => '之前有没有在其他地方看过', //题目
                'type' => 'radio', //类型
                'option' => json_encode([['title' => '有'], ['title' => '没有']]),
                'must' => '0',//不必选
                'sort' => '4',//排序
            ],
            [
                'exam_id' => '1',
                'title' => '医生怎么说?(如果看过)', //题目
                'type' => 'text', //类型
                'option' => null,
                'must' => '0',//不必选
                'sort' => '5',//排序
            ],
            [
                'exam_id' => '1',
                'title' => '传几张图片我看看吧!(如果需要的话)', //题目
                'type' => 'photo', //类型
                'option' => null,
                'must' => '0',//不必选
                'sort' => '6',//排序
            ],
            [
                'exam_id' => '2',
                'title' => '姓名', //题目
                'type' => 'text', //类型
                'option' => null,
                'must' => '1',//必选
                'sort' => '1',//排序
            ],
            [
                'exam_id' => '2',
                'title' => '年龄', //题目
                'type' => 'text', //类型
                'option' => null,
                'must' => '1',//必选
                'sort' => '2',//排序
            ],
            [
                'exam_id' => '2',
                'title' => '性别', //题目
                'type' => 'radio', //类型
                'option' => json_encode([['title' => '男'], ['title' => '女']]),
                'must' => '1',//必选
                'sort' => '3',//排序
            ],
            [
                'exam_id' => '2',
                'title' => '之前有没有在其他地方看过', //题目
                'type' => 'radio', //类型
                'option' => json_encode([['title' => '有'], ['title' => '没有']]),
                'must' => '0',//不必选
                'sort' => '4',//排序
            ],
            [
                'exam_id' => '2',
                'title' => '医生怎么说?(如果看过)', //题目
                'type' => 'text', //类型
                'option' => null,
                'must' => '0',//不必选
                'sort' => '5',//排序
            ],
            [
                'exam_id' => '2',
                'title' => '传几张图片我看看吧!(如果需要的话)', //题目
                'type' => 'photo', //类型
                'option' => null,
                'must' => '0',//不必选
                'sort' => '6',//排序
            ],
            [
                'exam_id' => '3',
                'title' => '姓名', //题目
                'type' => 'text', //类型
                'option' => null,
                'must' => '1',//必选
                'sort' => '1',//排序
            ],
            [
                'exam_id' => '3',
                'title' => '年龄', //题目
                'type' => 'text', //类型
                'option' => null,
                'must' => '1',//必选
                'sort' => '2',//排序
            ],
            [
                'exam_id' => '3',
                'title' => '性别', //题目
                'type' => 'radio', //类型
                'option' => json_encode([['title' => '男'], ['title' => '女']]),
                'must' => '1',//必选
                'sort' => '3',//排序
            ],
            [
                'exam_id' => '3',
                'title' => '之前有没有在其他地方看过', //题目
                'type' => 'radio', //类型
                'option' => json_encode([['title' => '有'], ['title' => '没有']]),
                'must' => '0',//不必选
                'sort' => '4',//排序
            ],
            [
                'exam_id' => '3',
                'title' => '医生怎么说?(如果看过)', //题目
                'type' => 'text', //类型
                'option' => null,
                'must' => '0',//不必选
                'sort' => '5',//排序
            ],
            [
                'exam_id' => '3',
                'title' => '传几张图片我看看吧!(如果需要的话)', //题目
                'type' => 'photo', //类型
                'option' => null,
                'must' => '0',//不必选
                'sort' => '6',//排序
            ],
            [
                'exam_id' => '4',
                'title' => '姓名', //题目
                'type' => 'text', //类型
                'option' => null,
                'must' => '1',//必选
                'sort' => '1',//排序
            ],
            [
                'exam_id' => '4',
                'title' => '年龄', //题目
                'type' => 'text', //类型
                'option' => null,
                'must' => '1',//必选
                'sort' => '2',//排序
            ],
            [
                'exam_id' => '4',
                'title' => '性别', //题目
                'type' => 'radio', //类型
                'option' => json_encode([['title' => '男'], ['title' => '女']]),
                'must' => '1',//必选
                'sort' => '3',//排序
            ],
            [
                'exam_id' => '4',
                'title' => '之前有没有在其他地方看过', //题目
                'type' => 'radio', //类型
                'option' => json_encode([['title' => '有'], ['title' => '没有']]),
                'must' => '0',//不必选
                'sort' => '4',//排序
            ],
            [
                'exam_id' => '4',
                'title' => '医生怎么说?(如果看过)', //题目
                'type' => 'text', //类型
                'option' => null,
                'must' => '0',//不必选
                'sort' => '5',//排序
            ],
            [
                'exam_id' => '4',
                'title' => '传几张图片我看看吧!(如果需要的话)', //题目
                'type' => 'photo', //类型
                'option' => null,
                'must' => '0',//不必选
                'sort' => '6',//排序
            ],
        ]);
    }
}
