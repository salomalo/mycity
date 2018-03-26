<?php
namespace common\behaviors;

use yii;
use yii\base\Behavior;
use yii\base\Exception;
use yii\db\BaseActiveRecord;
use common\models\City;
use common\models\Action;
use common\models\Afisha;
use common\models\Post;
use common\models\File;
use common\models\Wall;
use common\models\ScheduleKino;

/**
 * Class WallPost
 * @package common\behaviors
 *
 * @property Afisha|Post|Action|ScheduleKino $owner
 * @property integer $ownerType
 */
class WallPost extends Behavior
{
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'addWallPost',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'addWallPost',
            BaseActiveRecord::EVENT_AFTER_DELETE => 'deleteWallPost',
        ];
    }

    public function addWallPost()
    {
        switch ($this->ownerType) {
            case File::TYPE_AFISHA:
                if ((int)$this->owner->isFilm === 0) {
                    $this->afisha();
                }
                break;
            case File::TYPE_ACTION:
                $this->action();
                break;
            case File::TYPE_POST:
                $this->post();
                break;
            case File::TYPE_AFISHA_WITH_SCHEDULE:
                $this->schedule();
                break;
        }
    }

    public function deleteWallPost()
    {
        $wall = Wall::find()->where(['pid' => $this->owner->id, 'type' => $this->ownerType])->one();
        if (is_object($wall)) {
            $wall->delete();
        }
    }

    public function getOwnerType()
    {
        $ownerClass = get_class($this->owner);
        $selfClass = self::className();

        switch ($ownerClass) {
            case Afisha::className():
                return File::TYPE_AFISHA;
            case Action::className():
                return File::TYPE_ACTION;
            case Post::className():
                return File::TYPE_POST;
            case ScheduleKino::className():
                return File::TYPE_AFISHA_WITH_SCHEDULE;
            default:
                throw new Exception("$selfClass behavior: error owner type $ownerClass");
        }
    }

    public function afisha()
    {
        /** @var $wall Wall */
        $wall = Wall::find()->where(['pid' => $this->owner->id, 'type' => $this->ownerType])->one();
        if (!is_object($wall)) {
            $wall = new Wall();
            $wall->type = File::TYPE_AFISHA;
            $wall->pid = $this->owner->id;
            $wall->url = $this->owner->url;
        }
        $wall->image = $this->owner->image ? Yii::$app->files->getUrl($this->owner, 'image') : '';
        $wall->title = $this->owner->title;

        $model = Afisha::findOne(['id' => $this->owner->id]);

        $wall->idCity = !empty($model->companys[0]) ? $model->companys[0]->idCity : '';
        $wall->description = $this->owner->description ? strip_tags($this->owner->description) : strip_tags($this->owner->fullText);

        if ($model->isChecked) {
            $wall->save();
        }
    }

    public function action()
    {
        /** @var $wall Wall */
        $wall = Wall::find()->where(['pid' => $this->owner->id, 'type' => $this->ownerType])->one();
        if (!is_object($wall)) {
            $wall = new Wall();
            $wall->type = File::TYPE_ACTION;
            $wall->pid = $this->owner->id;
            $wall->url = $this->owner->url;
        }
        $wall->image = $this->owner->image ? Yii::$app->files->getUrl($this->owner, 'image') : '';
        $wall->title = $this->owner->title;

        $wall->idCity = $this->owner->companyName ? $this->owner->companyName->idCity : '';
        $wall->description = $this->owner->description ? strip_tags($this->owner->description) : '';

        $wall->save();
    }

    public function post()
    {
        /** @var Post $post */
        $post = $this->owner;

        if ($post->allCity) {
            Wall::deleteAll(['pid' => $post->id, 'type' => $this->ownerType]);

            $cities = City::find()->select('id')
                ->where(['not', ['vk_public_id' => null]])
                ->andWhere(['not', ['vk_public_admin_token' => null]])
                ->andWhere(['not', ['vk_public_admin_id' => null]])
                ->column();

            if ((int)$post->status === Post::TYPE_PUBLISHED) {
                $wall = new Wall();
                $wall->type = File::TYPE_POST;
                $wall->pid = $post->id;
                $wall->url = $post->url;
                $wall->image = $post->image ? Yii::$app->files->getUrl($post, 'image') : '';
                $wall->title = $post->title;
                $wall->description = !empty($post->shortText) ? strip_tags($post->shortText) : strip_tags($post->fullText);

                foreach ($cities as $city) {
                    $new_wall = clone $wall;

                    $new_wall->idCity = $city;
                    $new_wall->save();
                }
            }
        } elseif (!$post->onlyMain and $post->idCity) {
            /** @var $walls Wall */
            $wall = Wall::find()->where(['pid' => $post->id, 'type' => $this->ownerType])->one();
            if (!$wall) {
                $wall = new Wall();
                $wall->type = File::TYPE_POST;
                $wall->pid = $post->id;
                $wall->url = $post->url;
            }

            $wall->image = $post->image ? Yii::$app->files->getUrl($post, 'image') : '';
            $wall->title = $post->title;

            if ((int)$post->status !== Post::TYPE_PUBLISHED) {
                $wall->delete();
            } else {
                $wall->idCity = $post->idCity;
                $wall->description = !empty($post->shortText) ? strip_tags($post->shortText) : strip_tags($post->fullText);

                $wall->save();
            }
        }
    }

    public function schedule()
    {
        $afisha = $this->owner->afisha;
        if ($afisha) {
            /** @var $wall Wall */
            $wall = Wall::find()->where(['pid' => $afisha->id, 'type' => $this->ownerType, 'idCity' => $this->owner->idCity])->one();
            if (!is_object($wall)) {
                $wall = new Wall();
                $wall->type = $this->ownerType;
                $wall->pid = $afisha->id;
                $wall->url = $afisha->url;

                $wall->image = $afisha->image ? Yii::$app->files->getUrl($afisha, 'image') : '';
                $wall->title = $afisha->title;

                $wall->idCity = $this->owner->idCity;
                $wall->description = $afisha->description ? strip_tags($afisha->description) : strip_tags($afisha->fullText);

                $wall->save();
            }
        }
    }
}
