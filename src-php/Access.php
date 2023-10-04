<?php

namespace Bradoctech\NovaToolPermissions;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    protected $casts = [
        'roles' => 'array',
    ];

    protected $fillable = [
        'roles',
    ];

    public function accessible()
    {
        return $this->morphTo();
    }
}
