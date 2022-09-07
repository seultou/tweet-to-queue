<?php

namespace App\Twitter\Api\Response;


final class Tweet implements Response
{
    private array $info;

    public function __construct(array $info)
    {
        $this->info = $info;
    }

    public function info(): array
    {
        return $this->info ?? [];
    }

    public function includes(): array
    {
        return $this->info['includes'] ?? [];
    }

    public function data(): array
    {
        return $this->info['data'] ?? [];
    }

    public function id(): int
    {
        return $this->data()[0]['id'];
    }

    public function render(): array
    {
        return [
            'id' => $this->id(),
            'data' => $this->data(),
        ];
    }

//    public function items(): TweetCollection
//    {
//
//    }

}