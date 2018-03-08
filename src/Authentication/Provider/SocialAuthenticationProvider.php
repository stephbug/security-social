<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Authentication\Provider;

use StephBug\SecurityModel\Application\Exception\UnsupportedProvider;
use StephBug\SecurityModel\Application\Values\SecurityKey;
use StephBug\SecurityModel\Guard\Authentication\Providers\AuthenticationProvider;
use StephBug\SecurityModel\Guard\Authentication\SimplePreAuthenticator;
use StephBug\SecurityModel\Guard\Authentication\Token\Tokenable;
use StephBug\SecurityModel\User\UserProvider;

class SocialAuthenticationProvider implements AuthenticationProvider
{
    /**
     * @var SimplePreAuthenticator
     */
    private $authenticator;

    /**
     * @var UserProvider
     */
    private $userProvider;

    /**
     * @var SecurityKey
     */
    private $securityKey;

    public function __construct(SimplePreAuthenticator $authenticator, UserProvider $userProvider, SecurityKey $securityKey)
    {
        $this->authenticator = $authenticator;
        $this->userProvider = $userProvider;
        $this->securityKey = $securityKey;
    }

    public function authenticate(Tokenable $token): Tokenable
    {
        if(!$this->supports($token)){
            throw UnsupportedProvider::withSupport($token, $this);
        }

        return $this->authenticator->authenticateToken($token, $this->userProvider, $this->securityKey);
    }

    public function supports(Tokenable $token): bool
    {
        return $this->authenticator->supportsToken($token, $this->securityKey);
    }
}