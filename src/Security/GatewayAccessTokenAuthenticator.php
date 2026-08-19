<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class GatewayAccessTokenAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    public const string AUTHORIZATION_BEARER_KEY = 'Authorization';
    public const string AUTHORIZATION_BEARER_KEYWORD = 'Bearer';

    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        return new JsonResponse(['message' => 'Authentication header is required'], Response::HTTP_UNAUTHORIZED);
    }

    public function supports(Request $request): ?bool
    {
        $authorizationHeader = $request->headers->get(self::AUTHORIZATION_BEARER_KEY);

        if (!$authorizationHeader) {
            return false;
        }

        return str_starts_with(
            strtolower($authorizationHeader),
            \sprintf('%s ', strtolower(self::AUTHORIZATION_BEARER_KEYWORD))
        );
    }

    public function authenticate(Request $request): Passport
    {
        if (null === $authorizationHeader = $request->headers->get(self::AUTHORIZATION_BEARER_KEY)) {
            throw new BadCredentialsException('Authorization header is required.');
        }

        $token = substr($authorizationHeader, \strlen(self::AUTHORIZATION_BEARER_KEYWORD) + 1);

        $user = $this->userRepository->find(1);

        if (null === $user) {
            throw new BadCredentialsException('Invalid token provided.');
        }

        return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier(), fn () => $user));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_UNAUTHORIZED);
    }
}
