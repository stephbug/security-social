<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Application\Http\Response;

use Illuminate\Http\Request;
use StephBug\SecurityModel\Application\Exception\AuthenticationException;
use StephBug\SecurityModel\Application\Http\Entrypoint\Entrypoint;
use StephBug\SecuritySocial\SocialServiceManager;
use Symfony\Component\HttpFoundation\Response;

class RedirectToSocialProvider implements Entrypoint
{
    /**
     * @var SocialServiceManager
     */
    private $serviceManager;

    public function __construct(SocialServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function startAuthentication(Request $request, AuthenticationException $exception = null): Response
    {
        return $this->serviceManager->socialiteInstance($request)->redirect();
    }
}