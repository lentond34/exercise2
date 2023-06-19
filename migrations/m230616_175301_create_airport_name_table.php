<?php

use yii\db\Migration;
use app\components\InsertDataHelper;


class m230616_175301_create_airport_name_table extends Migration
{

    const TABLE = 'nemo_guide_etalon.airport_name';
    const DATAFILE = 'data_airport_name.json';

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        $this->db = 'db2';
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
            'airport_id' => $this->integer(11)->notNull(),
            'language_id' => $this->integer(11)->defaultValue(null),
            'value' => $this->string(255)->notNull(),
        ]);
        $this->createIndex(
            'object_id',
            self::TABLE,
            'airport_id, language_id',
            1
        );
        $this->createIndex(
            'object_id_2',
            self::TABLE,
            'airport_id',
        );
        $this->createIndex(
            'language',
            self::TABLE,
            'language_id',
        );
        $this->createIndex(
            'value',
            self::TABLE,
            'value',
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
