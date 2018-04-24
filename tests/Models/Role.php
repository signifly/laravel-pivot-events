<?php

namespace Signifly\PivotEvents\Test\Models;

use Illuminate\Database\Eloquent\Model;
use Signifly\PivotEvents\HasPivotEvents;

class Role extends Model
{
    use HasPivotEvents;

    protected $fillable = [
        'name',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['scopes'])
            ->withTimestamps();
    }
}
