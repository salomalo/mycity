<?php

namespace frontend\controllers;

use common\models\Ads;
use common\models\Business;
use common\models\CommentRating;
use common\models\File;
use common\models\User;
use frontend\extensions\CommentsSuperlist\CommentsSuperlist;
use common\models\Log as Log;
use yii;
use yii\data\Pagination;
use yii\web\Controller;
use common\models\Comment;
use \common\extensions\Comments\Comments;
use yii\web\HttpException;

/**
 * Description of CommentController
 *
 * @author dima
 */
class CommentController extends Controller
{
    public function actionAdd()
    {
        if (!Yii::$app->request->isAjax) {
            throw new HttpException(404);
        } elseif (isset($_POST['Comment'])) {
            $form = $_POST['Comment'];
            $comment = new Comment();
            $comment->text = $form['text'];
            $comment->idUser = \Yii::$app->user->identity->id;
            $comment->type = $form['type'];

            if (isset($_POST['mongo']) and ((int)$_POST['mongo'] === 1)) {
                $comment->pidMongo = $form['pid'];
                $comment->pid = 0;
            } else {
                $comment->pid = $form['pid'];
            }

            if ($comment->save()) {
                Log::addUserLog("comment[create][{$form['type']}]  ID: {$comment->id}", $comment->id, Log::TYPE_COMMENT);
                Comments::getComment($comment, $comment->type, 2, 0, false, true);
            }
        } elseif (isset($_POST['text'], $_POST['parent'], $_POST['nesting'], $_POST['limit'])) {

            $parent = Comment::findOne((int)$_POST['parent']);

            $comment = new Comment();
            $comment->text = $_POST['text'];
            $comment->idUser = \Yii::$app->user->identity->id;
            $comment->type = $parent->type;
            $comment->parentId = (int)$_POST['parent'];
            $mongo = ($parent->pid === 0) ? true : false;

            if ($mongo) {
                $comment->pidMongo = $parent->pidMongo;
            }

            $comment->pid = $parent->pid;

            if ($comment->save()) {
                Log::addUserLog("comment[create][{$parent->type}]   ID: {$comment->id}", $comment->id, Log::TYPE_COMMENT);
                Comments::getComment($comment, $comment->type, (int)$_POST['limit'], (int)$_POST['nesting'], true, true);
            }
        } else {
            throw new HttpException(404);
        }
    }

