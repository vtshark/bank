<?php

use yii\db\Migration;

/**
 * Class m180721_190240_add_transactions_table
 */
class m180721_190240_add_transactions_table extends Migration
{
    const TABLE = '{{%transactions}}';

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
                'deposit_id' =>  $this->integer(11)->null()->defaultValue(null),
                'transaction_type_id' =>  $this->integer(11)->null()->defaultValue(null),
                'created_at' => $this->integer(11)->null()->defaultValue(null),
                'amount' => $this->decimal(11, 2)->notNull()->defaultValue('0.00'),
            ], $tableOptions
        );
        $this->createIndex('deposit_id', self::TABLE, ['deposit_id'], false);
        $this->createIndex('transaction_type_id', self::TABLE, ['transaction_type_id'], false);
        $this->addForeignKey('fk_deposit_id', self::TABLE, 'deposit_id', '{{%deposits}}', 'id',
            'SET NULL', 'NO ACTION'
        );
        $this->addForeignKey('fk_transaction_type_id', self::TABLE,
            'transaction_type_id', '{{%transaction_types}}', 'id',
            'SET NULL', 'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
