<?php

namespace app\controllers;

use Yii;
use app\models\TemplateCollection;
use app\models\TemplateCollectionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\helpers\TableBuilder;
use app\models\ModelAdapter;
use app\helpers\TemplateParser;
use yii\helpers\Json;
use app\helpers\T;
use app\helpers\Router;
use app\models\ModelAdapterSearch;

/**
 * TemplateCollectionController implements the CRUD actions for TemplateCollection model.
 */
class TemplateCollectionController extends Controller
{
   // private $current_adapter;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TemplateCollection models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TemplateCollectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TemplateCollection model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findTemplateModel($id),
        ]);
    }

    /**
     * Creates a new TemplateCollection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TemplateCollection();
          
        if(Yii::$app->request->isAjax && $model->load($_POST)) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        $dbTransaction = Yii::$app->db->beginTransaction();

        if ($model->load(Yii::$app->request->post())) { 
            try{
            $tableBuilder = new TableBuilder();
            $model->save();
            $tableBuilder->createTable($model->json);
            $dbTransaction->commit();
            return $this->redirect(['view', 'id' => $model->id]);
        }catch (\Exception $e) {
                    $dbTransaction->rollBack();
                    throw $e;
                }

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionShowTable(string $table_name)
    {
       $templates = TemplateCollection::find()->all();

       foreach($templates as $template){
       $decodedArray = Json::decode($template->json, true);


       if($decodedArray[TemplateParser::TABLE_NAME] == $table_name){

            $adapter = new ModelAdapter();
            $adapter::$tableName = $decodedArray[TemplateParser::TABLE_NAME];
            $adapter->setTableAttributes($decodedArray);

            $searchModel = new ModelAdapterSearch();
            $searchModel->setTableAttributes($decodedArray);
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('show-table', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tAttributes' => $decodedArray
              ]);

        }

        }

        throw new NotFoundHttpException('Template for this table was deleted.');
    }

    public function actionViewTemplateTable(string $table_name, int $record_id)
    {
        $adapter = Router::getNeededAdapter($table_name);
        $record = $adapter::findOne($record_id);

        return $this->render('view-template-table', [ 'adapter' => $record]
        );
    }

    public function actionUpdateTemplateTable(string $table_name, int $record_id)
    {
        $tAttributes = Router::getNeededAttributes($table_name);
        $adapter = Router::getNeededAdapter($table_name);
        $adapter = $adapter::findOne($record_id);
        $adapter->tAttributes = $tAttributes;

        if ($adapter->load(Yii::$app->request->post()) && $adapter->save()) {
            return $this->redirect(['view-template-table', 'table_name' => $adapter::tableName(), 'record_id' => $adapter->id]);
        } else {
            return $this->render('update-template-table', ['adapter' => $adapter, 'tAttributes' => $tAttributes]);
        }
    }

     public function actionDeleteTemplateTable(string $table_name, int $record_id)
    {
        $adapter = Router::getNeededAdapter($table_name);
        $record = $adapter::findOne($record_id);
        $record->delete();
        return $this->redirect(['show-table','table_name' => $adapter::tableName()]);
    }

    public function actionCreateTemplateTable(string $table_name){

        $tAttributes = Router::getNeededAttributes($table_name);
        $adapter = Router::getNeededAdapter($table_name);

        if($adapter->load(Yii::$app->request->post())){
            $adapter->save();
            return $this->redirect(['view-template-table', 'table_name' => $adapter::tableName(), 'record_id' => $adapter->id]);
        }else{
            return $this->render('create-template-table', ['adapter' => $adapter, 'tAttributes' => $tAttributes]);
            }

    }

    /**
     * Updates an existing TemplateCollection model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findTemplateModel($id);

        $model->setUpdateScenario();

        $tableName = Router::getTemplateTableName($model);
        $oldAttributes = Router::getNeededAttributes($tableName);

         if(Yii::$app->request->isAjax && $model->load($_POST)) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

        $newAttributes = Router::getNeededAttributes($tableName);

        $tableBuilder = new TableBuilder();
        $tableBuilder->updateTable($oldAttributes, $newAttributes);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TemplateCollection model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findTemplateModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TemplateCollection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TemplateCollection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findTemplateModel($id)
    {
        if (($model = TemplateCollection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
