<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "deposits".
 *
 * @property int $id
 * @property int $client_id
 * @property string $rate
 * @property string $amount
 * @property int $created_at
 * @property string $description
 *
 * @property Clients $client
 */
class Deposits extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deposits';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'amount', 'rate'], 'required'],
            [['client_id', 'created_at'], 'integer'],
            [['amount'], 'number', 'min' => 0.01, 'max' => 999999999.99],
            [['rate',], 'number', 'min' => 0.01, 'max' => 99.99],
            [['description'], 'string', 'max' => 255],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clients::className(), 'targetAttribute' => ['client_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'ID Клиента',
            'rate' => 'Ставка',
            'amount' => 'Сумма',
            'created_at' => 'Дата создания',
            'description' => 'Описание',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Clients::className(), ['id' => 'client_id']);
    }
}
