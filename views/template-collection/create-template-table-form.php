<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\helpers\TemplateParser;
use app\helpers\Router;

/* @var $this yii\web\View */
/* @var $adapter app\models\ModelAdapter */
/* @var $form yii\widgets\ActiveForm 
@param array $tAttributes
*/
?>

<div class="worker-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
            foreach($tAttributes[TemplateParser::TABLE_FIELDS] as $field => $properties){
                if($properties[TemplateParser::FIELD_TYPE] == TemplateParser::VARCHAR_TYPE || $properties[TemplateParser::FIELD_TYPE] == TemplateParser::INT_TYPE || $properties[TemplateParser::FIELD_TYPE] == TemplateParser::DATE_TYPE){
                   echo $form->field($adapter, $field)->textInput(); 
                }
                if($properties[TemplateParser::FIELD_TYPE] == TemplateParser::ENUM_TYPE ){

                  echo $form->field($adapter, $field)->dropDownList(Router::getTemplateDropDownOptions($tAttributes, $field), ['prompt' => 'Open Drop Down']);
                }
                if($properties[TemplateParser::FIELD_TYPE] == TemplateParser::BOOLEAN_TYPE ){
                   echo  $form->field($adapter, $field)->checkbox(); 
                }
            }
     ?>

    <div class="form-group">
        <?= Html::submitButton($adapter->isNewRecord ? 'Create' : 'Update', ['class' => $adapter->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>