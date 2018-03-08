<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\User;

use StephBug\SecurityModel\User\UserSecurity;
use StephBug\SecuritySocial\Application\Values\SocialProvider;
use StephBug\SecuritySocial\Application\Values\SocialUserId;

interface UserSocial extends UserSecurity
{
    public function getSocialUserId(): SocialUserId;

    public function getSocialProvider(): SocialProvider;
}