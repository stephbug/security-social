<?php

declare(strict_types=1);

namespace StephBug\SecuritySocial\Application\Http\Firewall;

use Illuminate\Http\Request;
use StephBug\SecurityModel\Application\Values\EmptyCredentials;
use StephBug\SecurityModel\Application\Values\SecurityKey;
use StephBug\SecurityModel\Guard\Authentication\SimplePreAuthenticator;
use StephBug\SecurityModel\Guard\Authentication\Token\Tokenable;
use StephBug\SecurityModel\User\Exception\UserNotFound;
use StephBug\SecurityModel\User\UserProvider;
use StephBug\SecuritySocial\Authentication\Token\SocialToken;
use StephBug\SecuritySocial\SocialServiceManager;
use StephBug\SecuritySocial\User\UserSocial;

class SocialAuthenticator implements SimplePreAuthenticator
{
    /**
     * @var SocialServiceManager
     */
    private $serviceManager;

    public function __construct(SocialServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function authenticateToken(Tokenable $token, UserProvider $userProvider, SecurityKey $securityKey): Tokenable
    {
        $tokenUser = $token->getUser();
        $socialProvider = $token->getSocialProvider();

        if ($token->isAuthenticated() && $tokenUser instanceof UserSocial) {
            return $token;
        }

        try {
            $user = $userProvider->requireByIdentifier($tokenUser->getIdentifier());

            return new SocialToken(
                $user,
                $socialProvider,
                $securityKey,
                $user->getSocialTokens,
                $user->getRoles()
            );
        } catch (UserNotFound $userNotFound) {
            return $token;
        }
    }

    public function supportsToken(Tokenable $token, SecurityKey $securityKey): bool
    {
        return $token instanceof SocialToken && $securityKey->sameValueAs($token->getSecurityKey());
    }

    public function createToken(Request $request, SecurityKey $securityKey): Tokenable
    {
        $userSocial = $this->serviceManager->socialUser($request);

        return new SocialToken(
            $userSocial,
            $this->serviceManager->socialProvider($request),
            $securityKey,
            new EmptyCredentials()
        );
    }
}