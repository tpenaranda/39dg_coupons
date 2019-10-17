<?php

namespace Tests\Feature;

use App\{Cart, Item, Coupon};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartTest extends TestCase
{
    public function testAddCoupon()
    {
        $cart = factory(Cart::class)->make([
            'items' => factory(Item::class, 2)->make(['price' => 25]),
        ]);

        $coupon = factory(Coupon::class)->make([
            'amount' => 25,
            'rules' => [],
            'type' => 'percent',
        ]);

        $this->assertEmpty($cart->coupon);

        $cart->addCoupon($coupon);

        $this->assertNotEmpty($cart->coupon);
        $this->assertEquals(37.5, $cart->total);
    }
}