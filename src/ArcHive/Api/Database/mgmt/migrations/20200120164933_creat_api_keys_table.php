<?php

use Phinx\Migration\AbstractMigration;
use function DI\create;

class CreatApiKeysTable extends AbstractMigration
{
    public function change()
    {
        $this->table('api_keys', ['id' => false, 'primary_key' => ['device_name']])
            ->addColumn('device_name', 'string', ['limit' => 64])

            ->addColumn('key', 'char', ['limit' => 64])
            ->addColumn('enabled', 'boolean', ['default' => true])
            ->addIndex(['key'], ['unique' => true])
            ->create();
    }
}
