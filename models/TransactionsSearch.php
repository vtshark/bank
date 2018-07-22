<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Transactions;

/**
 * TransactionsSearch represents the model behind the search form of `app\models\Transactions`.
 */
class TransactionsSearch extends Transactions
{
    public $month;
    public $year;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'deposit_id', 'transaction_type_id', 'created_at', 'month', 'year'], 'integer'],
            [['amount'], 'number'],
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
        $query = Transactions::find();

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
        $query->andFilterWhere([
            'id' => $this->id,
            'deposit_id' => $this->deposit_id,
            'transaction_type_id' => $this->transaction_type_id,
            'created_at' => $this->created_at,
            'amount' => $this->amount,
        ]);
        $query->with('transactionType');

        return $dataProvider;
    }

    public function searchForReport($params)
    {
        $out_arr['bank_profit'] = $this->getProfitData();
        $out_arr['deposit_stats'] = $this->getAgeStatsData();
        //echo "<pre>" . print_r($out_arr,1) . "</pre>"; die;
        return $out_arr;
    }

    private function getProfitData()
    {
        $dataArr = Yii::$app->db->createCommand('
            SELECT 
            transaction_type_id,
            tr_types.alias as transaction_type_alias,
            month(FROM_UNIXTIME(created_at)) as month, 
            year(FROM_UNIXTIME(created_at)) as year,  
            sum(amount) AS amount_sum 
            FROM transactions tr
            INNER JOIN transaction_types tr_types on tr.transaction_type_id = tr_types.id 
            GROUP BY year, month, transaction_type_id
            ORDER BY year, month, transaction_type_id')
            ->queryAll();

        // convert array to view template
        $out_arr = [];
        foreach ($dataArr as $item) {
            $date = $item['month'] . "." . $item['year'];
            $out_arr[$date][$item['transaction_type_alias']]['amount_sum'] = $item['amount_sum'];
        }

        return $out_arr;
    }

    private function getAgeStatsData()
    {
        $dataArr[1] = Yii::$app->db->createCommand('
            SELECT sum(d.amount) as amount_sum, count(d.id) as count
            FROM deposits d
            INNER JOIN clients c on c.id = d.client_id
            WHERE 
            (TIMESTAMPDIFF(YEAR, c.date_of_birth, CURDATE()) >= 18 and 
             TIMESTAMPDIFF(YEAR, c.date_of_birth, CURDATE()) < 25)
            ')->queryAll()[0];

        $dataArr[2] = Yii::$app->db->createCommand('
            SELECT sum(d.amount) as amount_sum, count(d.id) as count
            FROM deposits d
            INNER JOIN clients c on c.id = d.client_id
            WHERE 
            (TIMESTAMPDIFF(YEAR, c.date_of_birth, CURDATE()) >= 25 and 
             TIMESTAMPDIFF(YEAR, c.date_of_birth, CURDATE()) < 50)
            ')->queryAll()[0];

        $dataArr[3] = Yii::$app->db->createCommand('
            SELECT sum(d.amount) as amount_sum, count(d.id) as count
            FROM deposits d
            INNER JOIN clients c on c.id = d.client_id
            WHERE 
            (TIMESTAMPDIFF(YEAR, c.date_of_birth, CURDATE()) >= 50)
            ')->queryAll()[0];

        return $dataArr;

    }
}
