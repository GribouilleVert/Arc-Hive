<?php
namespace ArcHive\Api\Database\Tables;

use TurboPancake\Database\Table;

class ApiKeysTable extends Table {

    protected $table = 'api_keys';

    protected $customIdColumn = 'device_name';


    public function checkKey(string $key)
    {
        return $this->makeQuery()
            ->where('`key` = :key', '`enabled` = 1')
            ->parameters(['key' => $key])
            ->count('device_name') > 0;
    }

}