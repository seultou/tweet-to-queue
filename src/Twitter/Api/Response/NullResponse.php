<?php

namespace App\Twitter\Api\Response;

final class NullResponse implements Response
{
    public function data(): array
    {
        return [];
    }

    public function id(): int
    {
        return 0;
    }

    public function render(): array
    {
        return [];
    }
}