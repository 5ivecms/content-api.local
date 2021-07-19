<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%whoogle}}`.
 */
class m210717_131554_create_whoogle_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%whoogle}}', [
            'id' => $this->primaryKey(),
            'host' => $this->string(255),
            'is_blocked' => $this->smallInteger()->defaultValue(0)
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%whoogle}}');
    }
}
