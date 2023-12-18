<?php

namespace Nocs\Cabin\Tests\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Nocs\Cabin\Tests\Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }

}
