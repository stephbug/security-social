<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Application\Values;

use StephBug\SecurityModel\Application\Exception\Assert\Secure;
use StephBug\SecurityModel\Application\Values\Contract\SecurityValue;

class SocialUserId implements SecurityValue
{
    /**
     * @var string
     */
    private $uid;

    private function __construct(string $uid)
    {
        $this->uid = $uid;
    }

    public static function fromString($uid): self
    {
        Secure::notNull($uid);
        Secure::string($uid);
        Secure::notEmpty($uid);

        return new self($uid);
    }

    public function sameValueAs(SecurityValue $aValue): bool
    {
        return $aValue instanceof $this && $this->uid === $aValue->value();
    }

    public function value(): string
    {
        return $this->uid;
    }
}