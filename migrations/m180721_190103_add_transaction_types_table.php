<?php

use yii\db\Migration;

/**
 * Class m180721_190103_add_transaction_types_table
 */
class m180721_190103_add_transaction_types_table extends Migration
{
    const TABLE = '{{%transaction_types}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'name' =>  $this->string(255)->notNull(),
                'alias' =>  $this->string(20)->notNull(),
            ], $tableOptions
        );

        $this->batchInsert(self::TABLE,
            ["id", "name", "alias"],
            [
                [
                    'id' => 1,
                    'name' => 'Commission',
                    'alias' => 'commission',
                ],
                [
                    'id' => 2,
                    'name' => 'Profit',
                    'alias' => 'profit',
                ]
            ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }

}
