<?php

use yii\db\Migration;

/**
 * Class m180721_130052_genders_data_insert
 */
class m180721_130052_genders_data_insert extends Migration
{
    const TABLE = '{{%genders}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert(self::TABLE,
            ["id","name"],
            [
                [
                    'id' => 1,
                    'name' => 'м',
                ],
                [
                    'id' => 2,
                    'name' => 'ж',
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(self::TABLE, ["id" => [1, 2]]);
    }

}
