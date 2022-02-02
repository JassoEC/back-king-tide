<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{

    protected $model;

    public function __construct(User $user)
    {
        parent::__construct($user);
    }
}