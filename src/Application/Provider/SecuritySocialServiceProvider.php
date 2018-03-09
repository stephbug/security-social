<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Application\Provider;

use Illuminate\Support\ServiceProvider;

class SecuritySocialServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../../database');
    }
}