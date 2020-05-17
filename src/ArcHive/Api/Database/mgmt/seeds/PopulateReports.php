<?php


use Faker\Factory;
use Phinx\Seed\AbstractSeed;

class PopulateReports extends AbstractSeed
{
    public function run()
    {
        $datas = [];

        $faker = Factory::create('fr_FR');
        for ($i = 100; $i > 0; $i--) {
            $datas[] = [
                'device_name' => 'dev',
                'someone_present' => 0,
                'inside_temperature' => $faker->randomFloat(2, -15, 35),
                'outside_temperature' => $faker->randomFloat(2, -15, 35),
                'inside_humidity' => $faker->randomFloat(2, 0, 99),
                'outside_humidity' => $faker->randomFloat(2, 0, 99),
                'created_at' => date('Y-m-d H:i:s', time() - ($i * 60)),
            ];
        }
        $this->table('reports')
            ->insert($datas)
            ->save();
    }
}
