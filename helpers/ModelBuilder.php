<?php 

namespace app\helpers;

use app\models\TemplateCollection;
use app\helpers\Json;

class ModelBuilder {

	public function setRequired(array $tAttributes){

		foreach($tAttributes[TemplateParser::TABLE_FIELDS] as $field => $properties){
            $requiredFields[] = $field;
        }

        $required = [	
        				$requiredFields,
        				'required'
        			];

        return $required;
	}

	public function setVarchar(array $tAttributes)
	{
		$varchar = [];
		foreach ($tAttributes[TemplateParser::TABLE_FIELDS] as $field => $properties) {
			if($properties[TemplateParser::FIELD_TYPE] == TemplateParser::VARCHAR_TYPE){
				$varchar = [
								$field,
								TemplateParser::STRING_TYPE,
								'min'=>$properties[TemplateParser::FIELD_RULES]['min'],
								'max'=>$properties[TemplateParser::FIELD_RULES]['max']

						   ];
			}
		}

		return $varchar;
	}


	public function setInteger(array $tAttributes)
	{
		$integer = [];
		foreach ($tAttributes[TemplateParser::TABLE_FIELDS] as $field => $properties) {
			if($properties[TemplateParser::FIELD_TYPE] == TemplateParser::INT_TYPE){
				$integer = [
								$field,
								TemplateParser::NUMBER_TYPE,
								'min'=>$properties[TemplateParser::FIELD_RULES]['min'],
								'max'=>$properties[TemplateParser::FIELD_RULES]['max']
						   ];
			}
		}

		return $integer;
	}


	public function setDate(array $tAttributes)
	{
		$date = [];
		foreach ($tAttributes[TemplateParser::TABLE_FIELDS] as $field => $properties) {
			if($properties[TemplateParser::FIELD_TYPE] == TemplateParser::DATE_TYPE){
				$date = [
								$field,
								'safe'
								//'validateDate'
						   ];
			}
		}

		return $date;
	}


	public function setBoolean(array $tAttributes)
	{
		$boolean = [];
		foreach ($tAttributes[TemplateParser::TABLE_FIELDS] as $field => $properties) {
			if($properties[TemplateParser::FIELD_TYPE] == TemplateParser::BOOLEAN_TYPE){
				$boolean = [
								$field,
								TemplateParser::BOOLEAN_TYPE
						   ];
			}
		}

		return $boolean;
	}


	public function setEnum(array $tAttributes)
	{
		$enum = [];
		foreach ($tAttributes[TemplateParser::TABLE_FIELDS] as $field => $properties) {
			if($properties[TemplateParser::FIELD_TYPE] == TemplateParser::ENUM_TYPE){
				$enum = [
								$field,
								TemplateParser::STRING_TYPE
						   ];
			}
		}

		return $enum;
	}
	
	public function createRules(array $tAttributes)
	{
		$finishedRules[] = $this->setRequired($tAttributes);

		/*foreach ($tAttributes[TemplateParser::TABLE_FIELDS] as $field => $properties) {
				if($properties[TemplateParser::FIELD_TYPE] == TemplateParser::VARCHAR_TYPE){
					$finishedRules[] = $this->setVarchar($tAttributes);

				}
				if ($properties[TemplateParser::FIELD_TYPE] == TemplateParser::INT_TYPE) {
					$finishedRules[] = $this->setInteger($tAttributes);

				}
				if ($properties[TemplateParser::FIELD_TYPE] == TemplateParser::DATE_TYPE) {
					$finishedRules[] = $this->setDate($tAttributes);
				}
				if ($properties[TemplateParser::FIELD_TYPE] == TemplateParser::ENUM_TYPE) {
					$finishedRules[] = $this->setEnum($tAttributes);
				}
				if ($properties[TemplateParser::FIELD_TYPE] == TemplateParser::BOOLEAN_TYPE) {
					$finishedRules[] = $this->setBoolean($tAttributes);
				}

			}*/


		$finishedRules[] = $this->setVarchar($tAttributes);

		$finishedRules[] = $this->setInteger($tAttributes);

		$finishedRules[] = $this->setDate($tAttributes);

		$finishedRules[] = $this->setEnum($tAttributes);

		$finishedRules[] = $this->setBoolean($tAttributes);

		// Unset any empty rules
		foreach($finishedRules as $key => $rule){
			if(empty($rule)) unset($finishedRules[$key]);
		}

		return $finishedRules;

	}

	public function createAttributeLabels(array $tAttributes)
	{
		/*foreach($tAttributes[TemplateParser::TABLE_DISPLAY_FORMAT] as $field => $label){
           foreach ($label as $key => $value) {
           	$labels[$field] = $value;
           }

        }

        return $labels;*/

        foreach($tAttributes[TemplateParser::TABLE_FIELDS] as $field => $properties){
           $labels[$field] = $field;
        }

        return $labels;

	}

	public function createDisplayAttributes(array $tAttributes)
	{
		return $tAttributes[TemplateParser::TABLE_DISPLAY_FORMAT];
	}
}

?>

