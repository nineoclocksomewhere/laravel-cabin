<?php

namespace Nocs\Cabin\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class CabinLock extends Model
{
    use HasFactory;

    protected $table = 'cabin_lock';
    public $timestamps = true;
    public const CREATED_AT = null;
    public const UPDATED_AT = 'locked_at';

    /**
     * Fillable properties
     *
     * @var array
     */
    protected $fillable = [
        'key', 'session_id', 'locked_by', 'locked_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('cabin.models.user', User::class), 'locked_by');
    }

    public function isExpired(): bool
    {
        $expiredDate = (new Carbon($this->locked_at))->addSeconds(config('cabin.expiration_time', 10 * 60));

        return Carbon::now()->greaterThan($expiredDate);
    }
}
