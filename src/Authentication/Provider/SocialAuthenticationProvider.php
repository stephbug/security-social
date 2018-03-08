<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Authentication\Provider;

use StephBug\SecurityModel\Guard\Authentication\Providers\AuthenticationProvider;
use StephBug\SecurityModel\Guard\Authentication\Token\Tokenable;

class SocialAuthenticationProvider implements AuthenticationProvider
{

    public function authenticate(Tokenable $token): Tokenable
    {
        // TODO: Implement authenticate() method.
    }

    public function supports(Tokenable $token): bool
    {
        // TODO: Implement supports() method.
    }
}