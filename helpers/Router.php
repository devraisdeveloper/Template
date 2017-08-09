<?php

namespace app\helpers;

use app\models\TemplateCollection;
use app\models\ModelAdapter;
use app\helpers\TemplateParser;
use yii\helpers\Json;

class Router {
	
	public static function getTemplateTableName(TemplateCollection $template)
	{
		$tAttributes = Json::decode($template->json, true);
		return $tAttributes[TemplateParser::TABLE_NAME];
		
	}

	public static function getTemplateDropDownOptions(array $tAttributes, string $dropField)
	{

		foreach($tAttributes[TemplateParser::TABLE_FIELDS] as $field => $properties){
			if($field == $dropField){
				return array_combine($properties[TemplateParser::FIELD_VALUE], $properties[TemplateParser::FIELD_VALUE]);
			}
		}
	}

	public static function getNeededAttributes(string $table_name)
	{
	   $templates =  TemplateCollection::find()->all();

       foreach($templates as $template){
          $decodedArray = Json::decode($template->json, true);

       		if($decodedArray[TemplateParser::TABLE_NAME] == $table_name){
            return $decodedArray;
       		}

       }

        throw new NotFoundHttpException('The requested template was deleted !!!');
	}

	public static function getNeededAdapter(string $table_name)
	{
	   $templates =  TemplateCollection::find()->all();
	   $neededTemplate = [];

       foreach($templates as $template){
          $decodedArray = Json::decode($template->json, true);

       		if($decodedArray[TemplateParser::TABLE_NAME] == $table_name){
            	$neededTemplate = $decodedArray;
       		}

       }

       if(empty($neededTemplate)){
       	  throw new NotFoundHttpException('The requested template was deleted !!!');
       }

        $adapter = new ModelAdapter();
        $adapter::$tableName = $neededTemplate[TemplateParser::TABLE_NAME];
        $adapter->setTableAttributes($neededTemplate);

        return $adapter;
	}

  public static function isDuplicateTableName(array $decodedTemplate)
  {
     $templates =  TemplateCollection::find()->all();

       foreach($templates as $template){
          $decodedArray = Json::decode($template->json, true);

          if($decodedArray[TemplateParser::TABLE_NAME] == $decodedTemplate[TemplateParser::TABLE_NAME]){
              return false;
          }

       }

        return true;
  }

}

?>