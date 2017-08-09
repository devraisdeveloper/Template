<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $adapter app\models\ModelAdapter 
@param array $tAttributes
*/

$this->title = 'Create Record For Table '.$adapter::tableName();

?>
<div class="template-collection-create-template-table">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('create-template-table-form', [
        'adapter' => $adapter,
        'tAttributes' => $tAttributes
    ]) ?>

</div>