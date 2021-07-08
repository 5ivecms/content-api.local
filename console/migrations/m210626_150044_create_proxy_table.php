<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%proxy}}`.
 */
class m210626_150044_create_proxy_table extends Migration
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

        $this->createTable('{{%proxy}}', [
            'id' => $this->primaryKey(),
            'ip' => $this->string(255)->notNull(),
            'port' => $this->string(255)->notNull(),
            'type' => $this->string(255)->notNull(),
            'protocol' => $this->string(12),
            'login' => $this->string(255),
            'password' => $this->string(255),
            'totalTime' => $this->integer()->defaultValue(0),
            'connectTime' => $this->integer()->defaultValue(0),
            'pretransferTime' => $this->integer()->defaultValue(0),
            'countCaptcha' => $this->integer()->defaultValue(0),
            'countErrors' => $this->integer()->defaultValue(0),
            'redirected' => $this->integer(1)->defaultValue(0),
            'status' => $this->integer(2)->defaultValue(1),
        ], $tableOptions);

        $this->createIndex(
            'idx-proxy-ip',
            'proxy',
            'ip'
        );

        $this->createIndex(
            'idx-proxy-port',
            'proxy',
            'port'
        );

        $this->createIndex(
            'idx-proxy-type',
            'proxy',
            'type'
        );

        $this->createIndex(
            'idx-proxy-protocol',
            'proxy',
            'protocol'
        );

        $this->createIndex(
            'idx-proxy-totalTime',
            'proxy',
            'totalTime'
        );

        $this->createIndex(
            'idx-proxy-connectTime',
            'proxy',
            'connectTime'
        );

        $this->createIndex(
            'idx-proxy-pretransferTime',
            'proxy',
            'pretransferTime'
        );

        $this->createIndex(
            'idx-proxy-countCaptcha',
            'proxy',
            'countCaptcha'
        );

        $this->createIndex(
            'idx-proxy-redirected',
            'proxy',
            'redirected'
        );

        $this->createIndex(
            'idx-proxy-countErrors',
            'proxy',
            'countErrors'
        );

        $this->createIndex(
            'idx-proxy-status',
            'proxy',
            'status'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%proxy}}');
    }
}
