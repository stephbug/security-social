<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial;

use Illuminate\Http\Request;
use Laravel\Socialite\Contracts\Factory;
use Laravel\Socialite\Contracts\Provider as ProviderSocialite;
use Laravel\Socialite\Contracts\User as UserSocialite;
use StephBug\SecurityModel\User\Exception\BadCredentials;
use StephBug\SecuritySocial\Application\Http\Request\SocialAuthenticationRequest;
use StephBug\SecuritySocial\Application\Values\SocialProvider;
use StephBug\SecuritySocial\User\UserSocial;
use StephBug\SecuritySocial\User\UserSocialTransformer;

class SocialServiceManager
{
    /**
     * @var Factory
     */
    private $socialite;

    /**
     * @var SocialAuthenticationRequest
     */
    private $authenticationRequest;

    /**
     * @var UserSocialTransformer
     */
    private $transformer;

    public function __construct(Factory $socialite,
                                SocialAuthenticationRequest $authenticationRequest,
                                UserSocialTransformer $transformer)
    {
        $this->socialite = $socialite;
        $this->authenticationRequest = $authenticationRequest;
        $this->transformer = $transformer;
    }

    public function socialUser(Request $request): UserSocial
    {
        if (!$this->authenticationRequest->isRedirect($request)) {
            throw BadCredentials::invalid();
        }

        return $this->transformer->transform(
            $this->socialiteUser($request),
            $this->socialProvider($request)
        );
    }

    public function socialiteInstance(Request $request): ProviderSocialite
    {
        return $this->socialite->driver($this->socialProvider($request)->getName());
    }

    protected function socialiteUser(Request $request): UserSocialite
    {
        return $this->socialiteInstance($request)->user();
    }

    public function socialProvider(Request $request): SocialProvider
    {
        return $this->authenticationRequest->extract($request);
    }
}