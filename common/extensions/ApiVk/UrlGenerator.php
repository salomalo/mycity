<?php
namespace common\extensions\ApiVk;

/**
 * Class UrlGenerator
 * @package common\extensions\ApiVk
 *
 * @property string $getAccessToken()
 */
class UrlGenerator
{
    private $token;
    private $baseUrl = 'https://api.vk.com/method/';

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function getAccessToken()
    {
        return "v=5.52&access_token={$this->token}";
    }

    public function getWallPost($message, $owner_id = null, $attachments = null)
    {
        $time = time();

        $method[] = $this->baseUrl;
        $method[] = 'wall.post?';
        $method[] = $owner_id ? "owner_id={$owner_id}&" : null;
        $method[] = 'from_group=1&';
        $method[] = "message={$message}&";
        $method[] = "guid={$time}&";
        if (!empty($attachments) and is_array($attachments)) {
            $method[] = 'attachments=' . implode(',', $attachments) . '&';
        }
        $method[] = $this->getAccessToken();

        return implode($method);
    }

    public function getPhotosGetWallUploadServer($group_id = null)
    {
        $method[] = $this->baseUrl;
        $method[] = 'photos.getWallUploadServer?';
        $method[] = $group_id ? "group_id={$group_id}&" : null;
        $method[] = $this->getAccessToken();

        return implode($method);
    }

    public function getPhotosSaveWallPhoto($server, $user_id = null, $group_id = null)
    {
        $method[] = $this->baseUrl;
        $method[] = 'photos.saveWallPhoto?';
        $method[] = $group_id ? "group_id={$group_id}&" : null;
        $method[] = $user_id ? "user_id={$user_id}&" : null;
        $method[] = !empty($server->photo) ? "photo={$server->photo}&" : null;
        $method[] = !empty($server->server) ? "server={$server->server}&" : null;
        $method[] = !empty($server->hash) ? "hash={$server->hash}&" : null;
        $method[] = $this->getAccessToken();

        return implode($method);
    }

    public function getVideoSave($name, $description, $link, $album_id = null, $group_id = null, $is_private = 0, $wallpost = 0, $no_comments = 0, $repeat = 0)
    {
        $method[] = $this->baseUrl;
        $method[] = 'video.save?';
        $method[] = "name={$name}&description={$description}&link={$link}&";
        $method[] = $album_id ? "album_id={$album_id}&" : null;
        $method[] = $group_id ? "link={$group_id}&" : null;
        $method[] = "is_private={$is_private}&wallpost={$wallpost}&no_comments={$no_comments}&repeat={$repeat}&";
        $method[] = $this->getAccessToken();

        return implode($method);
    }

    public function getVideoGet($owner_id, $count = 1, $offset = 0, $videos = null, $album_id = null, $extended = 0)
    {
        $method[] = $this->baseUrl;
        $method[] = 'video.get?';
        $method[] = "owner_id={$owner_id}&count={$count}&offset={$offset}&extended={$extended}";
        $method[] = $videos ? "videos={$videos}&" : null;
        $method[] = $album_id ? "album_id={$album_id}&" : null;
        $method[] = $this->getAccessToken();

        return implode($method);
    }
}
