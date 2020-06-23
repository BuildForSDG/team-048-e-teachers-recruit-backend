<?php

namespace App\Repositories\UserExperience;

interface UserExperienceInterface
{

    public function getAll();

    public function findById(int $id);

    public function findByColumn(array $params);

    public function create(array $params);

}
