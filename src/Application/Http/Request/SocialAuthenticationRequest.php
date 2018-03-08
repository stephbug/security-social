<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Application\Http\Request;

use Illuminate\Http\Request as IlluminateRequest;
use StephBug\SecurityModel\Application\Exception\Assert\SecurityValueFailed;
use StephBug\SecurityModel\Application\Http\Request\AuthenticationRequest;
use StephBug\SecuritySocial\Application\Exceptions\InvalidSocialProvider;
use StephBug\SecuritySocial\Application\Values\SocialProvider;
use StephBug\SecuritySocial\Application\Values\SocialProviderAllowed;
use Symfony\Component\HttpFoundation\Request;

class SocialAuthenticationRequest implements AuthenticationRequest
{
    /**
     * @var string
     */
    private $providerName;

    /**
     * @var string
     */
    private $loginRouteName;

    public function __construct(string $providerName, string $loginRouteName)
    {
        $this->providerName = $providerName;
        $this->loginRouteName = $loginRouteName;
    }

    public function extract(IlluminateRequest $request): SocialProvider
    {
        try {
            return SocialProviderAllowed::fromString(
                $request->route()->parameter($this->providerName)
            );
        } catch (SecurityValueFailed $invalidProvider) {
            throw new InvalidSocialProvider('Invalid social provider', 0, $invalidProvider);
        }
    }

    public function matches(Request $request)
    {
        if ($this->isRedirect($request)) {
            return true;
        }

        return $this->isLogin($request);
    }

    public function isRedirect(IlluminateRequest $request): bool
    {
        return $this->isLogin($request)
            && $request->has('code')
            && $request->has('state');
    }

    public function isLogin(IlluminateRequest $request): bool
    {
        return $request->route()->getName() === $this->loginRouteName;
    }
}