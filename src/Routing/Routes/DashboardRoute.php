<?php

namespace App\Routing\Routes;

use App\User\BaseUser;
use App\User\DefaultUser;

class DashboardRoute extends AuthorizedRoute
{

    public function __construct()
    {
        parent::__construct(['/dashboard'], DefaultUser::ACCESS_LEVEL);
    }

    public function handle(?BaseUser $user, string $method): void
    {
        $this->render('DashboardView', ['name' => $user]);
    }
}