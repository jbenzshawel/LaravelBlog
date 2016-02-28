<?php
/**
 * Created by PhpStorm.
 * User: addison
 * Date: 2/28/16
 * Time: 2:15 PM
 */

namespace App\Repositories\Eloquent;

use App\Posts;

use App\Repositories\Interfaces\IRepository;
//use App\Repositories\Exceptions;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Container\Container as App;

abstract class Repository implements IRepository
{
    /**
     * @var App
     */
    private $app;

    /**
     * @var model
     */
    protected $model;

    /**
     * @param App $app
     *
     */
    public function __construct()
    {
        $modelClass = $this->model();
        $this->model = new $modelClass();
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    abstract function model();


    /**
     * @param array $columns
     * @return mixed
     */
    public function All()
    {

        return $this->model->get();
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function Paginate($perPage = 15, $columns = array('*'))
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function Create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function Update(array $data, $id, $attribute="id")
    {
        return $this->model->where($attribute, '=', $id)->update($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function Delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function Find($id, $columns = array('*'))
    {
        return $this->model->find($id, $columns);
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function FindBy($attribute, $value, $columns = array('*'))
    {
        return $this->model->where($attribute, '=', $value)->first($columns);
    }

}