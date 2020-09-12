<?php

use yii\db\Migration;

/**
 * Class m200912_135239_user__balance
 */
class m200912_135239_user__balance extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200912_135239_user__balance cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200912_135239_user__balance cannot be reverted.\n";

        return false;
    }
    */
}
