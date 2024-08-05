<?php

namespace Nocs\Cabin\Support;

use Nocs\Cabin\Models\CabinLock;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CabinManager
{

    /**
     * [$app description]
     * @var [type]
     */
    protected $app;
    private $sessionID;
    private $connection;

    /**
     * Constructor
     *
     * @param App $app App
     */
    public function __construct($app = null)
    {
        $this->app = $app;

        $this->connection = config('database.default');

        $this->sessionID = session()->getId() ?? null;
    }

    public function connection($connection)
    {
        $this->connection = $connection;
        return $this;
    }

    public function refreshSessionID ()
    {
        $this->sessionID = session()->getId() ?? null;
    }

    public function lock ($key)
    {
        if ($this->isLocked($key)) {
            return false;
        }

        $lock = CabinLock::on($this->connection)->firstOrNew([
            'key'           => $this->createKey($key),
            'session_id'    => $this->sessionID,
        ]);

        $lock->locked_at = Carbon::now();

        if (! $lock->exists) {
            $lock->locked_by = Auth::id() ?? null;
            $lock->locked_guard = $this->lookupGuard();
        }

        return $lock->save();
    }

    public function unlock ($key)
    {
        CabinLock::on($this->connection)->where([
            'key'           => $this->createKey($key),
            'session_id'    => $this->sessionID,
        ])->delete();

        return true;
    }

    public function isLocked ($key)
    {
        $this->removeExpired();

        return CabinLock::on($this->connection)->where('key', $this->createKey($key))
            ->where('session_id', '!=', $this->sessionID)
            ->count()
                > 0;
    }

    public function lockedBy ($key)
    {
        $this->removeExpired();
        
        return CabinLock::on($this->connection)->where('key', $this->createKey($key))
            ->value('locked_by') ?? false;
    }

    public function removeExpired ()
    {
        $time = Carbon::now()->subSeconds( config('cabin.expiration_time', 10 * 60) );
        
        CabinLock::on($this->connection)->where( 'locked_at', '<=', $time )->delete();

        return true;
    }

    public function ping ($key)
    {
        if ($lock = CabinLock::on($this->connection)->where([
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

    private function lookupGuard() {
        if ($user = Auth::user()) {
            $guards = Arr::where(array_keys(config('auth.guards')), function (string|int $value, int $key) {
                return $value != 'sanctum';
            });
            foreach ($guards as $guard) {
                if (Auth::guard($guard)->check()) {
                    return $guard;
                }
            }
        }

        return Auth::getDefaultDriver();
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