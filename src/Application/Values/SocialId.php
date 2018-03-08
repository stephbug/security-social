<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Application\Values;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use StephBug\SecurityModel\Application\Values\UniqueId;

class SocialId extends UniqueId
{
    private function __construct(UuidInterface $uniqueId)
    {
        $this->uniqueId = $uniqueId;
    }

    public static function fromString($uid): self
    {
        self::validate($uid);

        return new self($uid);
    }

    public static function nextIdentity(): self
    {
        return new self(Uuid::uuid4());
    }
}