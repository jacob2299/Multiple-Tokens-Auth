<?php

namespace MultipleTokenAuth;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

trait HasMultipleApiTokens
{
    public function createToken() : string
    {
        $token = Str::random(60);

        $this->tokens()->create([
            'api_token' => $token
        ]);

        $this->resetToMaxActiveTokens();

        return $token;
    }

    public function removeToken($token) : void
    {
        $this->tokens()->where('user_id', $this->getKey(), 'api_token', $token)->delete();
    }
    
    public function tokens() : HasMany
    {
        return $this->hasMany(ApiToken::class);
    }

    protected function resetToMaxActiveTokens() : void
    {
        $totalActiveTokens = config('multiple-tokens-auth.active_tokens');

        if ($totalActiveTokens !== null && $this->tokens()->count() > $totalActiveTokens) {
            $this->tokens()->latest()->skip($totalActiveTokens)->delete();
        }
    }
}
