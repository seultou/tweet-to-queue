<?php

namespace App\Twitter\Tweet;

final class Media
{
    private array $info;

    public function __construct(array $info)
    {
        $this->info = $info;
    }

    public function height(): int
    {
        return (int) $this->info['height'] ?? 0;
    }

    public function width(): int
    {
        return (int) $this->info['width'] ?? 0;
    }

    public function url(): string
    {
        return $this->info['preview_image_url'] ?? $this->info['url'] ?? '';
    }

    public function type(): string
    {
        return $this->info['type'];
    }

    public function mediaKey(): string
    {
        return $this->info['media_key'];
    }

    public function contents(): string
    {
        return $this->info['contents'];
    }

    public function render(): array
    {
        return [
            'height' => $this->height(),
            'width' => $this->width(),
            'url' => $this->url(),
            'type' => $this->type(),
            'media_key' => $this->mediaKey(),
            'base64_encode' => base64_encode($this->contents()),
        ];
    }

}