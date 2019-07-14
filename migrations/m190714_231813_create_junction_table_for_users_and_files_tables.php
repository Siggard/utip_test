<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users_files}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 * - `{{%files}}`
 */
class m190714_231813_create_junction_table_for_users_and_files_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users_files}}', [
            'users_id' => $this->integer(),
            'files_id' => $this->integer(),
            'created_at' => $this->timestamp(),
            'PRIMARY KEY(users_id, files_id)',
        ]);

        // creates index for column `users_id`
        $this->createIndex(
            '{{%idx-users_files-users_id}}',
            '{{%users_files}}',
            'users_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-users_files-users_id}}',
            '{{%users_files}}',
            'users_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `files_id`
        $this->createIndex(
            '{{%idx-users_files-files_id}}',
            '{{%users_files}}',
            'files_id'
        );

        // add foreign key for table `{{%files}}`
        $this->addForeignKey(
            '{{%fk-users_files-files_id}}',
            '{{%users_files}}',
            'files_id',
            '{{%files}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-users_files-users_id}}',
            '{{%users_files}}'
        );

        // drops index for column `users_id`
        $this->dropIndex(
            '{{%idx-users_files-users_id}}',
            '{{%users_files}}'
        );

        // drops foreign key for table `{{%files}}`
        $this->dropForeignKey(
            '{{%fk-users_files-files_id}}',
            '{{%users_files}}'
        );

        // drops index for column `files_id`
        $this->dropIndex(
            '{{%idx-users_files-files_id}}',
            '{{%users_files}}'
        );

        $this->dropTable('{{%users_files}}');
    }
}
