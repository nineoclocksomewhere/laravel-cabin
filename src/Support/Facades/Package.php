<?php

namespace Nocs\Package\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Package facade class
 */
class Package extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {

        return 'package';

    }

}