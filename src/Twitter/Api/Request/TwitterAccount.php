<?php

namespace App\Twitter\Api\Request;

final class TwitterAccount extends BaseRequest
{
    protected const FIELDS = [
        'user.fields' => 'entities,created_at,name,location,profile_image_url,id,description,pinned_tweet_id,protected,public_metrics,url,verified,username,withheld'
    ];

    public static function byUsername(string $username): self
    {
        return new self('users/by/username/' . $username);
    }
}