<?php

namespace App\Repositories\Interfaces;

interface IRepository
{
    public function All ();

    public function Fields($columns = array('*'));

    public function Paginate ($perPage = 15, $columns = array('*'));

    public function Create (array $data);

    public function Update (array $data, $id);

    public function Delete ($id);

    public function Find ($id, $columns = array('*'));

    public function FindBy ($field, $value, $columns = array('*'));
}