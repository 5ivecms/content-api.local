<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%useragent}}`.
 */
class m210626_151648_create_useragent_table extends Migration
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

        $this->createTable('{{%useragent}}', [
            'id' => $this->primaryKey(),
            'useragent' => $this->text()
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%useragent}}');
    }
}
