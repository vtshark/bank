<?php

use yii\db\Migration;

/**
 * Class m180721_124100_add_clients_table
 */
class m180721_124100_add_clients_table extends Migration
{
    const TABLE = '{{%clients}}';

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
                'id_number' =>  $this->integer(11)->notNull()->unique(),
                'name' =>  $this->string(255)->notNull(),
                'surname' =>  $this->string(255)->notNull(),
                'gender_id' =>  $this->integer(1)->null()->defaultValue(null),
                'date_of_birth' => $this->dateTime()->notNull(),
            ],$tableOptions
        );

        $this->createIndex('gender_id', self::TABLE, ['gender_id'], false);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }

}
