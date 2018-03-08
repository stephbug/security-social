<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Application\Exceptions;

use StephBug\SecurityModel\Application\Exception\AuthenticationException;

class InvalidSocialProvider extends AuthenticationException
{
}