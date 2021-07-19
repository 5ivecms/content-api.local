<?php

use yii\db\Migration;

/**
 * Class m210712_141651_add_settings_data
 */
class m210712_141651_add_settings_data extends Migration
{
    private $table = '{{%settings}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert($this->table, [
            'type' => 'integer',
            'section' => 'cache',
            'key' => 'duration',
            'value' => 86400,
            'active' => 1,
            'created' => date('Y-m-d h:i:s'),
            'modified' => null,
        ]);

        $this->insert($this->table, [
            'type' => 'integer',
            'section' => 'proxy',
            'key' => 'enabled',
            'value' => 0,
            'active' => 1,
            'created' => date('Y-m-d h:i:s'),
            'modified' => null,
        ]);

        $this->insert($this->table, [
            'type' => 'integer',
            'section' => 'proxy',
            'key' => 'timeout',
            'value' => 5,
            'active' => 1,
            'created' => date('Y-m-d h:i:s'),
            'modified' => null,
        ]);

        $this->insert($this->table, [
            'type' => 'integer',
            'section' => 'proxy',
            'key' => 'ping',
            'value' => 300,
            'active' => 1,
            'created' => date('Y-m-d h:i:s'),
            'modified' => null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210712_141651_add_settings_data cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210712_141651_add_settings_data cannot be reverted.\n";

        return false;
    }
    */
}
