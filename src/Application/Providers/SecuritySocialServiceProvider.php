<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Application\Providers;

use Illuminate\Support\ServiceProvider;

class SecuritySocialServiceProvider extends ServiceProvider
{
    /**
     * @var
     */
    protected $defer = true;

    public function register(): void
    {

    }

    public function provides(): array
    {
        return [];
    }
}