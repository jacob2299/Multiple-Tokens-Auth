<?php

namespace MultipleTokenAuth;

use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    protected $fillable = [
        'api_token',
        'api_token'
    ];

    public function user()
    {
        return $this->belongsTo(config('multiple-tokens-auth.model'));
    }
}
