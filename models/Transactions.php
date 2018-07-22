<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "transactions".
 *
 * @property int $id
 * @property int $deposit_id
 * @property int $transaction_type_id
 * @property int $created_at
 * @property string $amount
 *
 * @property Deposits $deposit
 * @property TransactionTypes $transactionType
 */
class Transactions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deposit_id', 'transaction_type_id', 'created_at'], 'integer'],
            [['amount'], 'number'],
            [['deposit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Deposits::className(), 'targetAttribute' => ['deposit_id' => 'id']],
            [['transaction_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => TransactionTypes::className(), 'targetAttribute' => ['transaction_type_id' => 'id']],
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
            'deposit_id' => 'Deposit ID',
            'transaction_type_id' => 'Тип тразакции',
            'created_at' => 'Дата создания',
            'amount' => 'Сумма',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposit()
    {
        return $this->hasOne(Deposits::className(), ['id' => 'deposit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionType()
    {
        return $this->hasOne(TransactionTypes::className(), ['id' => 'transaction_type_id']);
    }
}
