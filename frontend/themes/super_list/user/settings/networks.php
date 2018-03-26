<?php

use dektrium\user\widgets\Connect;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 */

$this->title = Yii::t('user', 'Networks');
$this->params['breadcrumbs'][] = $this->title;

$networksVisible = count(Yii::$app->authClientCollection->clients) > 0;
$action = Yii::$app->controller->action->id;
?>
<div class="main">
    <div class="main-inner">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-lg-9">
                    <div id="primary">
                        <div class="document-title">
                            <ol class="breadcrumb">
                                <li>
                                    <a href="/ru">Главная</a>
                                </li>
                            </ol>
                            <h1><?= Yii::t('user', 'Networks') ?></h1>
                        </div><!-- /.document-title -->
                    </div>

                    <?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

                    <div class="row">
                        <div class="col-md-7">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <?= Html::encode($this->title) ?>
                                </div>
                                <div class="panel-body">
                                    <div class="alert alert-info">
                                        <p><?= Yii::t('user', 'You can connect multiple accounts to be able to log in using them') ?>
                                            .</p>
                                    </div>
                                    <?php $auth = Connect::begin([
                                        'baseAuthUrl' => ['/user/security/auth'],
                                        'accounts' => $user->accounts,
                                        'autoRender' => false,
                                        'popupMode' => false,
                                    ]) ?>
                                    <table class="table">
                                        <?php foreach ($auth->getClients() as $client): ?>
                                            <tr>
                                                <td style="width: 32px; vertical-align: middle">
                                                    <?= Html::tag('span', '', ['class' => 'auth-icon ' . $client->getName()]) ?>
                                                </td>
                                                <td style="vertical-align: middle; width: 200px">
                                                    <strong><?= $client->getTitle() ?></strong>
                                                </td>
                                                <td style="width: 100px; margin-right: 20px;">
                                                    <?= $auth->isConnected($client) ?
                                                        Html::a(Yii::t('user', 'Disconnect'), $auth->createClientUrl($client), ['class' => 'btn btn-danger btn-block', 'data-method' => 'post', 'style' => 'width: 160px']) :
                                                        Html::a(Yii::t('user', 'Connect'), $auth->createClientUrl($client), ['class' => 'btn btn-primary btn-block', 'style' => 'width: 160px'])
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                    <?php Connect::end() ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 col-lg-3">
                    <div class="sidebar sidebar_dashboard">
                        <div id="nav_menu-7" class="widget widget_nav_menu"><h2 class="widgettitle">Меню</h2>
                            <div class="menu-dashboard-menu-container">
                                <ul id="menu-dashboard-menu" class="menu">
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-3445">
                                        <?= Html::a('<i class="fa fa-fw fa-list"></i>' . Yii::t('app', 'Options'), ['/user/profile/index']); ?>
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-3446">
                                        <?= Html::a('<i class="fa fa-fw fa-user"></i>' . Yii::t('user', 'Account'), ['/user/settings/account']); ?>
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-3447">
                                        <?= Html::a('<i class="fa fa-fw fa-certificate"></i>' . Yii::t('user', 'Profile'), ['/user/settings/profile']); ?>
                                    </li>
                                    <?php if ($networksVisible) : ?>
                                        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-3448">
                                            <?= Html::a('<i class="fa fa-fw fa-eye"></i>' . Yii::t('user', 'Networks'), ['/user/settings/networks']); ?>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div><!-- /.sidebar -->
                </div>

            </div>
        </div>
    </div>
</div>