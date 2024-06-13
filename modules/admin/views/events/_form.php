<?php

use app\modules\admin\models\forms\EventForm;
use kartik\select2\Select2;
use yii\helpers\{Html, Url};
use yii\jui\DatePicker;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var EventForm $model */
/** @var ActiveForm $form */
?>

<div class="event-form">

    <?php
    $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <?= $form->field($model, 'planned_date')->widget(DatePicker::class, [
                'language'   => 'ru',
                'dateFormat' => 'yyyy-MM-dd',
                'clientOptions' => [
                    'minDate' => 0
                ]
            ]) ?>
        </div>
    </div>

    <?= $form->field($model, 'organizatorIds')
        ->widget(Select2::class, [
            'initValueText' => $model->isNewRecord ? '' : $model->getOrganizatorOptions(),
            'options' => ['placeholder' => 'Select an organizator...', 'multiple' => true],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 3,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return '" . Yii::t('app', 'Waiting for results...') . "'; }"),
                ],
                'ajax' => [
                    'url' => Url::to(['organizators/autocomplete']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {str:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(organizator) { return organizator.text; }'),
                'templateSelection' => new JsExpression('function (organizator) { return organizator.text; }'),
            ],
        ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
