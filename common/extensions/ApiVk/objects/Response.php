<?php

namespace common\extensions\ApiVk\objects;

/**
 * Class Response
 * @package common\extensions\ApiVk\objects
 */
abstract class Response
{
    /** @var $error ErrorResponse */
    public $error;
    /** @var $response SuccessResponse */
    public $response;
}