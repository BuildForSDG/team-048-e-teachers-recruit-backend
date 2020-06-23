<?php

namespace App\Repositories\UserSkill;

interface UserSkillInterface
{

    public function getAll();

    public function findById(int $id);

    public function findByColumn(array $params);

    public function create(array $params);

}
