<?php

namespace Nocs\Cabin\Tests\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UuidUser extends Authenticatable
{
    protected $table = 'uuid_users';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];
}
