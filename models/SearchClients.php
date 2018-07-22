<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Clients;

/**
 * SearchClients represents the model behind the search form of `app\models\Clients`.
 */
class SearchClients extends Clients
{
    public $date_of_birth_1;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_number', 'gender_id', 'date_of_birth'], 'integer'],
            [['name', 'surname'], 'safe'],
            [['date_of_birth_1'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Clients::find();

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
        if ($this->date_of_birth_1) {
            $this->date_of_birth = date("Y-m-d H:i:s", strtotime($this->date_of_birth_1));
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_number' => $this->id_number,
            'gender_id' => $this->gender_id,
            'date_of_birth' => $this->date_of_birth,
        ]);


        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'surname', $this->surname]);
        $query->with('gender');

        return $dataProvider;
    }
}
