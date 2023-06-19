<?php

namespace app\components;

use \yii\db\Connection;

class InsertDataHelper
{

    private $db;

    /**
     * {@inheritdoc}
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
        return $this;
    }



    /**
     * {@inheritdoc}
     */
    public function insertFromJSON(string $table, string $dataFilePath): bool
    {
        print_r("> Inserting data from JSON \n");
        $schema = $this->db->getTableSchema($table, true);
        $columns = $schema->getColumnNames();

        $json = file_get_contents($dataFilePath);
        $data = json_decode($json, true);

        $insertPartCount = 1000;
        $dataParts = [];
        $dataPart = [];
        $row = 0;
        foreach ($data[2]['data'] as $dataRow) {
            $dataItem = array_values($dataRow);
            $dataPart[] = $dataItem;
            $row++;


            if ($row >= $insertPartCount) {
                $row = 0;
                $dataParts[] = $dataPart;
                $dataPart = [];
            }
        }

        if (count($dataPart) < $insertPartCount) {
            $dataParts[] = $dataPart;
        }
        unset($dataPart);

        foreach ($dataParts as $dataPart) {
            $this->db->createCommand()
                ->batchInsert(
                    $table,
                    $columns,
                    $dataPart
                )->execute();
        }

        return true;
    }

}