<?php

namespace App\Security;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\User;

class GithubUserProvider
{
    private $githubClient;
    private $githubId;
    private $httpClient;

    public function __construct($githubClient, $githubId, HttpClientInterface $httpClient)
    {
        $this->githubClient = $githubClient;
        $this->githubId = $githubId;
        $this->httpClient = $httpClient;
    }

    public function loadUserFromGithub(string $code)
    {
        // 2. Users are redirected back to your site by GitHub
        $url = sprintf("https://github.com/login/oauth/access_token?client_id=%s&client_secret=%s&code=%s",
            $this->githubClient, $this->githubId, $code);

        $response = $this->httpClient->request('POST', $url, [
          'headers' => [
            'Accept' => "application/json"
          ]
        ]);


      	// access_token
        $token = $response->toArray()['access_token'];

      	// 3. Use the access token to access the API
        // don't forget to make a space after 'token'.
        $response = $this->httpClient->request('GET', 'https://api.github.com/user', [
          'headers' => [
            'Authorization' => 'token ' . $token
          ]
        ]);

        $githubData = $response->toArray();

      	return new User($githubData);
    }
}
