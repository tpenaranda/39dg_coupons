<?php

namespace Tests\Feature;

use App\{Cart, Item};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    public function testGetCart()
    {
        $this->setTestCart(factory(Cart::class)->make([
            'items' => factory(Item::class, 2)->make()
        ]));

        $response = $this->getJson('/api/cart');

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data.items'));

        $response->assertJsonStructure([
            'data' => [
                'items' => [
                    '*' => [
                        'id',
                        'description',
                        'price',
                    ],
                ]
            ]
        ]);
    }

    public function testCouponFIXED10()
    {
        $this->setTestCart(factory(Cart::class)->make([
            'items' => []
        ]));

        $response = $this->putJson('/api/cart/coupon/FIXED10');

        $response->assertStatus(422);

        $this->setTestCart(factory(Cart::class)->make([
            'items' => [
                factory(Item::class)->make(['price' => 49]),
            ]
        ]));

        $response = $this->putJson('/api/cart/coupon/FIXED10');

        $response->assertStatus(422);

        $this->setTestCart(factory(Cart::class)->make([
            'items' => factory(Item::class, 2)->make(['price' => 24])
        ]));

        $response = $this->putJson('/api/cart/coupon/FIXED10');

        $response->assertStatus(422);

        $this->setTestCart(factory(Cart::class)->make([
            'items' => [
                factory(Item::class)->make(['price' => 50.10]),
            ]
        ]));

        $response = $this->putJson('/api/cart/coupon/FIXED10');

        $response->assertStatus(200);

        $this->assertEquals(50.10, $response->json('data.coupon.original_total'));
        $this->assertEquals(40.10, $response->json('data.total'));
    }

    public function testCouponPERCENT10()
    {
        $this->setTestCart(factory(Cart::class)->make([
            'items' => [
                factory(Item::class)->make(['price' => 101]),
            ]
        ]));

        $response = $this->putJson('/api/cart/coupon/PERCENT10');

        $response->assertStatus(422);

        $this->setTestCart(factory(Cart::class)->make([
            'items' => factory(Item::class, 2)->make(['price' => 49])
        ]));

        $response = $this->putJson('/api/cart/coupon/PERCENT10');

        $response->assertStatus(422);

        $this->setTestCart(factory(Cart::class)->make([
            'items' => factory(Item::class, 2)->make(['price' => 50.10])
        ]));


        $response = $this->putJson('/api/cart/coupon/PERCENT10');

        $response->assertStatus(200);

        $this->assertEquals(100.20, $response->json('data.coupon.original_total'));
        $this->assertEquals(90.18, $response->json('data.total'));
    }

    public function testCouponMIXED10()
    {
        $this->setTestCart(factory(Cart::class)->make([
            'items' => factory(Item::class, 2)->make(['price' => 100])
        ]));

        $response = $this->putJson('/api/cart/coupon/MIXED10');

        $response->assertStatus(422);

        $this->setTestCart(factory(Cart::class)->make([
            'items' => factory(Item::class, 3)->make(['price' => 50])
        ]));

        $response->assertStatus(422);

        $this->setTestCart(factory(Cart::class)->make([
            'items' => factory(Item::class, 3)->make(['price' => 75])
        ]));

        $response = $this->putJson('/api/cart/coupon/MIXED10');

        $response->assertStatus(200);

        $this->assertEquals(225, $response->json('data.coupon.original_total'));
        $this->assertEquals(202.5, $response->json('data.total'));
    }

    public function testCouponREJECTED100()
    {
        $this->setTestCart(factory(Cart::class)->make([
            'items' => factory(Item::class, 10)->make(['price' => 100])
        ]));

        $response = $this->putJson('/api/cart/coupon/REJECTED100');

        $response->assertStatus(422);

        $this->setTestCart(factory(Cart::class)->make([
            'items' => factory(Item::class, 2)->make(['price' => 600])
        ]));

        $response = $this->putJson('/api/cart/coupon/REJECTED100');

        $response->assertStatus(200);

        $this->assertEquals(1200, $response->json('data.coupon.original_total'));
        $this->assertEquals(1070, $response->json('data.total'));
    }
}
