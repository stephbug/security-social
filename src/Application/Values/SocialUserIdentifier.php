<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Application\Values;

use StephBug\SecurityModel\Application\Values\Contract\SecurityIdentifier;
use StephBug\SecurityModel\Application\Values\Contract\SecurityValue;
use StephBug\SecurityModel\Application\Values\Contract\UserToken;

class SocialUserIdentifier implements SecurityIdentifier, UserToken
{
    /**
     * @var SocialUserId
     */
    private $userId;

    /**
     * @var SocialProvider
     */
    private $socialProvider;

    private function __construct(SocialUserId $userId, SocialProvider $socialProvider)
    {
        $this->userId = $userId;
        $this->socialProvider = $socialProvider;
    }

    public static function fromValues($userId, $provider): self
    {
        return new self(
            SocialUserId::fromString($userId),
            SocialProviderAllowed::fromString($provider)
        );
    }

    public function getUserId(): SocialUserId
    {
        return $this->userId;
    }

    public function getProvider(): SocialProvider
    {
        return $this->socialProvider;
    }

    public function identify(): array
    {
        return [$this->userId->identify(), $this->socialProvider->getName()];
    }

    public function sameValueAs(SecurityValue $aValue): bool
    {
        return $aValue instanceof $this
            && $this->userId->sameValueAs($aValue->getUserId())
            && $this->socialProvider->sameValueAs($aValue->getProvider());
    }
}