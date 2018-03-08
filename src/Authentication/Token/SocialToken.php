<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Authentication\Token;

use StephBug\SecurityModel\Application\Values\Contract\Credentials;
use StephBug\SecurityModel\Application\Values\Contract\UserToken;
use StephBug\SecurityModel\Application\Values\SecurityKey;
use StephBug\SecurityModel\Guard\Authentication\Token\Token;
use StephBug\SecuritySocial\Application\Values\SocialProvider;

class SocialToken extends Token
{
    /**
     * @var SocialProvider
     */
    private $socialProvider;

    /**
     * @var SecurityKey
     */
    private $securityKey;

    /**
     * @var Credentials
     */
    private $credentials;

    public function __construct(UserToken $user,
                                SocialProvider $socialProvider,
                                SecurityKey $securityKey,
                                Credentials $credentials,
                                array $roles = [])
    {
        parent::__construct($roles);

        $this->setUser($user);
        $this->socialProvider = $socialProvider;
        $this->securityKey = $securityKey;
        $this->credentials = $credentials;

        count($roles) > 0 and $this->setAuthenticated(true);
    }

    public function getCredentials(): Credentials
    {
        return $this->credentials;
    }

    public function getSecurityKey(): SecurityKey
    {
        return $this->securityKey;
    }

    public function getSocialProvider(): SocialProvider
    {
        return $this->socialProvider;
    }
}