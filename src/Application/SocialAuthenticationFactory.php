<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Application;

use Illuminate\Contracts\Foundation\Application;
use Laravel\Socialite\Contracts\Factory;
use StephBug\Firewall\Factory\Contracts\AuthenticationServiceFactory;
use StephBug\Firewall\Factory\Payload\PayloadFactory;
use StephBug\Firewall\Factory\Payload\PayloadService;
use StephBug\SecurityModel\Application\Http\Entrypoint\Entrypoint;
use StephBug\SecurityModel\Guard\Guard;
use StephBug\SecuritySocial\Application\Http\Firewall\SocialAuthenticationFirewall;
use StephBug\SecuritySocial\Application\Http\Firewall\SocialAuthenticator;
use StephBug\SecuritySocial\Application\Http\Request\SocialAuthenticationRequest;
use StephBug\SecuritySocial\Application\Http\Response\RedirectToSocialProvider;
use StephBug\SecuritySocial\Authentication\Provider\SocialAuthenticationProvider;
use StephBug\SecuritySocial\SocialServiceManager;
use StephBug\SecuritySocial\User\UserTransformer;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

class SocialAuthenticationFactory implements AuthenticationServiceFactory
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function create(PayloadService $payload): PayloadFactory
    {
        $authenticator = $this->registerAuthenticator($payload);

        return (new PayloadFactory())
            ->setFirewall($this->registerFirewall($payload, $authenticator))
            ->setProvider($this->registerProvider($payload, $authenticator));

    }

    protected function registerFirewall(PayloadService $payload, string $authenticatorId): string
    {
        $id = 'firewall.social_authentication_firewall.' . $payload->securityKey->value();

        $this->app->bind($id, function (Application $app) use ($authenticatorId, $payload) {
            return new SocialAuthenticationFirewall(
                $app->make(Guard::class),
                $app->make($authenticatorId),
                $payload->securityKey,
                $app->make($payload->entrypoint),
                $this->getRedirectToProvider(),
                $this->getAuthenticationRequest()
            );
        });

        return $id;
    }

    protected function registerProvider(PayloadService $payload, string $authenticatorId): string
    {
        $id = ' firewall.simple-social_authentication_provider.' . $payload->securityKey->value();

        $this->app->bind($id, function (Application $app) use ($authenticatorId, $payload) {
            return new SocialAuthenticationProvider(
                $app->make($authenticatorId),
                $app->make($payload->userProviderId),
                $payload->securityKey
            );
        });

        return $id;
    }

    protected function registerAuthenticator(PayloadService $payload): string
    {
        $id = 'firewall.social_authenticator.' . $payload->securityKey->value();

        $this->app->bind($id, function () {
            return new SocialAuthenticator($this->getSocialServiceManager());
        });

        return $id;
    }

    protected function getSocialServiceManager(): SocialServiceManager
    {
        return new SocialServiceManager(
            $this->app->make(Factory::class),
            $this->getAuthenticationRequest(),
            new UserTransformer()
        );
    }

    protected function getAuthenticationRequest(): SocialAuthenticationRequest
    {
        return new SocialAuthenticationRequest('social_provider_name', 'social.oauth.login');
    }

    protected function getRedirectToProvider(): Entrypoint
    {
        return new RedirectToSocialProvider($this->getSocialServiceManager());
    }

    public function registerEntrypoint(): ?string
    {
        return null;
    }

    public function position(): string
    {
        return 'http';
    }

    public function matcher(): ?RequestMatcherInterface
    {
        return null;
    }

    public function userProviderKey(): ?string
    {
        return null;
    }
}