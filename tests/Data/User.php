<?php

namespace Tests\Data;

use Illuminate\Foundation\Auth\User as Authenticatable;
use MultipleTokenAuth\HasMultipleApiTokens;

class User extends Authenticatable
{
    use HasMultipleApiTokens;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];
}
