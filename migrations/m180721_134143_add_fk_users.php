<?php

use yii\db\Migration;

/**
 * Class m180721_134143_add_fk_users
 */
class m180721_134143_add_fk_users extends Migration
{
    const TABLE = '{{%deposits}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk_clients',
            self::TABLE, 'client_id',
            '{{%clients}}', 'id',
            'SET NULL', 'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_clients', self::TABLE);
    }

}
