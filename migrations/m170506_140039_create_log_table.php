<?php

use yii\db\Migration;

/**
 * Handles the creation of table `log`.
 */
class m170506_140039_create_log_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('log', [
            'id' => $this->primaryKey(),
            'time' => 'INTEGER NOT NULL',
            'ip' => 'TEXT NOT NULL',
            'userId' => 'INTEGER NOT NULL',
            'eventType' => 'INTEGER(1) NOT NULL',
            'eventData' => 'TEXT DEFAULT NULL',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('log');
    }
}
