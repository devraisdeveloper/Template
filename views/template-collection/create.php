<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TemplateCollection */

$this->title = 'Create Template Collection';
$this->params['breadcrumbs'][] = ['label' => 'Template Collections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="template-collection-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
