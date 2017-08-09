<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ModelAdapter;
use app\helpers\SearchModelBuilder;
use app\helpers\TemplateParser;

class ModelAdapterSearch extends ModelAdapter
{

	public $tAttributes = [];

    public function setTableAttributes(array $tAttributes){
    	$this->tAttributes = $tAttributes;
       // parent::setTableAttributes($tAttributes);
    }

   /* public function __construct(){
        parent::setTableAttributes();
    }*/

    /**
     * @inheritdoc
     */
    public function rules()
    {
       /* return [
            [['id', 'Internal_company_ID', 'Uses_company_car'], 'integer'],
            [['First_name', 'Second_name', 'Date_of_birth', 'Department'], 'safe'],
        ];*/

        return (new SearchModelBuilder)->createSearchRules($this->tAttributes);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
       $query = ModelAdapter::find();

       /* $adapter = new ModelAdapter();
        $adapter->setTableAttributes($this->tAttributes);
        $query = $adapter->find();*/

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
      /*  $query->andFilterWhere([
            'id' => $this->id,
            'Date_of_birth' => $this->Date_of_birth,
            'Internal_company_ID' => $this->Internal_company_ID,
            'Uses_company_car' => $this->Uses_company_car,
        ]);

        $query->andFilterWhere(['like', 'First_name', $this->First_name])
            ->andFilterWhere(['like', 'Second_name', $this->Second_name])
            ->andFilterWhere(['like', 'Department', $this->Department]);*/

        foreach($this->tAttributes[TemplateParser::TABLE_FIELDS] as $field => $properties){

            if($properties[TemplateParser::FIELD_TYPE] == TemplateParser::INT_TYPE || $properties[TemplateParser::FIELD_TYPE] == TemplateParser::DATE_TYPE || $properties[TemplateParser::FIELD_TYPE] == TemplateParser::BOOLEAN_TYPE ){

                 $query->andFilterWhere([
                    $field => $this->{$field}
           /* 'id' => $this->id,
            'Date_of_birth' => $this->Date_of_birth,
            'Internal_company_ID' => $this->Internal_company_ID,
            'Uses_company_car' => $this->Uses_company_car,*/
          //  (new SearchModelBuilder)->filterIntValues($this->tAttributes)
                                ]);
            }
        }


    //    $query->andFilterWhere(
           /* 'id' => $this->id,
            'Date_of_birth' => $this->Date_of_birth,
            'Internal_company_ID' => $this->Internal_company_ID,
            'Uses_company_car' => $this->Uses_company_car,*/
           // (new SearchModelBuilder)->filterIntValues($this->tAttributes)
        	//					);

        foreach ($this->tAttributes[TemplateParser::TABLE_FIELDS] as $field => $properties) {

        	if($properties[TemplateParser::FIELD_TYPE] == TemplateParser::VARCHAR_TYPE){
        		$query->andFilterWhere(['like', $field, $this->{$field}]);
        		}
        }

       /* $query->andFilterWhere(['like', 'First_name', $this->First_name])
            ->andFilterWhere(['like', 'Second_name', $this->Second_name])
            ->andFilterWhere(['like', 'Department', $this->Department]);*/

/*$s = [
'f'=>'d',
'd'=>'ddd'
];
print_r($s);
            echo '<pre>';
        print_r($dataProvider->getModels());
        echo '</pre>';
        exit();*/

        return $dataProvider;
    }

}

?>