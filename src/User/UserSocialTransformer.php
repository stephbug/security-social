<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\User;

use Laravel\Socialite\Contracts\User as UserSocialite;
use StephBug\SecuritySocial\Application\Values\SocialProvider;

interface UserSocialTransformer
{
    public function transform(UserSocialite $socialiteUser, SocialProvider $socialProvider): UserSocial;
}