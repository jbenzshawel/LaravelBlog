<?php
/**
 * Created by PhpStorm.
 * User: addison
 * Date: 2/28/16
 * Time: 2:15 PM
 */

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\IRepository;
use Illuminate\Database\Eloquent\Model;


abstract class Repository implements IRepository
{
    /**
     * @var model
     */
    protected $_model;

    /**
     * @param App $app
     *
     */
    public function __construct ()
    {
        $modelClass = $this->model();
        $this->_model = new $modelClass();
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

        return $this->_model->get();
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function Fields(array $columns)
    {
        $response = array();
        if (count($columns) > 0) {
            $response = $this->_model->get($columns);
        }
        return $response;
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function Paginate($perPage = 15, $columns = array('*'))
    {
        return $this->_model->paginate($perPage, $columns);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function Create(array $data)
    {
        return $this->_model->create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function Update(array $data, $id, $attribute="id")
    {
        return $this->_model->where($attribute, $id)->update($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function Delete($id)
    {
        return $this->_model->destroy($id);
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function Find($id, $columns = array('*'))
    {
        return $this->_model->find($id, $columns);
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function FindBy($attribute, $value, $columns = array('*'))
    {
        return $this->_model->where($attribute, '=', $value)->first($columns);
    }

}