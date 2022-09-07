<?php

namespace App\Twitter\Api\Response;

final class TwitterAccount implements Response
{
    private array $info;

    public function __construct(array $info)
    {
        $this->info = $info;
    }

    public function data(): array
    {
        return $this->info['data'];
    }

    public function id(): int
    {
        return $this->data()['id'];
    }

    public function username(): string
    {
        return $this->data()['username'];
    }

    public function name(): string
    {
        return $this->data()['name'];
    }

    public function render(): array
    {
        return [
            'id' => $this->id(),
            'username' => $this->username(),
            'name' => $this->name(),
        ];
    }
}