<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TemplateCollection */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="template-collection-form">

    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>

    <?=$form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'json')->textarea(['rows' => 15]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
