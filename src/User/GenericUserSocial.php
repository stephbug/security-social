<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\User;

use Illuminate\Support\Collection;
use StephBug\SecurityModel\Application\Values\Contract\EmailAddress as EmailContract;
use StephBug\SecurityModel\Application\Values\Contract\SecurityIdentifier;
use StephBug\SecurityModel\Application\Values\Contract\UniqueIdentifier;
use StephBug\SecurityModel\Application\Values\Contract\UserToken;
use StephBug\SecuritySocial\Application\Values\SocialId;
use StephBug\SecuritySocial\Application\Values\SocialProvider;
use StephBug\SecuritySocial\Application\Values\SocialProviderAllowed;
use StephBug\SecuritySocial\Application\Values\SocialUserEmail;
use StephBug\SecuritySocial\Application\Values\SocialUserId;
use StephBug\SecuritySocial\Application\Values\SocialUserIdentifier;

class GenericUserSocial implements UserSocial, UserToken
{
    /**
     * @var array
     */
    private $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getIdentifier(): SecurityIdentifier
    {
        return SocialUserIdentifier::fromValues(
            $this->getSocialUserId()->identify(),
            $this->getSocialProvider()->getName()
        );
    }

    public function getId(): UniqueIdentifier
    {
        return SocialId::fromString($this->attributes['social_id']);
    }

    public function getEmail(): EmailContract
    {
        return SocialUserEmail::fromString($this->attributes['social_user_email']);
    }

    public function getRoles(): Collection
    {
        return new Collection($this->attributes['roles'] ?? []);
    }

    public function getSocialUserId(): SocialUserId
    {
        return SocialUserId::fromString($this->attributes['social_user_id']);
    }

    public function getSocialProvider(): SocialProvider
    {
        return SocialProviderAllowed::fromString($this->attributes['social_user_provider']);
    }

    public function getInformation(): array
    {
        return $this->attributes['information'];
    }
}