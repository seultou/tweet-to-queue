<?php

namespace App\Twitter;

use App\Twitter\Api\Request\Request;
use App\Twitter\Api\Response;
use App\Twitter\Api\TwitterOAuth;

final class Twitter
{
    private string $apiVersion;
    private string $apiKey;
    private string $apiKeySecret;
    private string $apiBearerToken;

    public function __construct(
        string $twitterApiVersion,
        string $twitterApiKey,
        string $twitterApiKeySecret,
        string $twitterApiBearerToken
    ) {
        $this->apiVersion = $twitterApiVersion;
        $this->apiKey = $twitterApiKey;
        $this->apiKeySecret = $twitterApiKeySecret;
        $this->apiBearerToken = $twitterApiBearerToken;
    }

    public function connect(): TwitterOAuth
    {
        $connection = new TwitterOAuth($this->apiKey, $this->apiKeySecret, null, $this->apiBearerToken);
        $connection->setApiVersion($this->apiVersion);
        $connection->setDecodeJsonAsArray(true);

        return $connection;
    }

    public function request(Request $request): Response\Response
    {
        return Response\ResponseFactory::create($this->connect()->get($request->uri()), get_class($request));
    }
}