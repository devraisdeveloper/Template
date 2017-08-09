<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $adapter app\models\ModelAdapter */

$this->title = $adapter::tableName();
?>

<div class="VIEW-TEMPLATE-TABLE">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update-template-table', 'table_name' => $adapter::tableName(), 'record_id' => $adapter->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete-template-table', 'table_name' => $adapter::tableName(), 'record_id' => $adapter->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Back to show table: '.$adapter::tableName(), ['show-table', 'table_name' => $adapter::tableName()], ['class' => 'btn btn-primary']) ?>
        
    </p>

    <?php 

    $columns[] = 'id';

    foreach($adapter->customDisplayAttributes() as $label => $fields){
        $columns[] =[
                        'label' => $label,
                        'value' => function($model) use ($fields){
                            $response = "";

                            foreach($fields as $field){
                                $response = $response." ".$model->{$field};
                            }
                            return $response;
                        }
                    ];
    }

    ?>

    <?= DetailView::widget([
        'model' => $adapter,
        'attributes' => $columns
    ]) ?>

</div>