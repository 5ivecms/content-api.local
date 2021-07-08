<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%setting}}`.
 */
class m210626_204117_create_setting_table extends Migration
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

        $this->createTable('{{%setting}}', [
            'id' => $this->primaryKey(),
            'option' => $this->string(255),
            'value' => $this->text(),
            'default' => $this->text(),
            'label' => $this->text(),
        ], $tableOptions);

        $this->createIndex(
            'idx-setting-option',
            'setting',
            'option',
            true
        );

        // Настройки кеширования
        $this->insert('{{%setting}}', [
            'option' => 'cache.duration',
            'value' => 120,
            'default' => 120,
            'label' => 'Длительность кеширования (секунды)'
        ]);

        // Настройки прокси
        $this->insert('{{%setting}}', [
            'option' => 'proxy.enabled',
            'value' => 1,
            'default' => 1,
            'label' => 'Использоивать прокси'
        ]);

        $this->insert('{{%setting}}', [
            'option' => 'proxy.timeout',
            'value' => 10,
            'default' => 10,
            'label' => 'Таймаут (сек)'
        ]);

        $this->insert('{{%setting}}', [
            'option' => 'proxy.ping',
            'value' => 500,
            'default' => 500,
            'label' => 'Допустимый пинг, ms'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%setting}}');
    }
}
