<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    /**
     * Define the polymorphic relationship to the tokenable model.
     */
    public function tokenable()
    {
        return $this->morphTo('tokenable', 'tokenable_type', 'tokenable_id');
    }
}
