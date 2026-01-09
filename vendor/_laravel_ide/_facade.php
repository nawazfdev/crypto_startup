<?php

namespace Illuminate\Support\Facades;

interface Auth
{
    /**
     * @return \TCG\Voyager\Models\User|false
     */
    public static function loginUsingId(mixed $id, bool $remember = false);

    /**
     * @return \TCG\Voyager\Models\User|false
     */
    public static function onceUsingId(mixed $id);

    /**
     * @return \TCG\Voyager\Models\User|null
     */
    public static function getUser();

    /**
     * @return \TCG\Voyager\Models\User
     */
    public static function authenticate();

    /**
     * @return \TCG\Voyager\Models\User|null
     */
    public static function user();

    /**
     * @return \TCG\Voyager\Models\User|null
     */
    public static function logoutOtherDevices(string $password);

    /**
     * @return \TCG\Voyager\Models\User
     */
    public static function getLastAttempted();
}