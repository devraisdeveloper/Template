<?php 

namespace app\helpers;

use app\models\ModelAdapter;
use app\helpers\Router;

class TemplateParser {
    
CONST TABLE_NAME = "table_name";
CONST TABLE_FIELDS = "table_fields";
CONST TABLE_DISPLAY_FORMAT = "table_display_format";
CONST FIELD_TYPE = "type";
CONST FIELD_VALUE ="value";
CONST FIELD_RULES = "rules";

CONST JSON_CORRUPT = "Received corrupted json file !!!";
CONST JSON_STRUCTURE_ERROR = "Json was created with errors in its structure !!!";

CONST JSON_RESTRICTED_TYPES = "You added restricted types to json template !!!";
CONST JSON_ABSENT_FIELD_TYPE = "Field type was not added !!!";
CONST JSON_ABSENT_FIELD_VALUE = "Field value was not added !!!";
CONST JSON_ABSENT_FIELD_RULES = "Rules value was not added !!!";
CONST JSON_ABSENT_RULES_DETAILS = "Max and Min were not set !!!";

CONST JSON_EMPTY_FIELDS = "Fields for table creation were not added !!!";
CONST JSON_EMPTY_DISPLAY_FIELDS = "Display fields were not set !!!";
CONST JSON_DISPLAY_FIELDS_OVERLOAD = "Amount of display fields exceeds number of table fields !!!";

CONST NOT_INT= "Value must be a positive integer !!!";
CONST NOT_ENUM= "Drop down must be an array and have at least 1 value !!!";
CONST NOT_DATE= "Date entered incorrectly !!! Format must be Format: YYYY-MM-DD";
CONST NOT_MAX_MIN = "MIN and MAX have errors !!!" ;
CONST NOT_BOOLEAN = "Boolean value must be 0 or 1 !!!" ;
CONST DISPLAY_FORMAT_LABELS_MISMATCH = "Field and label numbers are not the same !!!";
CONST TOO_MUCH_FIELDS_FOR_LABEL = "You are assigning to much table fields to your custom labels OR you have syntax errors in your fields in The Display option !!!";
CONST DUPLICATE_NAME = "This table name already exists !!!";

CONST VARCHAR_TYPE =  "varchar";
CONST STRING_TYPE =  "string";
CONST INT_TYPE =  "int";
CONST DATE_TYPE = "date";
CONST ENUM_TYPE =  "enum";
CONST BOOLEAN_TYPE = "boolean";
CONST NUMBER_TYPE =  "number";

    private $update = false;

    public function setUpdate(bool $status){
    	$this->update = $status;
    }

    public function getUpdate(){
    	return $this->update;
    }

    public function testDisplayFormats(array $decodedTemplate){
    	$tableFields = [];
    	$displayFields = [];
    	$result = [];

    	foreach($decodedTemplate[TemplateParser::TABLE_FIELDS] as $field => $property){
    		$tableFields[] = $field;
    	}

    	foreach($decodedTemplate[TemplateParser::TABLE_DISPLAY_FORMAT] as $label => $property){
    		foreach($property as $field){
				$displayFields[] = $field;
			}
    	}

    	$result = array_diff($tableFields, $displayFields);

    	if(empty($result)) return true;

    	return false;
    }

	public function compareFieldsLabels(array $decodedTemplate)
	{
		$formatFields = 0;
		$displayFields = 0;

		foreach($decodedTemplate[TemplateParser::TABLE_FIELDS] as $field => $property){
			$formatFields++;
		}

		foreach($decodedTemplate[TemplateParser::TABLE_DISPLAY_FORMAT] as $label => $property){
			foreach($property as $field){
				$displayFields++;
			}
		}

		if($formatFields >= $displayFields){
			return true;
		}

		return false;
	}

