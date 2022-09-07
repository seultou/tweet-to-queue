<?php

namespace App\Twitter\Api\Request;

final class Tweet extends BaseRequest
{
    protected const FIELDS = [
        'since_id' => null,
        'expansions' => 'attachments.media_keys,attachments.poll_ids,author_id,entities.mentions.username,geo.place_id,in_reply_to_user_id,referenced_tweets.id,referenced_tweets.id.author_id',
        'tweet.fields' => 'attachments,context_annotations,created_at,id,author_id,conversation_id,entities,in_reply_to_user_id,geo,lang,possibly_sensitive,public_metrics,referenced_tweets,reply_settings,source,text,withheld',
        'media.fields' => 'alt_text,url,width,type,public_metrics,duration_ms,media_key,height,preview_image_url',
        'poll.fields' => 'duration_minutes,end_datetime,options,voting_status,id',
        'place.fields' => 'name,id,place_type,geo,contained_within,country_code,country,full_name',
        'user.fields' => 'created_at,entities,description,location,name,pinned_tweet_id,id,profile_image_url,protected,public_metrics,url,username,verified,withheld',
    ];

    public static function byUserId(string $id, array $params = [])
    {
        return new self('users/' . $id . '/tweets', $params);
    }
}