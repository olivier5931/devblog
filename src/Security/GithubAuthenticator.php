<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\User;

/**
 * @property EntityManagerInterface em
 */
class GithubAuthenticator extends AbstractGuardAuthenticator {

  private $provider;

  public function __construct(GithubUserProvider $provider, EntityManagerInterface $em) {
    $this->provider = $provider;
    $this->em = $em;
  }

  public function supports(Request $request) {
    // need to call authentication process ? (boolean response)
    return $request->query->get('code');
  }

  public function getCredentials(Request $request) {
    return [
        'code' => $request->query->get('code')
    ];
  }

  public function getUser($credentials, UserProviderInterface $userProvider) {
    // get user with client_id and client_secret
    $githubUser = $this->provider->loadUserFromGithub($credentials['code']);

    $username = $githubUser->getUsername();

    $user = $this->em->getRepository('App:User')->findOneBy(['username' => $username]);

    if (!$user) {

      $user = new User();
      $user->setUsername($githubUser->getUsername());
      $user->setLanguage('fr');
      $user->setRoles(['ROLE_GITHUB']);
      $user->setStatus('enabled');

      $this->em->persist($user);
      $this->em->flush();
    }

    return $user;
  }

  public function checkCredentials($credentials, UserInterface $user) {
    // no password, then we always return true
    return true;
  }

  public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
    return new Response("Authentication Failed :(", 401);
  }

  public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
    // todo
  }

  public function start(Request $request, AuthenticationException $authException = null) {
    return new RedirectResponse('/accueil');
  }

  public function supportsRememberMe() {
    return null;
  }

}
