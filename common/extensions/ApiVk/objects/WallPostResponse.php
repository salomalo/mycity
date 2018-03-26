<?php

namespace common\extensions\ApiVk\objects;

/**
 * Class WallPostResponse
 * @package common\extensions\ApiVk\objects
 */
abstract class WallPostResponse extends Response
{
    /** @var  $post_id integer */
    public $post_id;
}