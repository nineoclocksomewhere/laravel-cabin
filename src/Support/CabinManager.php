<?php

namespace Nocs\Cabin\Support;

class CabinManager
{

    /**
     * [$app description]
     * @var [type]
     */
    protected $app;

    /**
     * Constructor
     *
     * @param App $app App
     */
    public function __construct($app = null)
    {

        $this->app = $app;

    }

    /**
     * Callback
     *
     * @param  string $method     Method name
     * @param  array  $parameters Provided parameters
     * @return mixed              Result of method callback
     */
    public function __call($method, $parameters)
    {

        // ...

        static::throwBadMethodCallException($method);
    }

}