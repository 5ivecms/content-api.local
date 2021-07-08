<?php

use yii\db\Migration;

/**
 * Class m210626_140312_add_access_token_to_user_table
 */
class m210626_140312_add_access_token_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'access_token', $this->string()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210626_140312_add_access_token_to_user_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210626_140312_add_access_token_to_user_table cannot be reverted.\n";

        return false;
    }
    */
}
