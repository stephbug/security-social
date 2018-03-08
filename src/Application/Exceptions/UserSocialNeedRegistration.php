<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Application\Exceptions;

use StephBug\SecurityModel\User\Exception\UserNotFound;
use StephBug\SecuritySocial\Authentication\Token\SocialToken;

class UserSocialNeedRegistration extends UserNotFound
{
    /**
     * @var SocialToken
     */
    private $socialToken;

    public static function withSocialToken(SocialToken $token): self
    {
        $self = new static('Social user need registration');
        $self->setSocialToken($token);

        return $self;
    }

    public function setSocialToken(SocialToken $token): void
    {
        $this->socialToken = $token;
    }

    public function getSocialToken(): SocialToken
    {
        return $this->socialToken;
    }
}