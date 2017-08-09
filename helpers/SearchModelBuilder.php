<?php

namespace app\helpers;

use app\models\TemplateCollection;
use app\helpers\Json;

class SearchModelBuilder
{
	public function setInteger(array $tAttributes){

	$foundFields[] = 'id';

		foreach($tAttributes[TemplateParser::TABLE_FIELDS] as $field => $properties){

            if($properties[TemplateParser::FIELD_TYPE] == TemplateParser::INT_TYPE || $properties[TemplateParser::FIELD_TYPE] == TemplateParser::BOOLEAN_TYPE) {
				$foundFields[] = $field;
			}
        }

        $integer = [	
        			$foundFields,
        			'integer'
        		];

        return $integer;
	}

	public function setSafe(array $tAttributes){

		foreach($tAttributes[TemplateParser::TABLE_FIELDS] as $field => $properties){

            if($properties[TemplateParser::FIELD_TYPE] == TemplateParser::VARCHAR_TYPE || $properties[TemplateParser::FIELD_TYPE] == TemplateParser::DATE_TYPE || $properties[TemplateParser::FIELD_TYPE] == TemplateParser::ENUM_TYPE) {
				$foundFields[] = $field;
			}
        }

        $safe = [	
        			$foundFields,
        			'safe'
        		];

        return $safe;
	}

	public function createSearchRules(array $tAttributes)
	{
		$finishedRules[] = $this->setSafe($tAttributes);

		$finishedRules[] = $this->setInteger($tAttributes);

		return $finishedRules;

	}

	public function filterIntValues(array $tAttributes)
	{
		$searchQuery = [];

		$searchQuery['id'] = 'id';

		foreach ($tAttributes[TemplateParser::TABLE_FIELDS] as $field => $properties) {

			if($properties[TemplateParser::FIELD_TYPE] == TemplateParser::INT_TYPE || $properties[TemplateParser::FIELD_TYPE] == TemplateParser::DATE_TYPE || $properties[TemplateParser::FIELD_TYPE] == TemplateParser::BOOLEAN_TYPE ){
				$searchQuery[$field] = $field;
    			}
    	}

    	return $searchQuery;	
	}

}

?>