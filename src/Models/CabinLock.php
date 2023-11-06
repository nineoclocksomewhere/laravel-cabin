<?php

namespace Nocs\Cabin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabinLock extends Model
{
    use HasFactory;

    protected $table = 'cabin_lock';
    public $timestamps = false;

    /**
     * Fillable properties
     *
     * @var array
     */
    protected $fillable = [
        'key', 'session_id', 'locked_by', 'locked_at'
    ];
}
