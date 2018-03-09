<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial;

use Illuminate\Contracts\Foundation\Application;
use StephBug\SecuritySocial\Application\Values\SocialProvider;
use StephBug\SecuritySocial\User\UserSocialTransformer;

class UserTransformerResolver
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function resolve(SocialProvider $provider): UserSocialTransformer
    {
        $transformer =
            $this->fromConfig('providers.' . $provider->getName() . '.transformer')
            ?? $this->fromConfig('transformer');

        return $this->app->make($transformer);
    }

    protected function fromConfig(string $key, $default = null): string
    {
        return $this->app->make('config')->get('security_socials.' . $key, $default);
    }
}