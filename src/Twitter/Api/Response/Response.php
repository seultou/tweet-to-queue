<?php

namespace App\Twitter\Api\Response;

interface Response
{
    public function id(): int;
    public function render(): array;
}