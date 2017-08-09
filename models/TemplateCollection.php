<?php

namespace app\models;

use Yii;
use yii\validators\InlineValidator;
use app\helpers\f;
use app\helpers\TemplateParser;

/**
 * This is the model class for table "TemplateCollection".
 *
 * @property integer $id
 * @property string $name
 * @property string $json
 */
class TemplateCollection extends \yii\db\ActiveRecord
{

    private $scenario = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'TemplateCollection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'json'], 'required'],
            [['json'], 'string'],
            [['name'], 'string', 'max' => 30],
            [['json'], 'validateJson']
        ];
    }

    public function validateJson($attribute, $params, $validator)
    {
       $parser = new TemplateParser();
       $parser->setUpdate($this->getUpdateScenario());
       $status =  $parser->validateStructure($this->json);

        if($status != 'cool'){
           $validator->addError($this, $attribute, $status);
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'json' => 'Json',
        ];
    }

    public function setUpdateScenario()
    {
        $this->scenario = true;
    }

    public function getUpdateScenario()
    {
        return $this->scenario;
    }
}
