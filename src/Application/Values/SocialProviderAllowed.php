<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Application\Values;

use StephBug\SecurityModel\Application\Exception\Assert\Secure;
use StephBug\SecurityModel\Application\Values\Contract\SecurityValue;

class SocialProviderAllowed implements SocialProvider
{
    /**
     * @var string
     */
    private $name;

    const PROVIDERS = ['github']; // move to enum

    protected function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function fromString($name): self
    {
        $message = 'Invalid social provider';

        Secure::notNull($name, $message);
        Secure::string($name, $message);
        Secure::notEmpty($name, $message);

        $name = mb_strtolower($name);
        Secure::inArray($name, static::PROVIDERS, $message);

        return new self($name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function sameValueAs(SecurityValue $aValue): bool
    {
        return $aValue instanceof $this && $this->name === $aValue->getName();
    }
}