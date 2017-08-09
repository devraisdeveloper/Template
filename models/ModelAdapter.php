<?php

namespace app\models;

use app\helpers\ModelBuilder;
use app\helpers\TemplateParser;
use app\helpers\Router;
use yii\helpers\Json;
use app\models\TemplateCollection;
use yii\db\TableSchema;

use Yii;

class ModelAdapter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

   public $tAttributes = [];
   public static $tableName;

    public function setTableAttributes(array $tAttributes){
    	$this->tAttributes = $tAttributes;
    }

    public static function tableName()
    {
        return self::$tableName;
    }

    public function rules()
    {
    	return (new ModelBuilder())->createRules($this->tAttributes);
    }

    public function attributeLabels()
    {  
        if(empty($this->tAttributes)){

          $templates = TemplateCollection::find()->all();

            foreach($templates as $template){
              $decodedArray = Json::decode($template->json, true);

            if($decodedArray[TemplateParser::TABLE_NAME] == ModelAdapter::tableName()){
              return (new ModelBuilder())->createAttributeLabels($decodedArray);
              }

             }

        throw new NotFoundHttpException('Template for this table was deleted.');
         }
   
        return (new ModelBuilder())->createAttributeLabels($this->tAttributes);
    }

    public function customDisplayAttributes(){

          if(empty($this->tAttributes)){

          $templates = TemplateCollection::find()->all();

            foreach($templates as $template){
              $decodedArray = Json::decode($template->json, true);

            if($decodedArray[TemplateParser::TABLE_NAME] == ModelAdapter::tableName()){
              return (new ModelBuilder())->createDisplayAttributes($decodedArray);
              }

             }

        throw new NotFoundHttpException('Template for this table was deleted.');
         }

         return (new ModelBuilder())->createDisplayAttributes($this->tAttributes);
    }
   
}

