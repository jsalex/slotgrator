<?php

use yii\db\Migration;

/**
 * Class m200912_135135_prize__list
 */
class m200912_135135_prize__list extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'prize_name' => $this->string();
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%prize__list}}');
    }

}
