<?php
namespace ArcHive\Api\Database\Tables;

use stdClass;
use TurboPancake\Database\Table;

class ReportsTable extends Table {

    protected $table = 'reports';

    public function getLastReport(): stdClass
    {
        return ($this->makeQuery())
            ->order('created_at DESC')
            ->limit(1)
            ->fetch();
    }

    public function getLast14Reports()
    {
        return ($this->makeQuery())
            ->order('created_at DESC')
            ->limit(14)
            ->fetchAll();
    }

}