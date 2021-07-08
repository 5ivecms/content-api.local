<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%blacklist}}`.
 */
class m210703_143741_create_blacklist_table extends Migration
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

        $this->createTable('{{%blacklist}}', [
            'id' => $this->primaryKey(),
            'domain' => $this->string(255)
        ], $tableOptions);

        $this->createIndex(
            'idx-blacklist-domain',
            'blacklist',
            'domain',
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blacklist}}');
    }
}
