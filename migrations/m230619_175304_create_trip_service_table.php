<?php

use yii\db\Migration;
use app\components\InsertDataHelper;

class m230619_175304_create_trip_service_table extends Migration
{

    const TABLE = 'cbt.trip_service';
    const DATAFILE = 'data_trip_service.json';

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
            'trip_id' => $this->integer(11)->notNull(),
            'service_id' => $this->integer(11)->notNull(),
            'status' => $this->integer(4)->defaultValue(0),
            'type_booking' => $this->integer(1)->notNull()->comment('Тип заказа'),
            'variants' => $this->integer(11)->notNull()->comment('Варианты'),
            'price' => $this->decimal(14,3)->defaultValue(null),
            'currency' => $this->string(3)->defaultValue(null),
            'confirmation_number' => $this->string(16)->defaultValue(null),
            'deadline' => $this->integer(11)->defaultValue(null),
            'date_start' => $this->integer(11)->defaultValue(null),
            'date_end' => $this->integer(11)->defaultValue(null),
            'start_city_id' => $this->integer(11)->defaultValue(null),
            'start_point_id' => $this->integer(11)->defaultValue(null),
            'end_point_id' => $this->integer(11)->defaultValue(null),
            'end_city_id' => $this->integer(11)->defaultValue(null),
            'description' => $this->text()->notNull(),
        ]);
        $this->createIndex(
            'service_id',
            self::TABLE,
            'service_id',
        );
        $this->createIndex(
            'trip_id',
            self::TABLE,
            'trip_id',
        );
        $this->createIndex(
            'status',
            self::TABLE,
            'trip_id, status',
        );
        $this->createIndex(
            'deadline_time',
            self::TABLE,
            'trip_id, deadline',
        );
        $this->createIndex(
            'date_start',
            self::TABLE,
            'trip_id, date_start',
        );
        $this->createIndex(
            'date_end',
            self::TABLE,
            'trip_id, date_end',
        );
        $this->createIndex(
            'confirmation_number',
            self::TABLE,
            'trip_id, confirmation_number',
        );
        $this->createIndex(
            'trip_united',
            self::TABLE,
            'trip_id, service_id, type_booking, variants',
        );
        $this->createIndex(
            'variants',
            self::TABLE,
            'trip_id, variants',
        );
        $this->createIndex(
            'type_booking',
            self::TABLE,
            'trip_id, type_booking',
        );
        $this->createIndex(
            'start_city_id',
            self::TABLE,
            'trip_id, start_city_id',
        );
        $this->createIndex(
            'start_point_id',
            self::TABLE,
            'trip_id, start_point_id',
        );
        $this->createIndex(
            'end_point_id',
            self::TABLE,
            'trip_id, end_point_id',
        );
        $this->createIndex(
            'end_city_id',
            self::TABLE,
            'trip_id, end_city_id',
        );

        $this->addForeignKey(
            'fk_trip_service_trip_id',
            self::TABLE,
            'trip_id',
            'cbt.trip',
            'id',
            'CASCADE',
            'CASCADE',
        );

        $this->addForeignKey(
            'fk_trip_flight_id',
            self::TABLE,
            'id',
            'cbt.flight_segment',
            'flight_id',
            'CASCADE',
            'CASCADE',
        );

        $this->execute("SET foreign_key_checks = 0;");
        $insertHelper = new InsertDataHelper($this->db);
        $res = $insertHelper->insertFromJSON(self::TABLE, dirname(__FILE__) . '/' . self::DATAFILE);
        $this->execute("SET foreign_key_checks = 1;");
        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function down(): bool
    {
        if ($this->db->getTableSchema(self::TABLE, true) != null) {
            $this->dropForeignKey('fk_trip_service_trip_id', self::TABLE);
            $this->dropForeignKey('fk_trip_flight_id', self::TABLE);
            $this->dropTable(self::TABLE);
        }
        return true;
    }
}
