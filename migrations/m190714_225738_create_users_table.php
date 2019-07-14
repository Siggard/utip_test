<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m190714_225738_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),

        ]);

        $this->insert('users', [
            'username' => 'admin',
            'password' => '00a1f187721c63501356bf791e69382c',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('users', ['id' => 1]);
        $this->dropTable('{{%users}}');
    }
}
