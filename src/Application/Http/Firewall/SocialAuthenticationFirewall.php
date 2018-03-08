<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Application\Http\Firewall;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Laravel\Socialite\Two\InvalidStateException;
use StephBug\SecurityModel\Application\Exception\AuthenticationException;
use StephBug\SecurityModel\Application\Http\Entrypoint\Entrypoint;
use StephBug\SecurityModel\Application\Http\Firewall\AuthenticationFirewall;
use StephBug\SecurityModel\Application\Values\SecurityKey;
use StephBug\SecurityModel\Guard\Authentication\SimplePreAuthenticator;
use StephBug\SecurityModel\Guard\Guard;
use StephBug\SecuritySocial\Application\Exceptions\InvalidSocialProvider;
use StephBug\SecuritySocial\Application\Http\Request\SocialAuthenticationRequest;
use Symfony\Component\HttpFoundation\Response;

class SocialAuthenticationFirewall extends AuthenticationFirewall
{
    /**
     * @var Guard
     */
    private $guard;

    /**
     * @var SimplePreAuthenticator
     */
    private $socialAuthenticator;

    /**
     * @var SecurityKey
     */
    private $securityKey;

    /**
     * @var Entrypoint
     */
    private $defaultLogin;

    /**
     * @var SocialAuthenticationRequest
     */
    private $authenticationRequest;

    /**
     * @var Entrypoint
     */
    private $redirectToSocialProvider;

    public function __construct(Guard $guard,
                                SimplePreAuthenticator $socialAuthenticator,
                                SecurityKey $securityKey,
                                Entrypoint $defaultLogin,
                                Entrypoint $redirectToSocialProvider,
                                SocialAuthenticationRequest $authenticationRequest)
    {
        $this->guard = $guard;
        $this->socialAuthenticator = $socialAuthenticator;
        $this->securityKey = $securityKey;
        $this->defaultLogin = $defaultLogin;
        $this->authenticationRequest = $authenticationRequest;
        $this->redirectToSocialProvider = $redirectToSocialProvider;
    }

    protected function processAuthentication(Request $request): ?Response
    {
        try {
            if ($this->authenticationRequest->isRedirect($request)) {
                $token = $this->socialAuthenticator->createToken($request, $this->securityKey);

                $this->guard->put($token);

                return null;
            }

            $this->authenticationRequest->extract($request);

            return $this->redirectToSocialProvider->startAuthentication($request);
        } catch (InvalidSocialProvider | InvalidStateException | ClientException $exception) {
            if (!$exception instanceof InvalidSocialProvider) {
                $exception = new AuthenticationException('Authentication failed', 0, $exception);
            }

            $this->guard->forget();

            return $this->defaultLogin->startAuthentication($request, $exception);
        }
    }

    protected function requireAuthentication(Request $request): bool
    {
        return $this->guard->isStorageEmpty() && $this->authenticationRequest->matches($request);
    }
}