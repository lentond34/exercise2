<?php

use yii\db\Migration;
use app\components\InsertDataHelper;

class m230616_175303_create_flight_segment_table extends Migration
{

    const TABLE = 'cbt.flight_segment';
    const DATAFILE = 'data_flight_segment.json';

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
            'flight_id' => $this->integer(11)->notNull(),
            'num' => $this->integer(11)->notNull(),
            'group' => $this->integer(11)->notNull(),
            'departureTerminal' => $this->string(1)->defaultValue(null),
            'arrivalTerminal' => $this->string(1)->defaultValue(null),
            'flightNumber' => $this->string(6)->defaultValue(null),
            'departureDate' => $this->string(20)->defaultValue(null),
            'arrivalDate' => $this->string(20)->defaultValue(null),
            'stopNumber' => $this->integer(11)->defaultValue(null),
            'flightTime' => $this->integer(11)->defaultValue(null),
            'eTicket' => $this->tinyInteger(1)->defaultValue(null),
            'bookingClass' => $this->string(16)->defaultValue(null),
            'bookingCode' => $this->string(1)->defaultValue(null),
            'baggageValue' => $this->integer(11)->defaultValue(null),
            'baggageUnit' => $this->string(16)->defaultValue(null),
            'depAirportId' => $this->integer(11)->defaultValue(null),
            'arrAirportId' => $this->integer(11)->defaultValue(null),
            'opCompanyId' => $this->integer(11)->defaultValue(null),
            'markCompanyId' => $this->integer(11)->defaultValue(null),
            'aircraftId' => $this->integer(11)->defaultValue(null),
            'depCityId' => $this->integer(11)->defaultValue(null),
            'arrCityId' => $this->integer(11)->defaultValue(null),
            'supplierRef' => $this->string(8)->defaultValue(null),
            'depTimestamp' => $this->integer(11)->defaultValue(null),
            'arrTimestamp' => $this->integer(11)->defaultValue(null),
        ]);
        $this->createIndex(
            'flight_id',
            self::TABLE,
            'flight_id',
        );
        $this->createIndex(
            'depAirportId',
            self::TABLE,
            'depAirportId',
        );
        $this->createIndex(
            'arrAirportId',
            self::TABLE,
            'arrAirportId',
        );
        $this->createIndex(
            'fk_flight_segment_flight',
            self::TABLE,
            'flight_id, group, depTimestamp',
        );

        $this->addForeignKey(
            'fk_flight_segment_depAirportId',
            self::TABLE,
            'depAirportId',
            'nemo_guide_etalon.airport_name',
            'airport_id',
            'CASCADE',
            'CASCADE',
        );

        $this->addForeignKey(
            'fk_flight_segment_arrAirportId',
            self::TABLE,
            'arrAirportId',
            'nemo_guide_etalon.airport_name',
            'airport_id',
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
            $this->dropForeignKey('fk_flight_segment_depAirportId', self::TABLE);
            $this->dropForeignKey('fk_flight_segment_arrAirportId', self::TABLE);
            $this->dropTable(self::TABLE);
        }
        return true;
    }
}
