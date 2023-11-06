<?php

namespace Nocs\Cabin\Support;

use Nocs\Cabin\Models\CabinLock;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CabinManager
{

    /**
     * [$app description]
     * @var [type]
     */
    protected $app;
    private $sessionID;

    /**
     * Constructor
     *
     * @param App $app App
     */
    public function __construct($app = null)
    {
        $this->app = $app;

        $this->sessionID = session()->getId() ?? null;
    }

    public function lock ($key)
    {
        if ($this->isLocked($key)) {
            return false;
        }

        $lock = CabinLock::firstOrNew([
            'key'           => $this->createKey($key),
            'session_id'    => $this->sessionID,
        ]);

        $lock->locked_at = Carbon::now();

        if (! $lock->exists) {
            $lock->locked_by = Auth::id() ?? null;
        }

        return $lock->save();
    }

    public function unlock ($key)
    {
        CabinLock::where([
            'key'           => $this->createKey($key),
            'session_id'    => $this->sessionID,
        ])->delete();

        return true;
    }

    public function isLocked ($key)
    {
        $this->removeExpired();

        return CabinLock::where('key', $this->createKey($key))
            ->where('session_id', '!=', $this->sessionID)
            ->count()
                > 0;
    }

    public function removeExpired ()
    {
        $time = Carbon::now()->subSeconds( config('cabin.expiration_time', 10 * 60) );
        
        CabinLock::where( 'locked_at', '<=', $time )->delete();

        return true;
    }

    public function ping ($key)
    {
        if ($lock = CabinLock::where([
            'key'           => $this->createKey($key),
            'session_id'    => $this->sessionID,
        ])->first()) {
            
            $lock->locked_at = Carbon::now();
            return $lock->save();

        }

        return false;
    }

    public function createKey($key)
    {
        return md5(Str::slug($key));
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
        static::throwBadMethodCallException($method);
    }

}