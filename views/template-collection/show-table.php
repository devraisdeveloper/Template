<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\helpers\TemplateParser;
use app\models\ModelAdapter;
use app\helpers\Router;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TemplateCollectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $tAttributes[TemplateParser::TABLE_NAME];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="show-table">

   <?php $adapter = new ModelAdapter();
    $adapter->setTableAttributes($tAttributes); ?>

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Record', ['create-template-table', 'table_name' => $tAttributes[TemplateParser::TABLE_NAME]], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    $columns[] = ['class' => 'yii\grid\SerialColumn'];
    $columns[] = 'id';
    foreach($adapter->attributeLabels() as $label => $field){

        $columns[] = [
                        'class' => yii\grid\DataColumn::className(),
                        'label' => $label,
                        'attribute' => $field,
                        'format'=>'html',
                        'value' => function($model) use ($field){

                            return $model->{$field};
                                }      
                     ];
                     
    }
    $columns[] = [
    'class' => 'yii\grid\ActionColumn',
    'header' => 'Actions',
    'headerOptions' => ['style' => 'color:#337ab7'],
    'template' => '{view}{update}{delete}',
        'buttons' => [
            'view' => function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view-template-table', 'table_name' => $model::tableName(), 'record_id' => $model->id], [
                            'title' => Yii::t('app', 'lead-view'),
                ]);
            },

            'update' => function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update-template-table', 'table_name' => $model::tableName(), 'record_id' => $model->id], [
                            'title' => Yii::t('app', 'lead-update'),
                ]);
            },
            'delete' => function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete-template-table', 'table_name' => $model::tableName(), 'record_id' => $model->id], [
                            'title' => Yii::t('app', 'lead-delete'),
                ]);
            }

          ]
    ];


    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => 
            $columns
        ,
    ]); ?>
</div>