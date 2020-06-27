<?php

namespace App\User;

use App\App;

class AdminUser extends BaseUser
{

    const ACCESS_LEVEL = 2;

    public function __construct(int $id, string $email, string $vorname, string $nachname)
    {
        parent::__construct($id, $email, $vorname, $nachname, self::ACCESS_LEVEL);
    }
}