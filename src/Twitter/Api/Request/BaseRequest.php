<?php

namespace App\Twitter\Api\Request;

use App\Twitter\Api\Response;
use App\Twitter\Twitter;

abstract class BaseRequest implements Request
{
    protected const FIELDS = [];
    protected string $uri;
    protected array $params;

    public function __construct(string $uri, array $params = [])
    {
        $this->uri = $uri;
        $this->params = $params;
    }

    public function uri(): string
    {
        return $this->uri
            . '?'
            . http_build_query(array_replace(static::FIELDS, $this->params));
    }
}