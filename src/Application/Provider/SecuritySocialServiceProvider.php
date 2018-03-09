<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Application\Provider;

use Illuminate\Support\ServiceProvider;

class SecuritySocialServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../../database');

        $this->publishes(
            [$this->getConfigPath() => config_path('security_socials.php')],
            'config'
        );
    }

    public function register(): void
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'security_socials');
    }

    protected function getConfigPath(): string
    {
        return __DIR__ . '/../../../config/security_socials.php';
    }
}