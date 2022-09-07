<?php

namespace App\Twitter\Tweet;

use function \Safe\curl_init;
use function \Safe\curl_setopt;
use function \Safe\curl_exec;

final class Tweet
{
    private array $info;

    public function __construct(array $info)
    {
        $this->info = $info;
    }

    public function id(): int
    {
        return $this->info['id'];
    }

    public function createdAt(): string
    {
        return $this->info['created_at'];
    }

    public function authorId(): int
    {
        return $this->info['author_id'];
    }

    public function authorUsername(): string
    {
        return $this->info['author_username'];
    }

    public function text(): string
    {
        return $this->info['text'];
    }

    public function media(): MediaCollection
    {
        $keys = $this->info['attachments']['media_keys'] ?? [];
        if (empty($keys)) {
            return new MediaCollection();
        }

        return $this->fetchMediaContents($keys);
    }

    private function fetchMediaContents(array $keys): MediaCollection
    {
        $contents = new MediaCollection();
        for ($i = 0; $i < count($keys) - 1; ++$i) {
            $key = explode('_', $keys[$i])[1] ?? '';
            $url = '';
            foreach ($this->info['raw_includes']['media'] as $rawMedia) {
                if ($rawMedia['media_key'] === $key) {
                    break;
                }
                $url = $rawMedia['preview_image_url'] ?? $rawMedia['url'] ?? '';
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.1 Safari/537.11');
            $rawMedia['contents'] = curl_exec($ch);
            // $rescode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $contents->set($keys[$i], new Media($rawMedia));
        }

        return $contents;
    }
}