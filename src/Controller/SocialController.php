<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialController extends AbstractController
{
  protected $githubClient;

  public function __construct($githubClient)
  {
    $this->githubClient = $githubClient;
  }

  /**
   * @Route("/connect/github", name="connect_github")
   */
  public function connectGithub(UrlGeneratorInterface $generator)
  {
    // 1. Request a user's GitHub identity
    $url = $generator->generate('homepage', [], UrlGeneratorInterface::ABSOLUTE_URL);
    return new RedirectResponse("https://github.com/login/oauth/authorize?client_id=$this->githubClient&redirect_uri=".$url);
  }
}

