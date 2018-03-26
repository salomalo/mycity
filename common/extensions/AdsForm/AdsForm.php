<?php

namespace common\extensions\AdsForm;

use common\models\AdsProperty;
use yii;
use yii\base\Widget;
use common\models\Ads;
use common\models\ProductCompany;
use common\models\Profile;

class AdsForm extends Widget
{
    /** @var Ads $model */
    public $model;

    /** @var integer $idBusiness */
    public $idBusiness;

    public function init()
    {
        Assets::register($this->view);
    }

    public function run()
    {
        $idCompanyName = 'xxx';
        $company = empty($this->model->idCompany) ? null : ProductCompany::findOne($this->model->idCompany);

        if ($company) {
            $idCompanyName = $company->title;
        }

        if (empty($this->model->contact) && !$this->idBusiness) {
            if (!empty($this->model->business->phone)) {
                $this->model->contact = $this->model->business->phone;
            } elseif (($profile = Profile::findOne(Yii::$app->user->id)) && $profile->phone) {
                $this->model->contact = $profile->phone;
            }
        }


        $adsProperty = AdsProperty::find()
            ->where(['business_id' => $this->idBusiness ? $this->idBusiness : null, 'user_id' => Yii::$app->user->id])
            ->one();

        if ($this->idBusiness){
            return $this->render('index', [
                'model' => $this->model,
                'idBusiness' => $this->idBusiness,
                'idCompanyName' => $idCompanyName,
                'adsProperty' => $adsProperty,
            ]);
        } else {
            return $this->render('index_full', [
                'model' => $this->model,
                'idBusiness' => $this->idBusiness,
                'idCompanyName' => $idCompanyName,
                'adsProperty' => $adsProperty,
            ]);
        }


    }
}
