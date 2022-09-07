<?php

namespace spec\App\Twitter\Tweet;

use App\Twitter\Tweet\Media;
use PhpSpec\ObjectBehavior;

class MediaSpec extends ObjectBehavior
{
    private $stub = [
        'height' => 75,
        'width' => 100,
        'preview_image_url' => 'https://example.com/img.gif',
        'type' => 'animated_gif',
        'media_key' => '1_123',
        'contents' => 'fake_file_contents',
    ];

    function let()
    {
        $this->beConstructedWith($this->stub);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Media::class);
    }

    function it_returns_right_height()
    {
        $this->height()->shouldReturn($this->stub['height']);
    }

    function it_returns_right_width()
    {
        $this->width()->shouldReturn($this->stub['width']);
    }

    function it_returns_right_preview_image_url()
    {
        $this->url()->shouldReturn($this->stub['preview_image_url']);
    }

    function it_returns_right_url()
    {
        unset($this->stub['preview_image_url']);
        $this->stub['url'] = 'https://example.com/img.jpg';
        $this->beConstructedWith($this->stub);
        $this->url()->shouldReturn($this->stub['url']);
    }

    function it_returns_right_empty_url_when_missing()
    {
        unset($this->stub['preview_image_url']);
        $this->beConstructedWith($this->stub);
        $this->url()->shouldReturn('');
    }

    function it_returns_right_type()
    {
        $this->type()->shouldReturn($this->stub['type']);
    }

    function it_returns_right_media_key()
    {
        $this->mediaKey()->shouldReturn($this->stub['media_key']);
    }

    function it_returns_right_contents()
    {
        $this->contents()->shouldReturn($this->stub['contents']);
    }

    function it_returns_a_properly_formatted_array()
    {
        $this->render()->shouldReturn([
            'height' => $this->stub['height'],
            'width' => $this->stub['width'],
            'url' => (new Media($this->stub))->url(),
            'type' => $this->stub['type'],
            'media_key' => $this->stub['media_key'],
            'base64_encode' => base64_encode($this->stub['contents']),
        ]);
    }
}
