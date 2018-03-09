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
    private $secretToken;

    private function __construct(string $accessToken = null, string $secretToken = null)
    {
        $this->accessToken = $accessToken;
        $this->secretToken = $secretToken;
    }

    public static function fromString($accessToken = null, $secretToken = null)
    {
        if ($accessToken) {
            Secure::string($accessToken);
            Secure::notEmpty($accessToken);
        }

        if ($secretToken) {
            Secure::string($secretToken);
            Secure::notEmpty($secretToken);
        }

        return new self($accessToken, $secretToken);
    }

    public function credentials(): ?string
    {
        return $this->accessToken;
    }

    public function secretToken(): ?string
    {
        return $this->secretToken;
    }

    public function sameValueAs(SecurityValue $aValue): bool
    {
        return $aValue instanceof $this
            && $this->accessToken === $aValue->credentials()
            && $this->secretToken === $aValue->secretToken();
    }
}