<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\{UserInterface, UserProviderInterface};
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use App\Repository\User\UserRepository;

/**
 * authenticator for REST API by token
 * 
 * @author Sebastian Chmiel <s.chmiel2@confronter.pl>
 */
final class TokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var UserRepository  
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * check if request is supporting for atuhenticate
     *
     * @param Request $request
     * 
     * @return bool
     */
    public function supports(Request $request)
    {
        return $request->headers->has('X-AUTH-TOKEN') && $request->headers->has('X-AUTH-USERNAME');
    }

    /**
     * get credentials
     *
     * @param Request $request
     * 
     * @return array
     */
    public function getCredentials(Request $request)
    {
        return [
            'username' => $request->headers->get('X-AUTH-USERNAME'),
            'apiToken' => $request->headers->get('X-AUTH-TOKEN'),
        ];
    }

    /**
     * get user
     *
     * @param array $credentials
     * @param UserProviderInterface $userProvider
     * 
     * @return UserInterface|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (empty($credentials)) {
            return null;
        }

        // if a User is returned, checkCredentials() is called
        return $this->userRepository->findOneBy([
            'username' => $credentials['username'] ?? null
        ]);
    }

    /**
     * check credentials
     *
     * @param array $credentials
     * @param UserInterface $user
     * 
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!isset($credentials['apiToken'])) {
            return false;
        }

        return $user->getApiToken() === $credentials['apiToken'];
    }

    /**
     * on success
     *
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * 
     * @return null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return null;
    }

    /**
     * on failure
     *
     * @param Request $request
     * @param TokenInterface $token
     * 
     * @return JsonResposne
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * called when authentication is needed, but it's not sent
     * 
     * @param Request $request
     * @param AuthenticationException $authException (optional)
     * 
     * @return JsonResponse
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * check supports remember me
     *
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }
}
