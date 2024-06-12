<?php

use yii\db\Migration;

/**
 * Class m240612_094646_create_table_organizators_events
 */
class m240612_094646_create_table_organizators_events extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%organizators_events}}', [
            'organizator_id' => $this->integer()->notNull(),
            'event_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey(
            'pk_organizators_events',
            '{{%organizators_events}}',
            ['organizator_id', 'event_id']
        );

        $this->addForeignKey(
            'fkx-organizators_events-organizator_id',
            '{{%organizators_events}}',
            'organizator_id',
            '{{%organizators}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fkx-organizators_events-event_id',
            '{{%organizators_events}}',
            'event_id',
            '{{%events}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%organizators_events}}');
    }
}
