<?php

namespace Illuminate\Http;

interface Request
{
    /**
     * @return \TCG\Voyager\Models\User|null
     */
    public function user($guard = null);
}