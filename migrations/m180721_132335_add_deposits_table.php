<?php

use yii\db\Migration;

/**
 * Class m180721_132335_add_deposits_table
 */
class m180721_132335_add_deposits_table extends Migration
{
    const TABLE = '{{%deposits}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';
        $this->createTable(
            self::TABLE,
            [
                'id'=> $this->primaryKey(11),
                'client_id'=> $this->integer(11)->null()->defaultValue(null),
                'rate'=> $this->decimal(4, 2)->notNull()->defaultValue('0.00'),
                'amount'=> $this->decimal(11, 2)->notNull()->defaultValue('0.00'),
                'created_at'=> $this->integer(11)->null()->defaultValue(null),
                'description'=> $this->string(255)->null()->defaultValue(null),
            ],$tableOptions
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
