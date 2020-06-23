<?php

namespace App\Repositories\UserEducation;

interface UserEducationInterface
{

    public function getAll();

    public function findById(int $id);

    public function findByColumn(array $params);

    public function create(array $params);

}
