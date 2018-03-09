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
     * @var UserTransformerResolver
     */
    private $resolver;

    public function __construct(Factory $socialite,
                                SocialAuthenticationRequest $authenticationRequest,
                                UserTransformerResolver $resolver)
    {
        $this->socialite = $socialite;
        $this->authenticationRequest = $authenticationRequest;
        $this->resolver = $resolver;
    }

    public function socialUser(Request $request): UserSocial
    {
        if (!$this->authenticationRequest->isRedirect($request)) {
            throw BadCredentials::invalid();
        }

        $name = $this->socialProvider($request);

        return $this->resolver->resolve($name)->transform($this->socialiteUser($request), $name);
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