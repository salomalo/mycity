<?php

namespace console\controllers;

use common\models\Action;
use common\models\Ads;
use common\models\Afisha;
use common\models\Business;
use common\models\File;
use common\models\Gallery;
use common\models\Post;
use Exception;
use PHPImageWorkshop\ImageWorkshop;
use yii;
use yii\console\Controller;
use Aws\S3\S3Client;

class ImagesController extends Controller
{
    /**
     * Полностью удаляет и перезагружает изображения галерей
     * @param null|integer $startId
     */
    public function actionResizeGallery($startId = null)
    {
        $field = 'attachments';
        $type = 'gallery';
        $name = "{$type}_{$field}";
        $componentConfig = Yii::$app->files->config;
        $galleryConfig = $componentConfig[$name];

        $queryGallery = Gallery::find();
        if ($startId) {
            $queryGallery->where(['>=', 'id', $startId]);
        }

        $countImages = File::find()->where(['type' => File::TYPE_GALLERY])->count();
        echo "Galleries: \e[1;33m", $queryGallery->count(), "\e[0m; ",
            "Images: \e[1;33m", $countImages, "\e[31m x", count($galleryConfig['sizes']), "\e[0m", PHP_EOL;

        $queryGallery->orderBy(['id' => SORT_ASC]);

        /** @var Gallery[] $galleries */
        foreach ($queryGallery->batch() as $galleries) {
            foreach ($galleries as $gallery) {
                /** @var File[] $images */
                $images = File::find()->where(['pid' => $gallery->id, 'type' => File::TYPE_GALLERY])->all();

                echo "Gallery ID: \e[1;33m", $gallery->id, "\e[0m -> \e[1;33m", count($images), "\e[0m images", PHP_EOL;

                foreach ($images as $image) {
                    echo "\t\e[1;30m{$image->name}\e[0m ";

                    if (!$this->downloadFile($gallery, $field, $image->name)) {
                        echo "d:\e[31m Error\e[0m", PHP_EOL;
                        continue;
                    } else {
                        echo "d:\e[32m OK\e[0m";
                    }
                    $fileNameArray = explode('.', $image->name, 2);
                    $path = "{$galleryConfig['alias']}/{$gallery->alias}/{$gallery->id}";

                    if ($galleryConfig['sizes']) {
                        foreach ($galleryConfig['sizes'] as $key => $value) {
                            $resizeName = "{$fileNameArray[0]}_{$value}.{$fileNameArray[1]}";

                            $width = $galleryConfig['sizes'][$key];
                            $height = null;

                            if (!empty($galleryConfig['sizesHeight'][$key]) && (($galleryConfig['sizes'][$key] / $galleryConfig['sizesHeight'][$key]) >= 1.5)) {
                                $height = $galleryConfig['sizesHeight'][$key];
                                $width = null;
                            }

                            $this->resizeImage($image->name, $resizeName, $width, $height);

                            if (!@file_exists("/tmp/{$resizeName}")) {
                                echo "\e[31m!\e[0m", PHP_EOL;
                                continue;
                            }

                            $this->removeFileFromServer("{$path}/{$resizeName}");
                            Yii::$app->s3->upload("/tmp/{$resizeName}", "{$path}/{$resizeName}");
                            @unlink("/tmp/{$resizeName}");

                            echo "\e[32m.\e[0m";
                        }
                    }
                    $this->removeFileFromServer("{$path}/{$image->name}");
                    Yii::$app->s3->upload("/tmp/{$image->name}", "{$path}/{$image->name}");
                    @unlink("/tmp/{$image->name}");

                    echo "\e[34m done\e[0m", PHP_EOL;
                }
            }
        }
    }