	public function validateStructure(string $jsonTemplate)
	{
		$decodedTemplate = json_decode($jsonTemplate, true);
		$allowedTypes = [
			TemplateParser::VARCHAR_TYPE,
			TemplateParser::INT_TYPE,
			TemplateParser::DATE_TYPE,
			TemplateParser::ENUM_TYPE,
			TemplateParser::BOOLEAN_TYPE
		];
                
              //  return $decodedTemplate;

		$restrictedTypes = [];

		// Do we have a valid JSON ?
		if(json_last_error() != JSON_ERROR_NONE ){
			return TemplateParser::JSON_CORRUPT;
		}

		// Need to verify all the keys from the JSON template file		
		if(!isset($decodedTemplate[TemplateParser::TABLE_NAME]) || !isset($decodedTemplate[TemplateParser::TABLE_FIELDS]) || !isset($decodedTemplate[TemplateParser::TABLE_DISPLAY_FORMAT])){
				return TemplateParser::JSON_STRUCTURE_ERROR;
		}

		// Do we have allowed types ?
		foreach($decodedTemplate[TemplateParser::TABLE_FIELDS] as $field => $property){
			
			if(!in_array($property[TemplateParser::FIELD_TYPE], $allowedTypes)){
				$restrictedTypes[] = $property[TemplateParser::FIELD_TYPE] . " (".$field.")";
			}
		}

		if(count($restrictedTypes) > 0){
			return TemplateParser::JSON_RESTRICTED_TYPES;
		}

		// Check if table already exists
		if(Router::isDuplicateTableName($decodedTemplate) == false && $this->getUpdate() == false){
			return TemplateParser::DUPLICATE_NAME;
		}

		// YOu can't change table name when you update json
		// Table is already created

		if(Router::isDuplicateTableName($decodedTemplate) == true && $this->getUpdate() == true){
			return 'Cant change table name during update since table already exists !!!';
		}


		// Need to loop through every field key 
		// Every field needs to be assisgned to array consisting of "FIELD_TYPE" and "FIELD_VALUE"
			foreach($decodedTemplate[TemplateParser::TABLE_FIELDS] as $field => $property){

				// We must make sure that field type is set
				if(!isset($property[TemplateParser::FIELD_TYPE])){
					return TemplateParser::JSON_ABSENT_FIELD_TYPE . "(".$field.")";
				}

				//If type STRING or INT its lenght must be set
				// more than 0 and must be an integer
				if($property[TemplateParser::FIELD_TYPE] == TemplateParser::VARCHAR_TYPE || $property[TemplateParser::FIELD_TYPE] == TemplateParser::INT_TYPE){
					if(!isset($property[TemplateParser::FIELD_VALUE])){
						return TemplateParser::JSON_ABSENT_FIELD_VALUE . "(".$field.")";
					}

					if(!is_numeric($property[TemplateParser::FIELD_VALUE]) || $property[TemplateParser::FIELD_VALUE] <= 0){
						return TemplateParser::NOT_INT . "(".$field.")";
					}

					//If type STRING or INT its rules must be set
					// Rules defining MAX and MIN are for STRING and INT formats
					// Range must not less than 1 
					if(!isset($property[TemplateParser::FIELD_RULES])){
						return TemplateParser::JSON_ABSENT_FIELD_RULES . "(".$field.")";
					}

					if(!isset($property[TemplateParser::FIELD_RULES]['min']) || !isset($property[TemplateParser::FIELD_RULES]['max'])){
						return TemplateParser::JSON_ABSENT_RULES_DETAILS;
					}

					if(!is_numeric($property[TemplateParser::FIELD_RULES]['min']) || !is_numeric($property[TemplateParser::FIELD_RULES]['max']) || $property[TemplateParser::FIELD_RULES]['min'] < 1 || $property[TemplateParser::FIELD_RULES]['min'] >= $property[TemplateParser::FIELD_RULES]['max'] ){
						return TemplateParser::NOT_MAX_MIN . "(".$field.")";
					}
				}

				//If type ENUM its must be an ARRAY and have at least one value
				if($property[TemplateParser::FIELD_TYPE] == TemplateParser::ENUM_TYPE){
					if(!isset($property[TemplateParser::FIELD_VALUE])){
						return TemplateParser::JSON_ABSENT_FIELD_VALUE . "(".$field.")";
					}

					if(!is_array($property[TemplateParser::FIELD_VALUE]) || (count($property[TemplateParser::FIELD_VALUE]) <= 0)){
						return TemplateParser::NOT_ENUM . "(".$field.")";
					}
				}

				//If type DATE its value must be set
				//and format must validated
			/*	if($property[TemplateParser::FIELD_TYPE] == TemplateParser::DATE_TYPE){
					if(!isset($property[TemplateParser::FIELD_VALUE])){
						return TemplateParser::JSON_ABSENT_FIELD_VALUE . "(".$field.")";
					}

					$date = \DateTime::createFromFormat('Y-m-j', $property[TemplateParser::FIELD_VALUE]);
					if($date == false){
						return TemplateParser::NOT_DATE;
					}
				}*/

				//If type Boolea its value must be set
				//with 1 or 0
				if($property[TemplateParser::FIELD_TYPE] == TemplateParser::BOOLEAN_TYPE){
					if(!isset($property[TemplateParser::FIELD_VALUE])){
						return TemplateParser::JSON_ABSENT_FIELD_VALUE . "(".$field.")";
					}

					if(!is_numeric($property[TemplateParser::FIELD_VALUE]) || !(0 <= $property[TemplateParser::FIELD_VALUE] && $property[TemplateParser::FIELD_VALUE] <= 1)){
						return TemplateParser::NOT_BOOLEAN;
					}
				}

			}

			if($this->compareFieldsLabels($decodedTemplate) == false){
				return TemplateParser::DISPLAY_FORMAT_LABELS_MISMATCH;
			}

			if($this->testDisplayFormats($decodedTemplate) == false){
				return TemplateParser::TOO_MUCH_FIELDS_FOR_LABEL;
			}

			return 'cool';
	}

}

?>