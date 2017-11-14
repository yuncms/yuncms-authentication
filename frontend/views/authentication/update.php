<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\captcha\Captcha;
use yii\bootstrap\ActiveForm;
use xutl\bootstrap\filestyle\FilestyleAsset;
use yuncms\authentication\models\Authentication;

FilestyleAsset::register($this);
/*
 * @var yii\web\View $this
 * @var yuncms\authentication\models\Authentication $model
 */

$this->title = Yii::t('authentication', 'Authentication');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <h2 class="h3 profile-title"><?= Yii::t('authentication', 'Authentication') ?></h2>
        <div class="row">
            <div class="col-md-12">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
