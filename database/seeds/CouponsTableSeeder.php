<?php

use App\Coupon;
use Illuminate\Database\Seeder;

class CouponsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Coupon::class)->create([
            'name' => 'FIXED10',
            'amount' => 10,
            'rules' => [
                'min_items' => 1,
                'min_total' => 50,
            ],
            'type' => Coupon::TYPE_FIXED,
        ]);

        factory(Coupon::class)->create([
            'name' => 'PERCENT10',
            'amount' => 10,
            'rules' => [
                'min_items' => 2,
                'min_total' => 100,
            ],
            'type' => Coupon::TYPE_PERCENT,
        ]);

        factory(Coupon::class)->create([
            'name' => 'MIXED10',
            'amount' => 10,
            'rules' => [
                'min_items' => 3,
                'min_total' => 200,
            ],
            'type' => Coupon::TYPE_MIXED,
        ]);

        factory(Coupon::class)->create([
            'name' => 'REJECTED100',
            'amount' => 10,
            'rules' => [
                'min_total' => 1000,
            ],
            'type' => Coupon::TYPE_REJECTED,
        ]);
    }
}
