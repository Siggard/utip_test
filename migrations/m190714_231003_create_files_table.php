<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%files}}`.
 */
class m190714_231003_create_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%files}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'descr' => $this->text(),
            'hash' => $this->string(32),
            'ext' => $this->string(4)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%files}}');
    }
}
