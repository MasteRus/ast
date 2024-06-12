<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\activeRecord\Organizator $model */

$this->title = Yii::t('app', 'Create Organizator');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Organizators'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organizator-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
