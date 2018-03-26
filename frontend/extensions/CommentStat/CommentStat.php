<?php
namespace frontend\extensions\CommentStat;

use common\models\Comment;
use yii\base\Widget;

class CommentStat extends Widget
{
    public $business;

    public function run(){
        $countCommentPrice = Comment::find()
            ->where(['pid' => $this->business->id])
            ->andWhere(['business_type' => Comment::TYPE_COMMENT_SHOP])
            ->andWhere(['or', ['correct_price' => Comment::RATING_YES], ['correct_price' => Comment::RATING_NO]])
            ->count();
        $countGoodCommentPrice = Comment::find()
            ->where(['pid' => $this->business->id])
            ->andWhere(['business_type' => Comment::TYPE_COMMENT_SHOP])
            ->andWhere(['correct_price' => Comment::RATING_YES])
            ->count();

        $countProdAvailability = Comment::find()
            ->where(['pid' => $this->business->id])
            ->andWhere(['business_type' => Comment::TYPE_COMMENT_SHOP])
            ->andWhere(['or', ['product_availability' => Comment::RATING_YES], ['product_availability' => Comment::RATING_NO]])
            ->count();
        $countGoodProdAvailability = Comment::find()
            ->where(['pid' => $this->business->id])
            ->andWhere(['business_type' => Comment::TYPE_COMMENT_SHOP])
            ->andWhere(['product_availability' => Comment::RATING_YES])
            ->count();

        $countCorrectDescription = Comment::find()
            ->where(['pid' => $this->business->id])
            ->andWhere(['business_type' => Comment::TYPE_COMMENT_SHOP])
            ->andWhere(['or', ['correct_description' => Comment::RATING_YES], ['correct_description' => Comment::RATING_NO]])
            ->count();
        $countGoodCorrectDescription = Comment::find()
            ->where(['pid' => $this->business->id])
            ->andWhere(['business_type' => Comment::TYPE_COMMENT_SHOP])
            ->andWhere(['correct_description' => Comment::RATING_YES])
            ->count();

        $countInTime = Comment::find()
            ->where(['pid' => $this->business->id])
            ->andWhere(['business_type' => Comment::TYPE_COMMENT_SHOP])
            ->andWhere([
                'or',
                ['order_executed_on_time' => Comment::RATING_YES],
                ['order_executed_on_time' => Comment::RATING_NO]
            ])
            ->count();
        $countGoodInTime = Comment::find()
            ->where(['pid' => $this->business->id])
            ->andWhere(['business_type' => Comment::TYPE_COMMENT_SHOP])
            ->andWhere(['order_executed_on_time' => Comment::RATING_YES])
            ->count();

        $countCallback = Comment::find()
            ->where(['pid' => $this->business->id])
            ->andWhere(['business_type' => Comment::TYPE_COMMENT_SHOP])
            ->andWhere('rating_callback > :mark', ['mark' => Comment::CALLBACK_DEFAULT])
            ->count();
        $countGoodCallback = Comment::find()
            ->where(['pid' => $this->business->id])
            ->andWhere(['business_type' => Comment::TYPE_COMMENT_SHOP])
            ->andWhere('rating_callback > :markMin', ['markMin' => Comment::CALLBACK_DEFAULT])
            ->andWhere('rating_callback < :markMax', ['markMax' => Comment::CALLBACK_NEXT_DAY])
            ->count();

        return $this->render('index', [
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