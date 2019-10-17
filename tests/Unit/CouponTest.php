<?php

namespace Tests\Feature;

use App\{Cart, Item, Coupon};
use Tests\TestCase;

class CouponTest extends TestCase
{
    public function testIsValidForCart()
    {
        $cart = factory(Cart::class)->make([
            'items' => factory(Item::class, 2)->make(['price' => 25]),
        ]);

        $this->assertTrue(factory(Coupon::class)->make(['rules' => ['min_items' => 1]])->isValidForCart($cart));
        $this->assertTrue(factory(Coupon::class)->make(['rules' => ['min_items' => 2]])->isValidForCart($cart));
        $this->assertFalse(factory(Coupon::class)->make(['rules' => ['min_items' => 3]])->isValidForCart($cart));

        $this->assertTrue(factory(Coupon::class)->make(['rules' => ['min_total' => 49]])->isValidForCart($cart));
        $this->assertFalse(factory(Coupon::class)->make(['rules' => ['min_total' => 50]])->isValidForCart($cart));
        $this->assertFalse(factory(Coupon::class)->make(['rules' => ['min_total' => 51]])->isValidForCart($cart));
    }
}
