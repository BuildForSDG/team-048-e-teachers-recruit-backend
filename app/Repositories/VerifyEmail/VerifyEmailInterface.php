<?php

namespace App\Repositories\VerifyEmail;

interface VerifyEmailInterface
{
    public function create($email,$token);
    public function findByColumn(array $params);
    public function verifyEmail($data);
}
