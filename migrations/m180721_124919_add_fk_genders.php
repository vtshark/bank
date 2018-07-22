<?php

use yii\db\Migration;

/**
 * Class m180721_124919_add_fk_genders
 */
class m180721_124919_add_fk_genders extends Migration
{
    const TABLE = '{{%clients}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk_genders',
            self::TABLE, 'gender_id',
            '{{%genders}}', 'id',
            'SET NULL', 'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_genders', self::TABLE);
    }

}
