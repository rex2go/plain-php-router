<?php

namespace App\User;

class DefaultUser extends BaseUser
{

    const ACCESS_LEVEL = 1;

    public function __construct(int $id, string $email, string $vorname, string $nachname)
    {
        parent::__construct($id, $email, $vorname, $nachname, self::ACCESS_LEVEL);
    }
}