    /**
     * Добавляет размер к изображениям галереи по его номеру в конфиге(считается от 1)
     * @param $size
     * @param null|integer $startId
     */
    public function actionGalleryAddSize($size, $startId = null)
    {
        $size -= 1;
        $field = 'attachments';
        $type = 'gallery';
        $name = "{$type}_{$field}";
        $componentConfig = Yii::$app->files->config;
        $galleryConfig = $componentConfig[$name];

        $queryGallery = Gallery::find();
        if ($startId) {
            $queryGallery->where(['>=', 'id', $startId]);
        }

        $countImages = File::find()->where(['type' => File::TYPE_GALLERY])->count();
        echo "Galleries: \e[1;33m", $queryGallery->count(), "\e[0m; ",
        "Images: \e[1;33m", $countImages, "\e[31m x", count($galleryConfig['sizes']), "\e[0m", PHP_EOL;

        $queryGallery->orderBy(['id' => SORT_ASC]);

        /** @var Gallery[] $galleries */
        foreach ($queryGallery->batch() as $galleries) {
            foreach ($galleries as $gallery) {
                /** @var File[] $images */
                $images = File::find()->where(['pid' => $gallery->id, 'type' => File::TYPE_GALLERY])->all();

                echo "Gallery ID: \e[1;33m", $gallery->id, "\e[0m -> \e[1;33m", count($images), "\e[0m images", PHP_EOL;

                foreach ($images as $image) {
                    echo "\t\e[1;30m{$image->name}\e[0m ";

                    if (!$this->downloadFile($gallery, $field, $image->name)) {
                        echo "d:\e[31m Error\e[0m", PHP_EOL;
                        continue;
                    } else {
                        echo "d:\e[32m OK\e[0m";
                    }
                    $fileNameArray = explode('.', $image->name, 2);
                    $resizeName = "{$fileNameArray[0]}_{$galleryConfig['sizes'][$size]}.{$fileNameArray[1]}";

                    $width = $galleryConfig['sizes'][$size];
                    $height = null;

                    if (!empty($galleryConfig['sizesHeight'][$size]) && (($galleryConfig['sizes'][$size] / $galleryConfig['sizesHeight'][$size]) >= 1.5)) {
                        $height = $galleryConfig['sizesHeight'][$size];
                        $width = null;
                    }

                    $this->resizeImage($image->name, $resizeName, $width, $height);

                    if (!@file_exists("/tmp/{$resizeName}")) {
                        echo "\e[31m!\e[0m", PHP_EOL;
                        continue;
                    }
                    Yii::$app->s3->upload("/tmp/{$resizeName}", "{$galleryConfig['alias']}/{$gallery->alias}/{$gallery->id}/{$resizeName}");
                    @unlink("/tmp/{$resizeName}");
                    @unlink("/tmp/{$image->name}");
                    echo "\e[34m done\e[0m", PHP_EOL;
                }
            }
        }
    }

    /**
     * Добавляет размер изображения для предприятий
     * @param null|integer $startId
     * @throws Exception
     */
    public function actionBusinessAddSize($startId = null)
    {
        $sizes = [6];
        $field = 'image';
        $type = 'business';
        $name = "{$type}_{$field}";
        $config = Yii::$app->files->config[$name];

        $query = File::find()->where(['type' => File::TYPE_BUSINESS]);
        if ($startId) {
            $query->andWhere(['>=', 'id', $startId]);
        }

        $countImages = $query->count();
        echo "Images: \e[1;33m", $countImages, "\e[31m x", count($config['sizes']), "\e[0m", PHP_EOL;

        $query->orderBy(['id' => SORT_ASC]);

        /** @var File[] $images */
        foreach ($query->batch() as $images) {
            foreach ($images as $image) {
                echo "Image ID: \e[1;33m", $image->id, "\e[0m ";

                $business = Business::findOne($image->pid);

                if (!$business) {
                    echo "d:\e[31m ErrorB\e[0m", PHP_EOL;
                    $image->delete();
                    continue;
                }

                if (!$business->image) {
                    $business->image = $image->name;
                }

                if (@file_get_contents(Yii::$app->files->getUrl($business, $field, 600))) {
                    echo "d:\e[31m Exist\e[0m", PHP_EOL;
                    continue;
                }

                $file = @file_get_contents(Yii::$app->files->getUrl($business, $field, null));

                if (($file === false) || (@file_put_contents("/tmp/{$image->name}", $file) === false)) {
                    echo "d:\e[31m ErrorD\e[0m", PHP_EOL;
                    continue;
                } else {
                    echo "d:\e[32m OK\e[0m";
                }
                $fileNameArray = explode('.', $image->name, 2);
                
                foreach ($sizes as $size) {
                    $resizeName = "{$fileNameArray[0]}_{$config['sizes'][$size]}.{$fileNameArray[1]}";

                    if (!empty($config['sizesHeight'][$size]) && (($config['sizes'][$size] / $config['sizesHeight'][$size]) >= 1.5)) {
                        $this->resizeImage($image->name, $resizeName, null, $config['sizesHeight'][$size]);
                    } else {
                        $this->resizeImage($image->name, $resizeName, $config['sizes'][$size], null);
                    }

                    if (!@file_exists("/tmp/{$resizeName}")) {
                        echo "\e[31m!\e[0m", PHP_EOL;
                        continue;
                    }
                    Yii::$app->s3->upload("/tmp/{$resizeName}", "{$config['alias']}/{$resizeName}");
                    @unlink("/tmp/{$resizeName}");
                    echo "\e[32m.\e[0m";
                }
                @unlink("/tmp/{$image->name}");
                echo "\e[34m done\e[0m", PHP_EOL;
            }
        }
    }

