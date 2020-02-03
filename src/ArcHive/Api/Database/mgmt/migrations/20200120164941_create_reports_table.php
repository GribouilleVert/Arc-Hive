<?php

use Phinx\Migration\AbstractMigration;

class CreateReportsTable extends AbstractMigration
{
    public function change()
    {
        $this->table('reports')
            ->addColumn('device_name', 'string', ['limit' => 64, 'null' => true])
            ->addColumn('someone_present', 'boolean')
            ->addColumn('inside_temperature', 'decimal', ['precision' => 3, 'scale' => 1])
            ->addColumn('inside_humidity', 'decimal', ['precision' => 3, 'scale' => 1])
            ->addColumn('outside_temperature', 'decimal', ['precision' => 3, 'scale' => 1])
            ->addColumn('outside_humidity', 'decimal', ['precision' => 3, 'scale' => 1])
            ->addTimestamps()

            ->addForeignKey('device_name', 'api_keys', 'device_name', [
                'update' => 'CASCADE',
                'delete' => 'SET_NULL'
            ])
            ->create();
    }
}
