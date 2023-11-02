<?php

if (! function_exists('package')) {
    /**
     * package helper
     *
     * @param  dynamic  null
     * @return mixed|\Nocs\Package\Support\PackageManager
     *
     * @throws \Exception
     */
    function package()
    {
        return app('package');
    }
}