    /**
     * Добавляет размер изображения для прочих моделей
     * @param null $startId
     */
    public function actionAddSize($startId = null)
    {
        $sizes = [
            ['width' => 250, 'height' => null],
            ['width' => 600, 'height' => null],
        ];

        $models = [
            File::TYPE_ACTION => ['config' => Yii::$app->files->config['action_image'], 'object' => new Action(), 'attr' => 'pid'],
            File::TYPE_AFISHA => ['config' => Yii::$app->files->config['afisha_image'], 'object' => new Afisha(), 'attr' => 'pid'],
            File::TYPE_POST => ['config' => Yii::$app->files->config['post_image'], 'object' => new Post(), 'attr' => 'pid'],
//            File::TYPE_ADS => ['config' => Yii::$app->files->config['ads_image'], 'object' => new Ads(), 'attr' => 'pidMongo'],
        ];
        $field = 'image';

        $query = File::find()->where(['type' => array_keys($models)]);
        if ($startId) {
            $query->andWhere(['>=', 'id', $startId]);
        }

        $countImages = $query->count();
        echo "Images: \e[1;33m", $countImages, "\e[0m", PHP_EOL;

        $query->orderBy(['id' => SORT_ASC]);

        /** @var File[] $images */
        foreach ($query->batch() as $images) {
            foreach ($images as $image) {
                echo "Image ID: \e[1;33m", $image->id, "\e[0m t: ", $image->type, ' ';

                $model = $models[$image->type]['object']::findOne($image->{$models[$image->type]['attr']});

                if (!$model) {
                    echo "d:\e[31m ErrorF\e[0m", PHP_EOL;
                    continue;
                }

                if (!$model->image) {
                    $model->image = $image->name;
                }

                if (@file_get_contents(Yii::$app->files->getUrl($model, $field, 600))) {
                    echo "d:\e[31m Exist\e[0m", PHP_EOL;
                    continue;
                }

                $file = @file_get_contents(Yii::$app->files->getUrl($model, $field, null));

                if (($file === false) || (@file_put_contents("/tmp/{$image->name}", $file) === false)) {
                    echo "d:\e[31m ErrorD\e[0m", PHP_EOL;
                    continue;
                } else {
                    echo "d:\e[32m OK\e[0m";
                }
                $fileNameArray = explode('.', $image->name, 2);

                foreach ($sizes as $size) {
                    $resizeName = "{$fileNameArray[0]}_{$size['width']}.{$fileNameArray[1]}";

                    $this->resizeImage($image->name, $resizeName, $size['width'], $size['height']);

                    if (!@file_exists("/tmp/{$resizeName}")) {
                        echo "\e[31m!\e[0m", PHP_EOL;
                        continue;
                    }
                    Yii::$app->s3->upload("/tmp/{$resizeName}", "{$models[$image->type]['config']['alias']}/{$resizeName}");
                    @unlink("/tmp/{$resizeName}");
                    echo "\e[32m.\e[0m";
                }
                @unlink("/tmp/{$image->name}");
                echo "\e[34m done\e[0m", PHP_EOL;
            }
        }
    }

    /**
     * Сохраняет файл с сервера в папку /tmp/
     * @param $model Gallery|Business
     * @param $attr string
     * @param $name string
     * @param null|integer $size
     * 
     * @return bool
     */
    private function downloadFile($model, $attr, $name, $size = null)
    {
        $url = Yii::$app->files->getUrl($model, $attr, $size, $name, "{$model->alias}/{$model->id}");
        $file = @file_get_contents($url);
        
        return !(($file === false) || (@file_put_contents("/tmp/{$name}", $file) === false));
    }

    /**
     * Удаляет файл с сервера по path+name
     * @param $fileName string
     */
    private function removeFileFromServer($fileName)
    {
        S3Client::factory(['key' => Yii::$app->s3->key, 'secret' => Yii::$app->s3->secret])
            ->deleteObject(['Bucket' => Yii::$app->s3->bucket, 'Key' => $fileName]);
    }

