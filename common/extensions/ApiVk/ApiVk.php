<?php

namespace common\extensions\ApiVk;

use common\extensions\ApiVk\objects\ErrorResponse;
use common\extensions\ApiVk\objects\ImageUploadServerResponse;
use common\extensions\ApiVk\objects\PhotoResponse;
use common\extensions\ApiVk\objects\Response;
use common\extensions\ApiVk\objects\SuccessResponse;
use common\extensions\ApiVk\objects\WallPostResponse;
use yii\helpers\VarDumper;

/**
 * Class ApiVk
 * @package common\extensions\ApiVk
 */
class ApiVk
{
    /** @var UrlGenerator $generator */
    private $generator;

    public $errorCode;
    
    private $errors = [];

    /**
     * @param $url string
     * @return null|Response
     */
    private function doRequest($url)
    {
        sleep(1);
        $response = @file_get_contents($url);
        $json = null;
        if ($response) {
            $json = MyJsonClass::decodeJson($response);
        }
        return $json;
    }

    /**
     * @param $json Response
     * @param $msg string
     * @return null|SuccessResponse
     */
    private function checkResponse($json, $msg)
    {
        $return = null;
        if (!empty($json->error)) {
            $error = $json->error;
            /** @var $error ErrorResponse */
            echo PHP_EOL . $error->error_code . ': ' . $error->error_msg . PHP_EOL;
            $this->errors[] = $error;
            $this->errorCode = (int)$error->error_code;
        } elseif (!empty($json->response)) {
            $return = $json->response;
            /** @var $error SuccessResponse */
        } else {
            echo PHP_EOL, 'Unknown error when ', $msg;
        }
        return $return;
    }

    /**
     * ApiVk constructor.
     * @param $token string
     */
    public function __construct($token)
    {
        $this->generator = new UrlGenerator($token);
    }

    /**
     * @param string $message
     * @param null|integer $owner_id
     * @param null|array $attachments
     * @return null|int
     */
    public function wallPost($message, $owner_id = null, $attachments = null)
    {
        $message = urlencode(trim(strip_tags(html_entity_decode($message))));
        $url = $this->generator->getWallPost($message, $owner_id, $attachments);
        $json = $this->doRequest($url);
        /** @var WallPostResponse|null $return */
        $return = $this->checkResponse($json, 'wall.post');

        return isset($return->post_id) ? $return->post_id : null;
    }

    /**
     * @param null|integer $group_id
     * @return null|string
     */
    public function photosGetWallUploadServer($group_id = null)
    {
        $url = $this->generator->getPhotosGetWallUploadServer($group_id);
        $json = $this->doRequest($url);
        /** @var ImageUploadServerResponse|null $return */
        $return = $this->checkResponse($json, 'photos.GetWallUploadServer');

        return isset($return->upload_url) ? $return->upload_url : null;
    }

    /**
     * @param object $server
     * @param null|integer $user_id
     * @param null|integer $group_id
     * @return PhotoResponse|null
     */
    public function photosSaveWallPhoto($server, $user_id = null, $group_id = null)
    {
        if (isset($server->photo) and ($server->photo === '[]')) {
            return null;
        }
        $url = $this->generator->getPhotosSaveWallPhoto($server, $user_id, $group_id);
        $json = $this->doRequest($url);
        /** @var PhotoResponse|null $return */
        $return = $this->checkResponse($json, 'photos.saveWallPhoto');

        return $return;
    }

    public function videoSave($name, $description, $link, $album_id = null, $group_id = null)
    {
        $url = $this->generator->getVideoSave(urlencode($name), urlencode($description), $link, $album_id, $group_id);
        $json = $this->doRequest($url);
        $return = $this->checkResponse($json, 'video.Save');

        return isset($return->upload_url) ? $return->upload_url : null;
    }
    
    public function videoGet($owner_id)
    {
        $url = $this->generator->getVideoGet($owner_id);
        $json = $this->doRequest($url);
        $return = $this->checkResponse($json, 'video.Get');

        return isset($return[1]) ?$return[1] : null;
    }
    
    public function logErrors()
    {
        $errors = array_filter($this->errors);
        if (!empty($errors)) {
            file_put_contents('vk_cron_log.txt', (date('Y-m-d H:i:s ') . json_encode($errors) . PHP_EOL), FILE_APPEND);
        } else {
            file_put_contents('vk_cron_log.txt', (date('Y-m-d H:i:s OK ') . PHP_EOL), FILE_APPEND);
        }
    }
}
