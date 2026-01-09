<?php

namespace Illuminate\Contracts\Auth;

interface Guard
{
    /**
     * @return \TCG\Voyager\Models\User|null
     */
    public function user();
}