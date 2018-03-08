<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Application\Values;

use StephBug\SecurityModel\Application\Exception\Assert\Secure;
use StephBug\SecurityModel\Application\Values\Contract\Credentials;
use StephBug\SecurityModel\Application\Values\Contract\SecurityValue;

class SocialCredentials implements Credentials
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $refreshToken;

    private function __construct(string $accessToken = null, string $refreshToken = null)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }

    public static function fromString($accessToken = null, $refreshToken = null)
    {
        if ($accessToken) {
            Secure::string($accessToken);
            Secure::notEmpty($accessToken);
        }

        if ($refreshToken) {
            Secure::string($refreshToken);
            Secure::notEmpty($refreshToken);
        }

        return new self($accessToken, $refreshToken);
    }

    public function credentials(): ?string
    {
        return $this->accessToken;
    }

    public function refreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function sameValueAs(SecurityValue $aValue): bool
    {
        return $aValue instanceof $this
            && $this->accessToken === $aValue->credentials()
            && $this->refreshToken === $aValue->refreshToken();
    }
}