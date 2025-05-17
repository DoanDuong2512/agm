<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->truncate();
        DB::table('customers')->insert([
            'name' => 'Nguyễn Mạnh Dũng',
            'vn_id' => '001200006553', 
            'email' => 'mrdung20005b@gmail.com',
            'phone' => '0366065647',
            'gender' => 'male',
            'address' => 'Hanoi',
            'password' => Hash::make('dungnmzxje'), 
            'is_active' => 1, 
            'created_at' => now(),
            'updated_at' => null,
        ]);

        $faker = Faker::create('vi_VN'); // Faker hỗ trợ dữ liệu Việt Nam

        for ($i = 0; $i < 10; $i++) {
            DB::table('customers')->insert([
                'name' => $faker->name,
                'vn_id' => mt_rand(100000000000, 999999999999),
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->unique()->phoneNumber,
                'gender' => $faker->randomElement(['male', 'female', 'other']),
                'address' => $faker->address,
                'password' => Hash::make('elcom@customer'), 
                'is_active' => 1, 
                'created_at' => now(),
                'updated_at' => null,
            ]);
        }
    }
}
