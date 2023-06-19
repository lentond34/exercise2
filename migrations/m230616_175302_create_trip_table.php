<?php

use yii\db\Migration;
use app\components\InsertDataHelper;

class m230616_175302_create_trip_table extends Migration
{

    const TABLE = 'cbt.trip';
    const DATAFILE = 'data_trip.json';

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        $this->db = 'db';
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp(): bool
    {

        if ($this->db->getTableSchema(self::TABLE, true) != null) {
            $this->dropTable(self::TABLE);
        }

        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'corporate_id' => $this->integer(11)->notNull(),
            'number' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'created_at' => $this->integer(11)->notNull()->comment('Дата и время создания'),
            'updated_at' => $this->integer(11)->notNull()->comment('Дата и время последнего обновления'),
            'coordination_at' => $this->integer(11)->notNull()->comment('Дата и время согласования'),
            'saved_at' => $this->integer(11)->notNull()->comment('Дата и время сохранения'),
            'tag_le_id' => $this->integer(11)->notNull(),
            'trip_purpose_id' => $this->integer(11)->notNull(),
            'trip_purpose_parent_id' => $this->integer(11)->notNull(),
            'trip_purpose_desc' => $this->text()->notNull()->comment('Цель командировки'),
            'status' => $this->tinyInteger(1)->notNull()->comment('Статус'),
        ]);
        $this->createIndex(
            'number',
            self::TABLE,
            'corporate_id, number',
            1
        );
        $this->createIndex(
            'corporate_id_2',
            self::TABLE,
            'corporate_id, user_id, status',
        );
        $this->createIndex(
            'corporate_id_coord',
            self::TABLE,
            'corporate_id, status, coordination_at',
        );
        $this->createIndex(
            'status',
            self::TABLE,
            'status, created_at',
        );
        $this->createIndex(
            'created_at',
            self::TABLE,
            'created_at',
        );

        $insertHelper = new InsertDataHelper($this->db);
        return $insertHelper->insertFromJSON(self::TABLE, dirname(__FILE__) . '/' . self::DATAFILE);
    }

    /**
     * {@inheritdoc}
     */
    public function down(): bool
    {
        if ($this->db->getTableSchema(self::TABLE, true) != null) {
            $this->dropTable(self::TABLE);
        }
        return true;
    }
}
