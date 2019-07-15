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
            'password' => '$2y$13$tpJkifJ67rfbbE2sDaO0eOtX5Wu/t//AD9q7EHGKneRpTMx0RFwNS',
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