    public function actionCreateAds(){
        $model = new Comment();
        $model->pid = 0;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

        } else {

        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionCreate($idBusiness = null){
        if (!Yii::$app->user->identity) {
            return $this->redirect('/business');
        }

        $model = new Comment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['list', 'idBusiness' => $model->pid]);
        } else {
            $business = Business::findOne($idBusiness);
            if (!$business){
                throw new HttpException(404);
            }

            //init default value
            $model->rating_business = Comment::RATING_BUSINESS_VERY_GOOD;
            $model->correct_price = Comment::RATING_DO_NOT_REMEMBER;
            $model->product_availability = Comment::RATING_DO_NOT_REMEMBER;
            $model->correct_description = Comment::RATING_DO_NOT_REMEMBER;
            $model->order_executed_on_time = Comment::RATING_DO_NOT_REMEMBER;
            $model->rating_callback = Comment::CALLBACK_DEFAULT;
            $model->rating_recommend = Comment::RECOMMEND_COMPANY_MAYBE;

            $countComments = Comment::find()->where(['pid' => $business->id, 'business_type' => Comment::TYPE_COMMENT_SHOP])->count();
            $countGoodComments = Comment::find()
                ->where(['pid' => $business->id, 'business_type' => Comment::TYPE_COMMENT_SHOP])
                ->andWhere(['not', ['rating_business' => null]])
                ->andWhere('rating_business > :mark', ['mark' => Comment::RATING_BUSINESS_BAD])
                ->count();

            $lvlGoodComment = $countComments ? (integer)($countGoodComments / $countComments * 100) : 0;

            $countCommentPrice = Comment::find()
                ->where(['pid' => $business->id])
                ->andWhere(['business_type' => Comment::TYPE_COMMENT_SHOP])
                ->andWhere(['or', ['correct_price' => Comment::RATING_YES], ['correct_price' => Comment::RATING_NO]])
                ->count();
            $countGoodCommentPrice = Comment::find()
                ->where(['pid' => $business->id])
                ->andWhere(['business_type' => Comment::TYPE_COMMENT_SHOP])
                ->andWhere(['correct_price' => Comment::RATING_YES])
                ->count();

            $countProdAvailability = Comment::find()
                ->where(['pid' => $business->id])
                ->andWhere(['business_type' => Comment::TYPE_COMMENT_SHOP])
                ->andWhere(['or', ['product_availability' => Comment::RATING_YES], ['product_availability' => Comment::RATING_NO]])
                ->count();
            $countGoodProdAvailability = Comment::find()
                ->where(['pid' => $business->id])
                ->andWhere(['business_type' => Comment::TYPE_COMMENT_SHOP])
                ->andWhere(['product_availability' => Comment::RATING_YES])
                ->count();

            $countCorrectDescription = Comment::find()
                ->where(['pid' => $business->id])
                ->andWhere(['business_type' => Comment::TYPE_COMMENT_SHOP])
                ->andWhere(['or', ['correct_description' => Comment::RATING_YES], ['correct_description' => Comment::RATING_NO]])
                ->count();
            $countGoodCorrectDescription = Comment::find()
                ->where(['pid' => $business->id])
                ->andWhere(['business_type' => Comment::TYPE_COMMENT_SHOP])
                ->andWhere(['correct_description' => Comment::RATING_YES])
                ->count();

            $countInTime = Comment::find()
                ->where(['pid' => $business->id])
                ->andWhere(['business_type' => Comment::TYPE_COMMENT_SHOP])
                ->andWhere([
                    'or',
                    ['order_executed_on_time' => Comment::RATING_YES],
                    ['order_executed_on_time' => Comment::RATING_NO]
                ])
                ->count();
            $countGoodInTime = Comment::find()
                ->where(['pid' => $business->id])
                ->andWhere(['business_type' => Comment::TYPE_COMMENT_SHOP])
                ->andWhere(['order_executed_on_time' => Comment::RATING_YES])
                ->count();

            $countCallback = Comment::find()
                ->where(['pid' => $business->id])
                ->andWhere(['business_type' => Comment::TYPE_COMMENT_SHOP])
                ->andWhere('rating_callback > :mark', ['mark' => Comment::CALLBACK_DEFAULT])
                ->count();
            $countGoodCallback = Comment::find()
                ->where(['pid' => $business->id])
                ->andWhere(['business_type' => Comment::TYPE_COMMENT_SHOP])
                ->andWhere('rating_callback > :markMin', ['markMin' => Comment::CALLBACK_DEFAULT])
                ->andWhere('rating_callback < :markMax', ['markMax' => Comment::CALLBACK_NEXT_DAY])
                ->count();

            return $this->render('create', [
                'business' => $business,
                'model' => $model,
                'countComments' => $countComments,
                'lvlGoodComment' => $lvlGoodComment,
                'countCommentPrice' => $countCommentPrice,
                'countGoodCommentPrice' => $countGoodCommentPrice,
                'countProdAvailability' => $countProdAvailability,
                'countGoodProdAvailability' => $countGoodProdAvailability,
                'countCorrectDescription' => $countCorrectDescription,
                'countGoodCorrectDescription' => $countGoodCorrectDescription,
                'countInTime' => $countInTime,
                'countGoodInTime' => $countGoodInTime,
                'countCallback' => $countCallback,
                'countGoodCallback' => $countGoodCallback,
            ]);
        }
    }

    public function actionList($alias = null){
        $url = explode('-', $alias, 2);
        $url[0] = (int)$url[0];

        if (!$url[0]) {
            throw new HttpException(404);
        }

        $business = Business::findOne($url[0]);
        if (!$business) {
            throw new HttpException(404);
        }

        $countComments = Comment::find()->where(['pid' => $business->id, 'business_type' => Comment::TYPE_COMMENT_SHOP])->count();
        $countGoodComments = Comment::find()
            ->where(['pid' => $business->id, 'business_type' => Comment::TYPE_COMMENT_SHOP])
            ->andWhere(['not', ['rating_business' => null]])
            ->andWhere('rating_business > :mark', ['mark' => Comment::RATING_BUSINESS_BAD])
            ->count();
        $lvlGoodComment = $countComments ? (integer)($countGoodComments / $countComments * 100) : 0;

        $query = Comment::find()
            ->where(['pid' => $business->id, 'business_type' => Comment::TYPE_COMMENT_SHOP])
            ->groupBy(['id'])
            ->orderBy(['id' => SORT_DESC]);

        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 10]);
        $pages->pageSizeParam = false;
        $query->offset($pages->offset)->limit($pages->limit);

        $countCompanyAds = Ads::find()->where(['idBusiness' => $business->id])->count();

        return $this->render('list', [
            'business' => $business,
            'models' => $query->all(),
            'pages' => $pages,
            'countComments' => $countComments,
            'lvlGoodComment' => $lvlGoodComment,
            'lastCompanyActivity' => $business->user ? $business->user->last_activity : null,
            'countCompanyAds' => $countCompanyAds,
        ]);
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->post('id');
        $arr = [];
        if ($id and Yii::$app->request->isAjax) {
            $model = Comment::findOne(['id' => $id, 'idUser' => \Yii::$app->user->identity->id]);

            if ($model) {
                $arr = $this->getArrChildrens($model);
                Log::addUserLog("comment[delete]  ID: {$id}", $id, Log::TYPE_COMMENT);
                $model->delete();
            }
        } else {
            throw new HttpException(404);
        }

        return json_encode($arr);
    }

    public function getArrChildrens($model)
    {
        static $arr = [];
        foreach ($model->childrens as $item) {
            $arr[] = $item->id;
            if ($item->childrens) {
                $this->getArrChildrens($item);
            }
        }
        return $arr;
    }

    public static function getChildrenComments($parentItem, $pid, $type, $limit, $nesting = 0)
    {

        $nesting += 1;

        foreach ($parentItem->childrens as $item) {

            Comments::getComment($item, $type, $limit, $nesting);

            if ($item->childrens) {
                self::getChildrenComments($item, $pid, $type, $limit, $nesting);
            }
        }
    }

    public function actionUpdate()
    {
        if (!Yii::$app->request->isAjax) {
            throw new HttpException(404);
        }
        $id_comment = (isset($_POST['id']) and $_POST['id']) ? (int)$_POST['id'] : null;
        $id_user = (Yii::$app->user->id) ? Yii::$app->user->id : null;
        if ($id_comment === null or $id_user === null) {
            throw new HttpException(403);
        }
        $model = Comment::findOne(['id' => $id_comment, 'idUser' => $id_user]);
        if (!$model) {
            throw new HttpException(404);
        } elseif ((strtotime($model->dateCreate) < (time() - 5 * 60))) {
            throw new HttpException(403);
        }

        $request = '';

        if (isset($_POST['text']) and empty($_POST['text'])) {
            throw new HttpException(403);
        } elseif (!isset($_POST['text'])) {
            $request = strip_tags($model->text);
        } elseif (isset($_POST['text']) and !empty($_POST['text'])) {
            $model->text = $_POST['text'];
            if ($model->save()) {
                Log::addUserLog("comment[update]  ID: {$model->id}", $model->id, Log::TYPE_COMMENT);
                Comments::getComment($model, $model->type, 2, 0, false, true);
            }
        }
        return $request;
    }

    public function actionRating()
    {
        $id = Yii::$app->request->post('id');
        $action = Yii::$app->request->post('action');
        if (!Yii::$app->request->isAjax or !$id or !$action) {
            throw new HttpException(404);
        }
        /* @var $model Comment */
        $model = Comment::findOne($id);
        if (!$model) {
            throw new HttpException(404);
        }
        $comment_rating = CommentRating::getUserRateComment($id);

        $clear = false;
        if (is_object($comment_rating) and $action === 'clear') {
            $action = $comment_rating->getAllowAction();
            $comment_rating->delete();
            //Yii::$app->logger->resetLikeTrigger(Comment::className(), $model->id, DetailLogObjectType::TYPE_COMMENT);
            $clear = true;
            $step = 1;
        } else {
            $step = is_object($comment_rating) ? 2 : 1;
        }

        if (empty($comment_rating) or ($action === $comment_rating->getAllowAction())) {
            switch ($action) {
                case 'like':
                    $model->like += $step;
                    if (!$clear) {
                        if (!is_object($comment_rating)) {
                            $comment_rating = new CommentRating(['comment_id' => $model->id]);
                        }
                        $comment_rating->vote = $comment_rating::LIKE;
                        $comment_rating->save();
                        //Yii::$app->logger->likeTrigger(Comment::className(), $model->id, DetailLogObjectType::TYPE_COMMENT);
                    }
                    break;
                case 'unlike':
                    $model->unlike += $step;
                    if (!$clear) {
                        if (!is_object($comment_rating)) {
                            $comment_rating = new CommentRating(['comment_id' => $model->id]);
                        }
                        $comment_rating->vote = $comment_rating::DISLIKE;
                        $comment_rating->save();
                        //Yii::$app->logger->dislikeTrigger(Comment::className(), $model->id, DetailLogObjectType::TYPE_COMMENT);
                    }
                    break;
                default:
                    throw new HttpException(404);
            }
        }
        $model->save();
        $rating = $model->like - $model->unlike;
        echo $rating;
    }

    public function actionAddSuperlist()
    {
        if (!Yii::$app->request->isAjax) {
            throw new HttpException(404);
        } elseif (isset($_POST['Comment'])) {
            $form = $_POST['Comment'];
            $comment = new Comment();
            $comment->text = $form['text'];
            $comment->idUser = \Yii::$app->user->identity->id;
            $comment->type = $form['type'];

            if (isset($_POST['mongo']) and ((int)$_POST['mongo'] === 1)) {
                $comment->pidMongo = $form['pid'];
                $comment->pid = 0;
            } else {
                $comment->pid = $form['pid'];
            }

            if ($comment->save()) {
                Log::addUserLog("comment[create][{$form['type']}]  ID: {$comment->id}", $comment->id, Log::TYPE_COMMENT);
                CommentsSuperlist::getComment($comment, $comment->type, 2, 0, false, true);
            }
        } elseif (isset($_POST['text'], $_POST['parent'], $_POST['nesting'], $_POST['limit'])) {

            $parent = Comment::findOne((int)$_POST['parent']);

            $comment = new Comment();
            $comment->text = $_POST['text'];
            $comment->idUser = \Yii::$app->user->identity->id;
            $comment->type = $parent->type;
            $comment->parentId = (int)$_POST['parent'];
            $mongo = ($parent->pid === 0) ? true : false;

            if ($mongo) {
                $comment->pidMongo = $parent->pidMongo;
            }

            $comment->pid = $parent->pid;

            if ($comment->save()) {
                Log::addUserLog("comment[create][{$parent->type}]  ID: {$comment->id}", $comment->id, Log::TYPE_COMMENT);
                CommentsSuperlist::getComment($comment, $comment->type, (int)$_POST['limit'], (int)$_POST['nesting'], true, true);
            }
        } else {
            throw new HttpException(404);
        }
    }
}
