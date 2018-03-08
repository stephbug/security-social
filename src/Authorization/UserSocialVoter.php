<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Authorization;

use Illuminate\Database\Eloquent\Model;
use StephBug\SecurityModel\Guard\Authentication\Token\Tokenable;
use StephBug\SecurityModel\Guard\Authorization\Voter\Voter;
use StephBug\SecuritySocial\User\UserSocial;

class UserSocialVoter extends Voter
{
    const REGISTRATION_REQUIRED = 'user_social_need_registration';

    protected function supports(string $attribute, $subject): bool
    {
        return static::REGISTRATION_REQUIRED === $attribute;
    }

    protected function voteOn(string $attribute, $subject, Tokenable $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserSocial) {
            return false;
        }

        if ($user instanceof Model && $user->exists) {
            return false;
        }

        // should be tested again a generic social user

        return true;
    }
}