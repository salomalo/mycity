<?php

namespace common\extensions\ApiVk\objects;

/**
 * Class ImageUploadServerResponse
 * @package common\extensions\ApiVk\objects
 */
abstract class ImageUploadServerResponse extends SuccessResponse
{
    /** @var  $upload_url string */
    public $upload_url;
    /** @var  $album_id integer */
    public $album_id;
    /** @var  $user_id integer */
    public $user_id;
}