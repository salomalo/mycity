<?php

namespace common\extensions\ApiVk\objects;

/**
 * Class ErrorResponse
 * @package common\extensions\ApiVk\objects
 */
abstract class ErrorResponse
{
    /** @var $error_code integer */
    public $error_code;
    /** @var $error_msg string */
    public $error_msg;
}