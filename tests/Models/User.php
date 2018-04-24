<?php

namespace Signifly\PivotEvents\Test\Models;

use Illuminate\Database\Eloquent\Model;
use Signifly\PivotEvents\HasPivotEvents;

class User extends Model
{
    use HasPivotEvents;

    protected $fillable = [
        'name', 'email',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class)
            ->withPivot(['scopes'])
            ->withTimestamps();
    }
}
