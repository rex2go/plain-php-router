<?php

namespace App\Routing\Routes;

use App\Service\SecurityService;
use App\User\BaseUser;

class LogoutRoute extends BaseRoute
{
    private $securityService;

    public function __construct(SecurityService $securityService)
    {
        $this->securityService = $securityService;
        parent::__construct(['/logout']);
    }

    public function handle(?BaseUser $user, string $method): void
    {
        if($user != null) $this->securityService->logout();
        header('Location: /login');
    }
}