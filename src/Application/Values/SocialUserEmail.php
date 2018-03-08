<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Application\Values;

use StephBug\SecurityModel\Application\Exception\Assert\Secure;
use StephBug\SecurityModel\Application\Values\Contract\EmailAddress;
use StephBug\SecurityModel\Application\Values\Contract\SecurityValue;

class SocialUserEmail implements EmailAddress
{
    /**
     * @var string
     */
    private $email;

    public static function fromString($email): self
    {
        $message = 'Social Email address is not valid';

        Secure::notNull($email, $message);
        Secure::string($email, $message);
        Secure::notEmpty($email, $message);
        Secure::email($email, $message);

        return new self($email);
    }

    private function __construct(string $email)
    {
        $this->email = $email;
    }

    public function identify(): string
    {
        return $this->email;
    }

    public function sameValueAs(SecurityValue $aValue): bool
    {
        return $aValue instanceof $this && $this->email === $aValue->identify();
    }
}