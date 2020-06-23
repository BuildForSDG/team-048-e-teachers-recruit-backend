<?php

namespace App\Repositories\UserBioData;

interface UserBioDataInterface
{

    public function getAll();

    public function findById(int $id);

    public function findByColumn(array $params);

    public function create(array $params);

}
