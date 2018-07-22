<?php

use yii\db\Migration;

/**
 * Class m180721_122615_add_genders_table
 */
class m180721_122615_add_genders_table extends Migration
{
    const TABLE = '{{%genders}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'name' =>  $this->char(1)->notNull(),
            ], $tableOptions
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
