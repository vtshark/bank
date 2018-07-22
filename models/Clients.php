<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clients".
 *
 * @property int $id
 * @property int $id_number
 * @property string $name
 * @property string $surname
 * @property int $gender_id
 * @property int $date_of_birth
 *
 * @property Genders $gender
 * @property Deposits[] $deposits
 */
class Clients extends \yii\db\ActiveRecord
{
    public $date_of_birth_1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clients';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_number', 'name', 'surname', 'date_of_birth', 'date_of_birth_1'], 'required'],
            [['id_number', 'gender_id'], 'integer'],
            [['name', 'surname'], 'string', 'max' => 255],
            [['id_number'], 'unique'],
            [['gender_id'], 'exist', 'skipOnError' => true, 'targetClass' => Genders::className(), 'targetAttribute' => ['gender_id' => 'id']],
            [['date_of_birth'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['date_of_birth_1'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_number' => 'ИНН',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'gender_id' => 'Пол',
            'date_of_birth' => 'Дата рождения',
            'date_of_birth_1' => 'Дата рождения',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGender()
    {
        return $this->hasOne(Genders::className(), ['id' => 'gender_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposits()
    {
        return $this->hasMany(Deposits::className(), ['client_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        $this->date_of_birth = date("Y-m-d H:i:s", (strtotime($this->date_of_birth_1)));
        return parent::beforeSave($insert);
    }
    public function beforeValidate()
    {
        $this->date_of_birth = date("Y-m-d H:i:s", (strtotime($this->date_of_birth_1)));
        return parent::beforeValidate();
    }
}
