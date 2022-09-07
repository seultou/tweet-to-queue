<?php

namespace App\Twitter\Api\Response;

final class ResponseFactory
{
    private function __construct()
    {
    }

    public static function create(array $info, string $fqcn): Response
    {
        if ($fqcn === \App\Twitter\Api\Request\TwitterAccount::class) {
            return new TwitterAccount($info);
        }
        if ($fqcn === \App\Twitter\Api\Request\Tweet::class) {
            return new Tweet($info);
        }

        return new NullResponse();
    }
}