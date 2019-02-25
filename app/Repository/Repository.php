<?php
namespace App\Repository;

use Illuminate\Database\Eloquent\Model;

/**
 * 仓库模式基类
 * Class Repository
 * @Auth: kingofzihua
 * @package App\Repository
 */
abstract class Repository
{
    /**
     * @Auth: kingofzihua
     * @var Model
     */
    protected $model;

    /**
     * 分页条数
     * @Auth: kingofzihua
     * @var int
     */
    protected $page = 15;

    /**
     * @Auth: kingofzihua
     * Repository constructor.
     */
    public function __construct()
    {
        $this->model = $this->model();
    }

    /**
     * 指定model
     * @Auth: kingofzihua
     * @return mixed
     */
    abstract public function model();

    /**
     * 创建数据
     * @Auth: kingofzihua
     * @return Model
     */
    public function create($data)
    {
        return $this->model->create($data);
    }

    /**
     * 通过编号获取
     * @Auth: kingofzihua
     * @return Model
     */
    public function get_data_by_id($id)
    {
        return $this->model->find($id);
    }

    /**
     * 获取所有数据
     * @Auth: kingofzihua
     * @param array $columns
     * @return mixed
     */
    public function get_all_data($columns = ['*'])
    {
        return $this->model->get($columns);
    }

    /**
     * 获取数据列表 分页
     * @Auth: kingofzihua
     * @return mixed
     */
    public function get_data_lists()
    {
        return $this->model->paginate($this->page);
    }

    /**
     * @Auth: kingofzihua
     * @param $method function name
     * @param $parameters parameter
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static())->$method(...$parameters);
    }

    /**
     * @Auth: kingofzihua
     * @param $method function name
     * @param $parameters parameter
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $this->model = $this->model();

        return (new static())->$method(...$parameters);
    }
}