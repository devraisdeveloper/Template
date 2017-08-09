<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use app\helpers\Router;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TemplateCollectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Template Collections';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="template-collection-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Template Collection', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
          //  'json:ntext',
             [
                'label' => 'See table',
                'format'=> 'raw',
                'value' => function($model) {
               return Html::a('Show table', ['show-table', 'table_name' => Router::getTemplateTableName($model)],['class'=>'btn btn-success']);
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);

    ?>

   <?php  $this->registerJsFile('@web/js/support.js');  

?>
</div>