    /**
     * Нарезает изображение и сохраняет в /tmp/
     * @param string $name
     * @param string $resizeName
     * @param null|integer $width
     * @param null|integer $height
     */
    private function resizeImage($name, $resizeName, $width = null, $height = null)
    {
        try {
            $layer = ImageWorkshop::initFromPath("/tmp/{$name}");
            $layer->resizeInPixel($width, $height, true);
            $layer->save('/tmp', $resizeName, false);
        } catch (Exception $e) {
            echo PHP_EOL, $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * Создает запись в file для изображений в ads->images
     * @throws yii\mongodb\Exception
     */
    public function actionFixAds()
    {
        $query = Ads::find();
        $count = $query->count();
        $query->limit(100);

        echo "Elements: $count", PHP_EOL;

        for ($i = 0; $i < $count; $i += 100) {
            /** @var Ads[] $models */
            $models = $query->offset($i)->all();

            foreach ($models as $model) {
                if (!$model->image) {
                    echo '-';
                    continue;
                }

                $file = File::find()->where(['name' => $model->image])->one();

                if ($file) {
                    echo '+';
                    continue;
                }

                $file = new File();
                $file->type = (string)File::TYPE_ADS;
                $file->name = $model->image;
                $file->pidMongo = (string)$model->_id;

                echo $file->save() ? '.' : ',';
            }
        }
        echo PHP_EOL;
    }

    /**
     * Создает запись в file и добавляет размер изображения для прочих моделей
     * @param null $startId
     */
    public function actionFixAndAddSize($startId = null)
    {
        $sizes = [
            ['width' => 250, 'height' => null],
            ['width' => 600, 'height' => null],
        ];

        $models = [
            File::TYPE_ACTION => ['config' => Yii::$app->files->config['action_image'], 'object' => new Action(), 'attr' => 'pid'],
            File::TYPE_AFISHA => ['config' => Yii::$app->files->config['afisha_image'], 'object' => new Afisha(), 'attr' => 'pid'],
            File::TYPE_POST => ['config' => Yii::$app->files->config['post_image'], 'object' => new Post(), 'attr' => 'pid'],
        ];
        $field = 'image';

        foreach ($models as $class => $config) {
            $query = $config['object']::find();
            $count = $query->count();
            $query->limit(100);

            echo "Elements: $count", PHP_EOL;

            for ($i = 0; $i < $count; $i += 100) {
                /** @var Action[]|Afisha[]|Post[] $models */
                $models = $query->offset($i)->all();

                foreach ($models as $model) {
                    if (!$model->image) {
                        echo '-';
                        continue;
                    }

                    $file = File::find()->where(['name' => $model->image])->one();

                    if ($file) {
                        echo '+';
                        continue;
                    }

                    $file = new File();
                    $file->type = (string)$class;
                    $file->name = $model->image;
                    $file->pid = (string)$model->id;

                    if (!$file->save()) {
                        echo ',', PHP_EOL;
                        var_dump($file->firstErrors);
                    } else {
                        echo '.';
                    }
                }
            }
            echo PHP_EOL;
        }

        $query = File::find()->where(['type' => array_keys($models)]);
        if ($startId) {
            $query->andWhere(['>=', 'id', $startId]);
        }

        $countImages = $query->count();
        echo "Images: \e[1;33m", $countImages, "\e[0m", PHP_EOL;

        $query->orderBy(['id' => SORT_ASC]);

        /** @var File[] $images */
        foreach ($query->batch() as $images) {
            foreach ($images as $image) {
                echo "Image ID: \e[1;33m", $image->id, "\e[0m t: ", $image->type, ' ';

                $model = $models[$image->type]['object']::findOne($image->{$models[$image->type]['attr']});

                if (!$model) {
                    echo "d:\e[31m ErrorF\e[0m", PHP_EOL;
                    continue;
                }

                if (!$model->image) {
                    $model->image = $image->name;
                }

                if (@file_get_contents(Yii::$app->files->getUrl($model, $field, 600))) {
                    echo "d:\e[31m Exist\e[0m", PHP_EOL;
                    continue;
                }

                $file = @file_get_contents(Yii::$app->files->getUrl($model, $field, null));

                if (($file === false) || (@file_put_contents("/tmp/{$image->name}", $file) === false)) {
                    echo "d:\e[31m ErrorD\e[0m", PHP_EOL;
                    continue;
                } else {
                    echo "d:\e[32m OK\e[0m";
                }
                $fileNameArray = explode('.', $image->name, 2);

                foreach ($sizes as $size) {
                    $resizeName = "{$fileNameArray[0]}_{$size['width']}.{$fileNameArray[1]}";

                    $this->resizeImage($image->name, $resizeName, $size['width'], $size['height']);

                    if (!@file_exists("/tmp/{$resizeName}")) {
                        echo "\e[31m!\e[0m", PHP_EOL;
                        continue;
                    }
                    Yii::$app->s3->upload("/tmp/{$resizeName}", "{$models[$image->type]['config']['alias']}/{$resizeName}");
                    @unlink("/tmp/{$resizeName}");
                    echo "\e[32m.\e[0m";
                }
                @unlink("/tmp/{$image->name}");
                echo "\e[34m done\e[0m", PHP_EOL;
            }
        }
    }
}
