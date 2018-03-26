<?php

namespace common\extensions\ApiVk\objects;

/**
 * Class PhotoResponse
 * @package common\extensions\ApiVk\objects
 */
abstract class PhotoResponse extends SuccessResponse
{
    /** @var  $id integer */
    public $id;
    /** @var  $album_id integer */
    public $album_id;
    /** @var  $owner_id integer */
    public $owner_id;
    /** @var  $photo_75 string */
    public $photo_75;
    /** @var  $photo_130 string */
    public $photo_130;
    /** @var  $photo_604 string */
    public $photo_604;
    /** @var  $width integer */
    public $width;
    /** @var  $height integer */
    public $height;
    /** @var  $text string */
    public $text;
    /** @var  $date integer */
    public $date;
}