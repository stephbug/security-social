<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\User;

use Laravel\Socialite\Contracts\User as UserSocialite;
use StephBug\SecuritySocial\Application\Values\SocialId;
use StephBug\SecuritySocial\Application\Values\SocialProvider;

class UserTransformer implements UserSocialTransformer
{
    public function transform(UserSocialite $socialiteUser, SocialProvider $socialProvider): UserSocial
    {
        return new GenericUserSocial([
            'social_id' => SocialId::nextIdentity()->identify(),
            'social_user_id' => (string)$socialiteUser->getId(),
            'social_provider_name' => $socialProvider->getName(),
            'social_user_email' => $socialiteUser->getEmail(),
            'access_token' => $socialiteUser->token ?? null,
            'secret_token' => $socialiteUser->refreshToken ?? null,
            'information' => $socialiteUser->getRaw()
        ]);
    }
}