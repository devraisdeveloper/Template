<?php

namespace app\helpers;

use app\models\TemplateCollection;
use yii\helpers\Json;
use yii\base\DynamicModel;
use app\helpers\TemplateParser;

use Yii;

class TableBuilder {

    public function setValue(array $properties)
    {
        $string = " ( ";

        if(!isset($properties[TemplateParser::FIELD_VALUE])){
            return $properties[TemplateParser::FIELD_TYPE]." NOT NULL,";
        }

        if(is_array($properties[TemplateParser::FIELD_VALUE])){

            foreach ($properties[TemplateParser::FIELD_VALUE] as $value){
            $string = $string."'".$value."',";
            }

            $string = rtrim($string,",");

            return $properties[TemplateParser::FIELD_TYPE].$string.") NOT NULL,";
        }

         if($properties[TemplateParser::FIELD_TYPE] == TemplateParser::BOOLEAN_TYPE){
            return $properties[TemplateParser::FIELD_TYPE]." default ".$properties[TemplateParser::FIELD_VALUE]." NOT NULL,";
        }

        if($properties[TemplateParser::FIELD_TYPE] == (TemplateParser::INT_TYPE || TemplateParser::VARCHAR_TYPE) ){
            return $properties[TemplateParser::FIELD_TYPE]." ( ".$properties[TemplateParser::FIELD_VALUE]." ) NOT NULL,";
        }

        if($properties[TemplateParser::FIELD_TYPE] == TemplateParser::DATE_TYPE ){
            return $properties[TemplateParser::FIELD_TYPE]." ( ".$properties[TemplateParser::FIELD_VALUE]." ) NOT NULL,";
        }

    }
    
    public function createTable(string $json){
        
        $decodedArray = Json::decode($json, true);

       /* echo '<pre>';
        print_r(TemplateParser::TABLE_NAME);
        echo '</pre>';
        die();  */  
        
        // Get table attributes  
        foreach ($decodedArray[TemplateParser::TABLE_FIELDS] as $field => $properties){
            $tAttributes[$field] = $properties;
        }
        
        // Create table based on json       
        // Create main part of query
        $query = "CREATE TABLE `".$decodedArray[TemplateParser::TABLE_NAME]."` ( `id` INT NOT NULL AUTO_INCREMENT, ";

        // Assigne all the fields to query
        foreach($tAttributes as $field => $properties){
            $query = $query." "."`".$field."`"." ".$this->setValue($properties);
        }

        // Close query and add a PRIMARY KEY
        $query = $query." PRIMARY KEY (`id`))";
       // $post = Yii::$app->db->createCommand($query)->execute();
        Yii::$app->db->createCommand($query)->execute();                
    }

    public function executeAddColumns(array $newFields, string $tableName)
    {
        foreach($newFields as $field => $properties){
        $query = "ALTER TABLE "."`".$tableName."`"." ADD "."`".$field."` ".$this->setValue($properties);

        // remove coma
        $query = rtrim($query,",");

        Yii::$app->db->createCommand($query)->execute(); 
        }
        

        //$query = "ALTER TABLE "."`".$tableName."`"." ADD ";ALTER TABLE `fff` ADD `another_name` VARCHAR(45) NOT NULL
    }

    public function executeDeleteColumns(array $deleteFields, string $tableName)
    {
        foreach($deleteFields as $field){
            $query = "ALTER TABLE "."`".$tableName."`"." DROP COLUMN "."`".$field."`";
        Yii::$app->db->createCommand($query)->execute(); 
        }
    }

    public function updateTable(array $oldAttributes, array $newAttributes)
    {
        $newFields = [];
        $deleteFields = [];

       /* echo '<pre>';
        print_r($oldAttributes);
        echo '</pre>';

        echo 'nnnnnnnnnnnnnnnnnnnnnnnn'."\n";

        echo '<pre>';
        print_r($newAttributes);
        echo '</pre>';

        die();*/


        // fields for update
        foreach($newAttributes[TemplateParser::TABLE_FIELDS] as $field => $properties){
            if(!in_array($field, array_keys($oldAttributes[TemplateParser::TABLE_FIELDS]))){
                $newFields[$field] = $properties; 
            }
        }

       /* echo '<pre>';
        print_r($newFields);
        echo '</pre>';

        die();*/

        foreach($oldAttributes[TemplateParser::TABLE_FIELDS] as $field => $properties){
            if(!in_array($field, array_keys($newAttributes[TemplateParser::TABLE_FIELDS]))){
                $deleteFields[] = $field; 
            }
        }

      /*  echo '<pre>';
        print_r($deleteFields);
        echo '</pre>';

        die();*/

        if(!empty($newFields)){
            $this->executeAddColumns($newFields, $newAttributes[TemplateParser::TABLE_NAME]);
            }

        if(!empty($deleteFields)){
            $this->executeDeleteColumns($deleteFields, $oldAttributes[TemplateParser::TABLE_NAME]);
        }
    }
    
}
