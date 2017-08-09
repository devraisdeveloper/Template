<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TemplateCollection */

$this->title = 'Update Record For Table '.$adapter::tableName();

?>
<div class="template-collection-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('create-template-table-form', [
        'adapter' => $adapter,
        'tAttributes' => $tAttributes
    ]) ?>

</div>