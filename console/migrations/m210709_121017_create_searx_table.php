<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%searx}}`.
 */
class m210709_121017_create_searx_table extends Migration
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

        $this->createTable('{{%searx}}', [
            'id' => $this->primaryKey(),
            'host' => $this->string(255),
            'is_blocked' => $this->smallInteger()->defaultValue(0)
        ], $tableOptions);

        $this->createIndex(
            'idx-searx-host',
            'searx',
            'host',
            true
        );

        $this->createIndex(
            'idx-searx-is_blocked',
            'searx',
            'is_blocked'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%searx}}');
    }
}
