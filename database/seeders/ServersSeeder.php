<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\Server;
use Illuminate\Database\Seeder;

class ServersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        for($i=0; $i < 100; $i++){
            Server::create([
               'server_name' => \Str::random(10),
                'server_ip' => $faker->ipv4(),
                'is_active' => random_int(0, 1)
            ]);
        }
    }
}
