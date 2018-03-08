<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Application\Values;

use StephBug\SecurityModel\Application\Values\Contract\SecurityValue;

interface SocialProvider extends SecurityValue
{
    public function getName(): string;
}