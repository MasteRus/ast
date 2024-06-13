<?php

use app\modules\admin\models\forms\OrganizatorForm;
use kartik\select2\Select2;
use yii\helpers\{Html, Url};
use yii\web\JsExpression;
use yii\widgets\{ActiveForm, MaskedInput};

/** @var yii\web\View $this */
/** @var OrganizatorForm $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="organizator-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->widget(MaskedInput::className(), [
        'mask' => '+7 (999) 999 99 99',
    ])->label(false) ?>

    <?= $form->field($model, 'eventIds')
        ->widget(Select2::class, [
            'initValueText' => $model->isNewRecord ? '' : $model->getEventOptions(),
            'options' => ['placeholder' => 'Select an event...', 'multiple' => true],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 3,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return '" . Yii::t('app', 'Waiting for results...') . "'; }"),
                ],
                'ajax' => [
                    'url' => Url::to(['events/autocomplete']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {str:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(event) { return event.text; }'),
                'templateSelection' => new JsExpression('function (event) { return event.text; }'),
            ],
        ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
