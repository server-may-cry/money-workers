<?php

namespace App;

interface StorageKeys
{
    /**
     * uid:123
     * 123 - user id.
     */
    public const USER_PREFIX = 'uid:';

    /**
     * lock:123
     * 123 - lock id.
     */
    public const LOCK_PREFIX = 'lock:';
}
